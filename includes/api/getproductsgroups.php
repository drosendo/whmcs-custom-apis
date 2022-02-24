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
    }
    return (object) $array;
}

try {

    $post_fields = get_env(get_defined_vars());

    $query = Capsule::table('tblproductgroups')->select('id', 'name')->where('hidden', false);

    if (isset($post_fields->gid) && $post_fields->gid > 0)
        $query->where('id', $post_fields->gid);

    $results = $query->get();

    if (empty($results)) {
        throw new Exception('No groups found');
    }

    $apiresults = array("result" => "success", "groups" => $results);
} catch (Exception $e) {
    $apiresults = array("result" => "error", "message" => $e->getMessage());
}
