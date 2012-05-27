<?php

// customer app function
function check_customer_exists($customer_id){
    $customer = ORM::for_table('customer')->find_one($customer_id);

    if (!$customer) {
        $app->redirect('/customer_list');
        exit;    
    }
    return $customer; 
}

function save_customer_data($customer_data){
    $customer_keys = array(
        'customer_name', 
        'invoice_title', 
        'customer_uid',
        'customer_addr',
        'invoice_addr',
        'customer_phone',
        'customer_fax',
        'contact_name',
        'contact_phone',
        'contact_email',
        'customer_notes',
    );

    $customer = (isset($customer_data['customer_id'])) ? 
        ORM::for_table('customer')->find_one($customer_data['customer_id']) : 
        ORM::for_table('customer')->create();

    if (isset($customer_data['customer_id']) && !$customer) {
        return False;
    }

    foreach($customer_keys as $key) {
        $customer->$key = $customer_data[$key];
    }

    $ret = $customer->save();

    return $ret;
}

// customer CRUD
$app->get('/customer_create', function() use ($app) {
    $data = array(
        'breadcrumb_title'       => '建立客戶',
    );
    $app->render('customer_create.html', $data);
});

$app->get('/customer_edit/:id', function($id) use ($app) {
    $customer = check_customer_exists($id);
    $data = array(
        'breadcrumb_title' => '更新客戶資料',
        'customer'         => $customer,
    );
    $app->render('customer_edit.html', $data);
});

$app->get('/customer_list', function() use ($app) {
    $customers = ORM::for_table('customer')->order_by_desc('customer_id')->find_many();

    $data = array(
        'breadcrumb_title' => '客戶列表',
        'customers'        => $customers,
    );
    $app->render('customer_list.html', $data);
});

$app->get('/customer_delete/:id', function($id) use ($app) {
    $customer = check_customer_exists($id);
    $data = array(
        'breadcrumb_title' => '刪除客戶資料',
        'customer'         => $customer,
    );
    $app->render('customer_delete.html', $data);
});

$app->get('/modal_customer_list', function() use ($app) {
    $customers = ORM::for_table('customer')->order_by_desc('customer_id')->find_many();

    $data = array(
        'breadcrumb_title' => '客戶資料',
        'customers'        => $customers,
    );
    $app->render('modal_customer_list.html', $data);
});

$app->post('/ajax_save_customer', function() use ($app) {
    $post = $app->request()->post();

    if ($post) {
        $wording = (isset($post['customer_id'])) ? '更新' : '建立';
        $ret = save_customer_data($post);
        if ($ret) {
            $data = array(
                'class' => 'success',
                'msg'   => '客戶資料'.$wording.'成功！',
                'link'  => 'customer_list',
                'link_msg' => '到列表查看'
            );
        } else {
            $data = array(
                'class' => 'error',
                'msg'   => '客戶資料'.$wording.'失敗，請重試！'
            );
        }
        $app->render('_notice.html', $data);
    }
});

$app->post('/ajax_delete_customer', function() use ($app) {
    $post = $app->request()->post();

    if ($post && (isset($post['customer_id']))) {
        $customer = ORM::for_table('customer')->find_one($post['customer_id']);

        if($customer!==false){
		    $ret = $customer->delete();
        }else{
            $ret=false;
        }

        if ($ret) {
            $data = array(
                'class' => 'success',
                'msg'   => '客戶資料刪除成功！',
                'link'  => 'customer_list',
                'link_msg' => '到列表查看'
            );
        } else {
            $data = array(
                'class' => 'error',
                'msg'   => '客戶資料刪除失敗，請重試！'
            );
        }
        $app->render('_notice.html', $data);
    }
});

$app->get('/ajax_get_customer_data/:id', function($id) use ($app) {
    $customer = ORM::for_table('customer')->find_one($id);
    if ($customer) {
        $data = array('customer' => $customer);
        $app->render('ajax_get_customer_data.html', $data);
    } else {
        echo '';
    }
});
