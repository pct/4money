<?php

// option app function
function get_options($option_keys){
    $options = array();

    foreach ($option_keys as $ok) {
        $tmp = ORM::for_table('option')->where('option_key', $ok)->find_one();
        $options[$ok] = ($tmp) ? $tmp->option_value : '';
    }

    return $options;
}

function render_option_defaut_value($data_keys) {
    $data = array();
    foreach ($data_keys as $k => $v) {
        $tmp = ORM::for_table('option')->where('option_key', $k)->find_one();
        $data[$k] = ($tmp) ? $tmp->option_value : '請輸入'.$v;
    }
	return $data;
}

// option CRUD
$app->get('/company_options', function() use ($app) {
	$app->applyHook('account.check_sysadmin');
    $data_keys = array(
        'company_name'     => '公司名稱',
        'company_id'       => '公司統編',
        'company_phone'    => '公司電話',
        'company_fax'      => '公司傳真',
        'company_addr'     => '公司地址',
        'company_email'    => '預設 Email',
        'company_username' => '預設聯絡人',
        'breadcrumb_title' => '公司資料',
    );

	$data=render_option_defaut_value($data_keys);

    $app->render('company_options.html', $data);
});

$app->get('/bank_options', function() use ($app) {
	$app->applyHook('account.check_sysadmin');
    $data_keys = array(
        'bank_company_title' => '公司戶名',
        'bank_name'          => '銀行',
        'bank_code'          => '銀行代碼',
        'bank_account'       => '帳號',
        'breadcrumb_title'   => '銀行帳戶',
    );

    $data=render_option_defaut_value($data_keys);

    $app->render('bank_options.html', $data);
});

$app->get('/advance_options', function() use ($app) {
	$app->applyHook('account.check_sysadmin');
    $data_keys = array(
        'quotation_id_prefix' => '報價單前綴編號或文字',
        'breadcrumb_title'    => '進階設定',
		'auth_pop3_host'      => 'POP3伺服器位址',
		'auth_pop3_msg'       => 'POP3認證密碼變更訊息',
    );

    $data=render_option_defaut_value($data_keys);

    $app->render('advance_options.html', $data);
});

$app->post('/ajax_save_options_auth', function() use ($app) {
	$app->applyHook('account.check_sysadmin');
	$options = array(
		'auth_pop3_host',
		'auth_pop3_msg'
	);

	$post = $app->request()->post();
	

	foreach ($options as $o) {
		if (isset($post[$o])){
			$tmp = ORM::for_table('option')->where('option_key', $o)->find_one();
			if(!$tmp){
				$tmp=ORM::for_table('option')->create();
			}
			$tmp->option_key = $o;
			$tmp->option_value = $post[$o];
			$tmp->save();
		}
	}
	
	$data = array('class' => 'success','msg'   => '資料更新成功！');
	$app->render('_notice.html', $data);
});

$app->post('/ajax_save_options', function() use ($app) {
	$app->applyHook('account.check_sysadmin');
    $options = array(
        'company_name', 
        'company_id', 
        'company_phone',
        'company_fax',
        'company_addr',
        'bank_company_title',
        'bank_name',
        'bank_code',
        'bank_account',
        'quotation_id_prefix'
    );

    $post = $app->request()->post();

    if ($post) {
        foreach ($options as $o) {
            if (isset($post[$o])) {
                $tmp = ORM::for_table('option')->where('option_key', $o)->find_one();
                if (!$tmp) {
                    $tmp = ORM::for_table('option')->create();
                }
                $tmp->option_key = $o;
                $tmp->option_value = $post[$o];
                $tmp->save();
            }
        }
        $data = array(
            'class' => 'success',
            'msg'   => '資料更新成功！'
        );
    } else {
        $data = array(
            'class' => 'error',
            'msg'   => '資料請勿留空！'
        );
    }
    $app->render('_notice.html', $data);
});
