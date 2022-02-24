<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be access directly!");
}

function get_env($vars)
{
    $array = array('action' => array(), 'gid' => array());

    if (isset($vars['cmd'])) {
        //Local API mode
        $array['action'] = $vars['cmd'];
        $array['params'] = (object) $vars['apivalues1'];
        $array['adminuser'] = $vars['adminuser'];
    } else {
        //Post CURL mode
        $array['action'] = $vars['action'];
        unset($vars['_POST']['username']);
        unset($vars['_POST']['password']);
        unset($vars['_POST']['action']);
        $array['gid'] = $vars['gid'];
        $array['pid'] = $vars['pid'];
    }
    return (object) $array;
}

try {

    $post_fields = get_env(get_defined_vars());

    $command = 'GetProducts';

    $postData = array(
        'gid' => $post_fields->gid,
        'pid' => $post_fields->pid
    );

    $results = localAPI($command, $postData);

    $query = Capsule::table('tblproducts')->select('id')->where('hidden', true);

    if ($post_fields->gid)
        $query->where('gid', $post_fields->gid);

    if ($post_fields->pid)
        $query->where('id', $post_fields->pid);

    $qr = $query->get();


    foreach ($results['products']['product'] as $key => $value) {
        foreach ($qr as $k => $v) {
            if ($value['pid'] == $v->id) {
                $results['totalresults'] = $results['totalresults'] - 1;
                unset($results['products']['product'][$key]);
            }
        }
    }

    $products = array_values($results['products']['product']);
    $results['products'] = $products;
    if (empty($results)) {
        throw new Exception('No products found');
    }

    $apiresults = $results;
} catch (Exception $e) {
    $apiresults = array("result" => "error", "message" => $e->getMessage());
}
