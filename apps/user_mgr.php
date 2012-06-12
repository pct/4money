<?php
$app->get('/user_mgr', function() use ($app) {
	$app->applyHook('account.check_sysadmin');
	$status_words=array('enable'=>'啟用','disable'=>'停用');
	$type_words=AUTH::get_support_auth_type();
	$tpl=array('breadcrumb_title'=>'帳號管理','status_words'=>$status_words,'type_words'=>$type_words);

	$user=ORM::for_table('account')->order_by_asc('acc_name')->find_many();
	$tpl['account']=$user;

	$log_tmp=ORM::for_table('account_log')->where('log_event','success')->group_by('log_user')->select('log_user')->select_expr('MAX(log_time)','log_time')->find_many();
	$log=array();
	foreach($log_tmp as $l){
		$log[$l->log_user]=$l->log_time;
	}
	$tpl['log']=$log;

	$app->render('user_mgr_list.html', $tpl);
});

/*
 * 新增
 */
$app->get('/user_add', function() use ($app) {
	$app->applyHook('account.check_sysadmin');
	$type_words=AUTH::get_support_auth_type();
	$tpl=array('breadcrumb_title'=>'新增帳號','type_words'=>$type_words);
	$app->render('user_mgr_add.html',$tpl);
});

$app->post('/ajax_user_add', function() use ($app){
	$app->applyHook('account.check_sysadmin');
	$post = $app->request()->post();
	$msg='';
	if($post['account_id']==''){
		$msg=array('class'=>'error','msg'=>'帳號不得為空值');
	}else if(strlen($post['account_id'])>32){
		$msg=array('class'=>'error','msg'=>'帳號超過32個字元');
	}
	$acc_count=ORM::for_table('account')->where('acc_name',$post['account_id'])->count();
	if($acc_count>0){
		$msg=array('class'=>'error','msg'=>'已存在相同名稱的帳號');
	}
	if($post['account_type']=='db'){
		if($post['account_pwd']=='' || $post['account_pwd2']==''){
			$msg=array('class'=>'error','msg'=>'請輸入密碼');
		}else if($post['account_pwd']!=$post['account_pwd2']){
			$msg=array('class'=>'error','msg'=>'兩次輸入的密碼不同');
		}
	}

	if($msg==''){
		$company=serialize(array('name'=>$post['account_name'],'phone'=>$post['account_phone'],'email'=>$post['account_email']));
		$acc=ORM::for_table('account')->create();
		$acc->acc_name=$post['account_id'];
		$acc->acc_auth_type=$post['account_type'];
		$acc->acc_flag='disable';
		$acc->acc_company=$company;
		$acc->save();
		
		if($post['account_type']=='db'){
			$auth=new AUTH($post['account_id']);
			$auth->setpwd($post['account_pwd']);
		}
		
		$msg=array('class'=>'success','msg'=>'帳號新增完成');
	}
	$app->render('_notice.html', $msg);
});

/*
 * 修改
 */
$app->get('/user_edit/:id', function($id) use ($app) {
	$app->applyHook('account.check_sysadmin');
	$status_words=array('enable'=>'啟用','disable'=>'停用');
	$type_words=AUTH::get_support_auth_type();
	$user=ORM::for_table('account')->where('acc_name',$id)->find_one();
	$info=unserialize($user->acc_company);
	$tpl=array('breadcrumb_title'=>'修改帳號','status_words'=>$status_words,'type_words'=>$type_words,'user'=>$user,'info'=>$info);

	$app->render('user_mgr_edit.html',$tpl);
});

$app->post('/ajax_user_edit', function() use ($app){
	$app->applyHook('account.check_sysadmin');
	$post = $app->request()->post();
	$msg='';
	$acc_count=ORM::for_table('account')->where('acc_name',$post['account_id'])->count();
	if($acc_count==0){
		$msg=array('class'=>'error','msg'=>'帳號不存在');
	}
	if($post['account_type']=='db'){
		if($post['account_pwd']!=$post['account_pwd2']){
			$msg=array('class'=>'error','msg'=>'兩次輸入的密碼不同');
		}
	}

	if($msg==''){
		$company=serialize(array('name'=>$post['account_name'],'phone'=>$post['account_phone'],'email'=>$post['account_email']));
		$acc=ORM::for_table('account')->where('acc_name',$post['account_id'])->find_one();
		$acc->acc_auth_type=$post['account_type'];
		$acc->acc_flag=$post['account_flag'];
		$acc->acc_company=$company;
		$acc->save();

		if($post['account_type']=='db' && $post['account_pwd']!=''){
			$auth=new AUTH($post['account_id']);
			$auth->setpwd($post['account_pwd']);
		}

		$msg=array('class'=>'success','msg'=>'帳號修改完成');
	}
	$app->render('_notice.html', $msg);
});

