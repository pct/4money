<?php
$app->get('/profile', function() use ($app) {
	$tpl=array('breadcrumb_title'=>'偏好設定');

	$user=ORM::for_table('account')->where('acc_name',$_SESSION['auth_uid'])->find_one();
	$tpl['user']=$user;

	$opt=array();
	$tmp=ORM::for_table('option')->where_like('option_key', 'auth_%')->find_many();
	foreach($tmp as $item){
		$opt[$item->option_key]=$item->option_value;
	}
	$tpl['option']=$opt;

	$info=unserialize($user->acc_company);
	if(is_array($info)){
		while($k=key($info)){
			$tpl['info_'.$k]=$info[$k];
			next($info);
		}
	}
	$app->render('profile.html', $tpl);
});

$app->post('/ajax_save_pwd', function() use ($app) {
	$post = $app->request()->post();
	$auth=new AUTH($_SESSION['auth_uid']);
	if($auth->login($post['old_pwd'])){
		if($post['new_pwd']!=$post['confirm_pwd']){
			$msg=array('class'=>'error','msg'=>'兩次輸入的密碼不同');
		}else{
			$rt=$auth->setpwd($post['new_pwd']);
			if($rt){
				$msg=array('class'=>'success','msg'=>'變更密碼完成');
			}else{
				$msg=array('class'=>'error','msg'=>'變更失敗');
			}
		}
	}else{
		$msg=array('class'=>'error','msg'=>'舊密碼錯誤');
	}
	$app->render('_notice.html', $msg);
});

$app->post('/ajax_save_info', function() use ($app) {
	$post = $app->request()->post();
	$data=array(
		'name'=>$post['info_name'],
		'phone'=>$post['info_phone'],
		'email'=>$post['info_email']);
	$user=ORM::for_table('account')->where('acc_name',$_SESSION['auth_uid'])->find_one();
	$user->acc_company=serialize($data);
	if($user->save()){
		$msg=array('class'=>'success','msg'=>'儲存完成');
	}else{
		$msg=array('class'=>'error','msg'=>'儲存失敗');
	}
	$app->render('_notice.html', $msg);
});
?>