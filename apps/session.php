<?php

class AUTH{
	public $acc_auth_type;
	public $acc_flag;
	private $acc;
	function __construct($uid){
		$this->acc=ORM::for_table('account')->where_equal('acc_name',$uid)->find_one();
		if($this->acc===false){
			$this->acc_flag='unknown_user';
		}else{
			$this->acc_auth_type=$this->acc->acc_auth_type;
			$this->acc_flag=$this->acc->acc_flag;
		}
	}
	function login($pwd){
		if( $this->acc===false || $this->acc_flag=='locked' || $this->acc_flag=='disable'){
			return false;
		}
		if( $this->acc_auth_type=='db' && $this->acc->acc_pwd==sha1($this->acc->acc_salt.'$'.$pwd) ){
				return true;
		}
		if( $this->acc_auth_type=='pop3' ){
			$pop3_host=$this->get_opt('auth_pop3_host');
			if($pop3_host!==false){
				$fs=fsockopen($pop3_host,110,$errno,$errstr,5);
				socket_set_timeout($fs,5);
				if(!$fs){
					return false;
				}
				//connected
				$msg=fgets($fs,256);
				//post ID
				fputs($fs,"USER ".$this->acc->acc_name."\r\n");
				$msg=fgets($fs,256);
				if(strpos($msg,'+OK')===false){
					return false;
				}
				//post password
				fputs($fs,"PASS $pwd\r\n");
				$msg=fgets($fs,256);
				if(strpos($msg,'+OK')===false){
					return false;
				}
				//quit
				fputs($fs,"QUIT \r\n");
				fclose($fs);
				return true;
			}
		}
		return false;
	}
	function setpwd($new_pwd){
		if( $this->acc==false || $this->acc_auth_type!='db' ){
			return false;
		}
		$salt='';
		for($n=0;$n<=rand(8,16);$n++){
			$salt.=chr(rand(65,90)).chr(rand(48,57)).chr(rand(97,122));
		}
		$this->acc->acc_salt=$salt;
		$this->acc->acc_pwd=sha1($salt.'$'.$new_pwd);
		$this->acc->save();
		return true;
	}
	function get_log($type=0,$size=5){//type 0:failed 1:success 2:all user all type
		$type=(int)$type;
		$log=ORM::for_table('account_log');
		if(0==$type){
			$type='failed';
		}else if(1==$type){
			$type='success';
		}
		if($type<2){
			return $log->where_equal('log_user',$this->acc->acc_name)->where_equal('log_event',$type)->limit($size)->order_by_desc('log_time')->find_many();
		}else if(2==$type){
			return $log->order_by_desc('log_time')->find_many();
		}else{
			return false;
		}
	}
	function get_opt($item){
		$tmp = ORM::for_table('option')->where('option_key', $item)->find_one();
		if($tmp){
			return $tmp->option_value;
		}
		return false;
	}
	static function get_support_auth_type(){
		$t=array('db'=>'資料庫','pop3'=>'POP3伺服器');
		if(in_array('ldap',get_loaded_extensions())){
			$t['ldap']='Windows AD';
		}
		return $t;
	}
	static function is_block(){
		$block_ttl=300;
		$log=ORM::for_table('account_log')->where('log_event','block')
			->where('log_ip',$_SERVER['REMOTE_ADDR'])->where_gte('log_time',time()-$block_ttl)->order_by_desc('log_time')->find_one();

		$unblock=ORM::for_table('account_log')->where('log_event','unblock')
			->where('log_ip',$_SERVER['REMOTE_ADDR'])->where_gte('log_time',time()-$block_ttl)->order_by_desc('log_time')->find_one();

		if($log===false){
			return false;
		}else if($unblock===false){
			return true;
		}

		if($log->log_time > $unblock->log_time){
			return true;
		}else{
			return false;
		}
	}
}