/*
 * 刪除
 */
$app->get('/user_delete/:id', function($id) use ($app) {
	$app->applyHook('account.check_sysadmin');
	$type_words=AUTH::get_support_auth_type();
	$tpl=array('breadcrumb_title'=>'刪除帳號','type_words'=>$type_words);
	
	$user=ORM::for_table('account')->where('acc_name',$id)->find_one();
	$tpl['acc_name']=$user->acc_name;
	$tpl['acc_auth_type']=$user->acc_auth_type;
	$info=unserialize($user->acc_company);
	if(is_array($info)){
		while($k=key($info)){
			$tpl['info_'.$k]=$info[$k];
			next($info);
		}
	}
	
	$app->render('user_mgr_delete.html',$tpl);
});

$app->post('/ajax_user_delete', function() use ($app) {
	$app->applyHook('account.check_sysadmin');
	$post = $app->request()->post();
	$user = ORM::for_table('account')->where('acc_name',$post['id'])->find_one();
	
	if($user!==false){
		$ret = $user->delete();
	}else{
		$ret=false;
	}
	
    if ($ret) {
        $data = array(
            'class' => 'success',
            'msg'   => '使用者刪除成功！',
            'link'  => 'user_mgr',
            'link_msg' => '回使用者列表'
        );
    } else {
        $data = array(
            'class' => 'error',
            'msg'   => '使用者刪除失敗，請重試！'
        );
    }
    $app->render('_notice.html', $data);
});

/*
 * Log
 */
$app->get('/log/:type', function($type) use ($app) {
	$app->applyHook('account.check_sysadmin');
	if($type=='login'){
		$app->render('user_log_login.html',array('breadcrumb_title'=>'登入紀錄'));
	}
 });
 
$app->post('/log_ajax/:type', function($type) use ($app) {
	$app->applyHook('account.check_sysadmin');
	$post = $app->request()->post();

	if($type=='login'){
		$totalcount=ORM::for_table('account_log')->count();
		
		$obj=ORM::for_table('account_log');
		$totaldisplaycount=ORM::for_table('account_log');
		if(!empty($post['sSearch'])){
			$obj->where_raw("`log_user` LIKE ? OR `log_ip` LIKE ?",array('%'.$post['sSearch'].'%','%'.$post['sSearch'].'%'));
			$totaldisplaycount->where_raw("`log_user` LIKE ? OR `log_ip` LIKE ?",array('%'.$post['sSearch'].'%','%'.$post['sSearch'].'%'));
		}
		
		$cn=$post['iSortingCols'];
		$col=array('log_time','log_user','log_ip','log_event');
		for($n=0;$n<$cn;$n++){
			$col_name=$col[(int)($post['iSortCol_'.$n])];
			$col_sort=$post['sSortDir_'.$n];
			if($col_sort=='asc'){
				$obj->order_by_asc($col_name);
			}else{
				$obj->order_by_desc($col_name);
			}
		}
		$obj->order_by_asc('log_event');
		
		$obj=$obj->limit($post['iDisplayLength'])->offset($post['iDisplayStart'])->find_many();
		$totaldisplaycount=$totaldisplaycount->count();
		
		$log=array();
		foreach($obj as $item){
			$log[]=array(date('Y-m-d H:i:s',$item->log_time),$item->log_user,$item->log_ip,$item->log_event);
		}
		//print_r($log);
		$data=array('data'=>$log,'echo'=>$post['sEcho'],'total'=>$totalcount,'totaldisplay'=>$totaldisplaycount);
		$app->render('ajax_get_log_datatables.json',$data);
	}else if($type=='unlock'){
		$ip=$post['ip'];
		$obj=ORM::for_table('account_log')->create();
		$obj->log_time=time();
		$obj->log_user=$_SESSION['auth_uid'];
		$obj->log_ip=$ip;
		$obj->log_event='unblock';
		$rt=$obj->save();
		if($rt){
			echo 'ok';
		}else{
			echo 'err';
		}
	}
 });
?>