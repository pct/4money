<?php

// quotation app hooks
$app->hook('check_quotation_exists', function($quotation_id) use ($app) {
    $quotation = ORM::for_table('quotation')->find_one($quotation_id);

    if (!$quotation) {
        $app->redirect('/quotation_list');
        exit;    
    }
    return $quotation; 
});

// quotation CRUD
$app->get('/quotation_create', function() use ($app) {
    $option_keys = array(
        'company_name', 
        'company_id', 
        'company_phone',
        'company_fax',
        'company_addr',
        'company_email',
        'company_username',
        'bank_company_title',
        'bank_name',
        'bank_code',
        'bank_account',
        'quotation_id_prefix'
    );

    $options = $app->applyHook('get_options', $option_keys);

	$k=array('%year'=>date('Y'),'%month'=>date('m'),'%day'=>date('d'),'%cyear'=>date('Y')-1911);
	foreach($k as $key => $val){
		$options['quotation_id_prefix']=str_replace($key,$val,$options['quotation_id_prefix']);
	}

    $quotations = ORM::for_table('quotation')->order_by_asc('quotation_id')->find_many();

    $latest_id = 1;
    if ($quotations) {
        $last_quotation = array_pop($quotations);
        $latest_id = $last_quotation->quotation_id +1;
    }

    $data = array(
        'quotation_name'          => '報價單編號',
        'quotation_contact_name'  => '聯絡人',
        'quotation_contact_email' => '聯絡人 Email',
        'quotation_vat'           => '營業稅％',
        'quotation_email'         => '預設 Email',
        'quotation_rules'         => '條款',
        'quotation_notes'         => '備註',
        'item_desc'               => '項目及描述',
        'quantity'                => '數量',
        'price'                   => '價格',
        'breadcrumb_title'        => '建立報價單',
        'today'                   => date('Y/m/d'),
        'latest_id'               => $latest_id
    );

    $data = array_merge($data, $options);
    $app->render('quotation_create.html', $data);
});

$app->get('/quotation_edit/:id', function($id) use ($app) {
    $option_keys = array(
        'company_fax',
    );

    $options = $app->applyHook('get_options', $option_keys);
    $quotation = $app->applyHook('check_quotation_exists', $id);
    $quotation_items = unserialize($quotation->items);

    $data = array(
        'breadcrumb_title' => '編輯報價單',
        'quotation'        => $quotation,
        'quotation_items'  => $quotation_items,
    );

    $data = array_merge($data, $options);
    $app->render('quotation_edit.html', $data);
});

$app->get('/quotation_list', function() use ($app) {
    $quotations = ORM::for_table('quotation')->order_by_desc('quotation_id')->find_many();

    $quotation_status_words = array(
        'confirmed' => '訂單確認',
        'canceled'   => '取消',
        'paid'      => '已付款',
        'wait'      => '待回應'
    );

    $data = array(
        'breadcrumb_title'       => '報價單列表',
        'quotations'             => $quotations,
        'quotation_status_words' => $quotation_status_words,
    );
    $app->render('quotation_list.html', $data);
});

$app->get('/quotation_view/:id', function($id) use ($app) {
    $option_keys = array(
        'company_fax',
    );

    $options = $app->applyHook('get_options', $option_keys);
    $quotation = $app->applyHook('check_quotation_exists', $id);

    $quotation_items = unserialize($quotation->items);

    $data = array(
        'breadcrumb_title' => '查看報價單',
        'quotation'        => $quotation,
        'quotation_items'  => $quotation_items,
    );

    $data = array_merge($data, $options);
    $app->render('quotation_view.html', $data);
});

$app->get('/quotation_delete/:id', function($id) use ($app) {
    $option_keys = array(
        'company_fax',
    );

    $options = $app->applyHook('get_options', $option_keys);
    $quotation = $app->applyHook('check_quotation_exists', $id);

    $quotation_items = unserialize($quotation->items);

    $data = array(
        'breadcrumb_title' => '刪除報價單',
        'quotation'        => $quotation,
        'quotation_items'  => $quotation_items,
    );

    $data = array_merge($data, $options);
    $app->render('quotation_delete.html', $data);
});