$app->hook('slim.before', function() use ($app) {
	if(isset($_SESSION['auth']) && $_SESSION['auth']==true){
		$app->view()->setData('session_uid',$_SESSION['auth_uid']);
		$app->view()->setData('SECURITY_LEVEL',SECURITY_LEVEL);
		$app->view()->setData('SYSTEM_ADMIN',unserialize(SYSTEM_ADMIN));
	}else{
		$login_res=array('/login','/css/layout.css');
		if(!in_array($app->config('curr_url'),$login_res)){
			if($app->request()->getMethod()=='GET'){
				$app->redirect($app->config('full_doc_root').'/login');
			}else{
				$data = array('class' => 'error','msg'   => '您的登入狀態已逾時','link'  => 'login','link_msg' => '按此重新登入');
				$app->render('_notice.html', $data);
				ob_end_flush();//訊息太短沒有這個會輸出兩次訊息 on php 5.4.1
				$app->stop();
			}
		}
	}
});

$app->hook('account.check_sysadmin', function() use ($app) {
	if(!in_array($_SESSION['auth_uid'],unserialize(SYSTEM_ADMIN))){
		if($app->request()->getMethod()=='GET'){
			ob_end_flush();
			$app->render('access_denied.html', array('breadcrumb_title' => '權限不足'));
			ob_end_flush();
			$app->stop();
		}else{
			ob_end_flush();
			$app->render('_notice.html', array('class' => 'error','msg'   => '權限不足'));
			ob_end_flush();
			$app->stop();
		}
	}
});

$app->hook('account.login_failed', function($uid) use ($app) {
	$app->applyHook('account.log',array('u'=>$uid,'e'=>'failed'));
	$block_range=60;
	$block_count=3;

	$unblock=ORM::for_table('account_log')->where('log_event','unblock')
		->where('log_ip',$_SERVER['REMOTE_ADDR'])->where_gte('log_time',time()-$block_range)->order_by_desc('log_time')->find_one();

	$st=time()-$block_range;
	if($unblock!==false){
		$st=$unblock->log_time;
	}
	$log=ORM::for_table('account_log')->where('log_event','failed')
		->where('log_ip',$_SERVER['REMOTE_ADDR'])->where_gte('log_time',$st)->count();

	if($log>=$block_count){
		$app->applyHook('account.log',array('u'=>$uid,'e'=>'block'));
	}
});

$app->hook('account.login_success', function($uid) use ($app){
	$app->applyHook('account.log',array('u'=>$uid,'e'=>'success'));
});

$app->hook('account.log',function($argv) use ($app) {
	$log=ORM::for_table('account_log')->create();
	$log->log_time=time();
	$log->log_user=$argv['u'];
	$log->log_event=$argv['e'];
	$log->log_ip=$_SERVER['REMOTE_ADDR'];
	$log->save();
});

$app->get('/login', function() use ($app) {
	$tpl=array (
		'page_title' => '登入',
		'uid_item' => '帳　號',
		'pwd_item' => '密　碼',
		'code' => '驗證碼',
		'ip_block_msg' => '您登入錯誤過於頻繁，已暫時封鎖IP');
	if(AUTH::is_block()){
		$tpl['ip_block']=true;
	}
	if(SECURITY_LEVEL==0){ //停用認證
		$_SESSION['auth']=true;
		$_SESSION['auth_uid']='admin';
		$app->applyHook('account.login_success','admin');
		$app->redirect('./');
	}
	$app->render('login.html', $tpl);
});

$app->get('/logout', function() use ($app){
	session_destroy();
	$app->redirect('login');
});

$app->post('/login',function() use ($app) {
	//block ip
	if(AUTH::is_block()){
		$app->redirect('login');
	}

	$post = $app->request()->post();
	if(isset($post['uid'])&&isset($post['pwd'])){
		$uid=$post['uid'];
		$pwd=$post['pwd'];
		if($uid==''||$pwd==''){
			$app->redirect('login');
		}
		$auth=new AUTH($uid);
		if($auth->login($pwd)===true){
			$_SESSION['auth']=true;
			$_SESSION['auth_uid']=$uid;
			$app->applyHook('account.login_success',$uid);
			$app->redirect('./');
		}else{
			if($auth->acc_flag=='locked'){
				$app->applyHook('account.log',array($uid,'locked'));
			}else{
				$app->applyHook('account.login_failed',$uid);
			}
		}
	}
	$app->redirect('login');
});

?>