$app->get('/quotation_download_pdf/:id', function($id) use ($app) {
    require 'lib/Wkhtmltopdf.php';

    $option_keys = array(
        'company_fax',
    );

    $options = $app->applyHook('get_options', $option_keys);
    $quotation = $app->applyHook('check_quotation_exists', $id);

    $url = $app->config('full_doc_root').'/quotation_view/'.$id;
    $html = file_get_contents($url);

    try {
        $wkhtmltopdf = new Wkhtmltopdf(array(
            'path' => PROJECT_PATH . '/pdf/', 
            'binpath'=> WKHTMLTOPDF_BIN_PATH
        ));
        $wkhtmltopdf->setTitle($quotation->quotation_name);
        $wkhtmltopdf->setHtml($html);
        $wkhtmltopdf->output(Wkhtmltopdf::MODE_DOWNLOAD, $quotation->quotation_name.'.pdf');
    } catch (Exception $e) {
        $app->notFound();
    }
});

$app->get('/quotation_status_update/:id', function($id) use ($app) {
    $quotation_status_words = array(
        'confirmed' => '訂單確認',
        'canceled'  => '取消',
        'paid'      => '已付款',
        'wait'      => '待回應'
    );

    $quotation = $app->applyHook('check_quotation_exists', $id);

    $quotation_items = unserialize($quotation->items);

    $data = array(
        'breadcrumb_title'       => '更新報價單狀態',
        'quotation'              => $quotation,
        'quotation_status_words' => $quotation_status_words,
    );

    $app->render('quotation_status_update.html', $data);
});

$app->post('/ajax_save_quotations', function() use ($app) {
    $quotation_keys = array(
        'quotation_name', 
        'company_name',
        'company_info',
        'company_contact',
        'customer_info',
        'customer_notes',
        'bank_info',
        'sub_total_price',
        'vat_price',
        'total_price',
    );

    $post = $app->request()->post();

    if ($post) {
        $wording = (isset($post['quotation_id'])) ? '更新' : '建立';

        $quotation = (isset($post['quotation_id'])) ? 
            ORM::for_table('quotation')->find_one($post['quotation_id']) : 
            ORM::for_table('quotation')->create();

        foreach ($quotation_keys as $key) {
            $quotation->$key = $post[$key];
        }

        $quotation->items = serialize($post['items']);

        $ret = $quotation->save();

        if ($ret) {
            $data = array(
                'class' => 'success',
                'msg'   => '報價單'.$wording.'成功！',
                'link'  => 'quotation_list',
                'link_msg' => '到列表查看'
            );
        } else {
            $data = array(
                'class' => 'error',
                'msg'   => '報價單'.$wording.'失敗，請重試！'
            );
        }
        $app->render('_notice.html', $data);
    }
});

$app->post('/ajax_update_quotation_status', function() use ($app) {
    $data = array(
        'class' => 'error',
        'msg'   => '報價單更新失敗，請重試！'
    );

    $post = $app->request()->post();

    if ($post && intval($post['id'])) {
        $id = $post['id'];
        $quotation = ORM::for_table('quotation')->find_one($id);
        if ($quotation) {
            $quotation->status = $post['quotation_status'];
            $ret = $quotation->save();
            if ($ret) {
                $data = array(
                    'class' => 'success',
                    'msg'   => '報價單狀態更新成功！',
                    'link'  => 'quotation_list',
                    'link_msg' => '到列表查看'
                );
            }
        }
        $app->render('_notice.html', $data);
    }
});

$app->post('/ajax_delete_quotation', function() use ($app) {
    $post = $app->request()->post();

    if ($post && (isset($post['quotation_id']))) {
        $quotation = ORM::for_table('quotation')->find_one($post['quotation_id']);
        $ret = $quotation->delete();

        if ($ret) {
            $data = array(
                'class' => 'success',
                'msg'   => '報價單刪除成功！',
                'link'  => 'quotation_list',
                'link_msg' => '到列表查看'
            );
        } else {
            $data = array(
                'class' => 'error',
                'msg'   => '報價單刪除失敗，請重試！'
            );
        }
        $app->render('_notice.html', $data);
    }
});
