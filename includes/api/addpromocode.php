<?php

use WHMCS\Database\Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly!");
}

function get_env($vars)
{
    $array = array('action' => array());

    if (isset($vars['cmd'])) {
        // Local API mode
        $array['action'] = $vars['cmd'];
        $array['params'] = (object) $vars['apivalues1'];
        $array['adminuser'] = $vars['adminuser'];
    } else {
        // Post CURL mode
        $array['action'] = $vars['action'];
        unset($vars['_POST']['username']);
        unset($vars['_POST']['password']);
        unset($vars['_POST']['action']);
        $array['params'] = (object) $vars;
    }

    return (object) $array;
}

try {
    $post_fields = get_env(get_defined_vars());

    if ($post_fields->action === 'create') {
        $command = 'AddPromo';
        $postData = array(
            'code' => $post_fields->params->code,
            'type' => $post_fields->params->type,
            'value' => $post_fields->params->value,
            'cycles' => $post_fields->params->cycles,
            'appliesto' => $post_fields->params->appliesto,
            'expirationdate' => $post_fields->params->expirationdate,
            'maxuses' => $post_fields->params->maxuses,
        );
    } elseif ($post_fields->action === 'update') {
        $command = 'UpdatePromo';
        $postData = array(
            'promotionid' => $post_fields->params->promotionid,
            'code' => $post_fields->params->code,
            'type' => $post_fields->params->type,
            'value' => $post_fields->params->value,
            'cycles' => $post_fields->params->cycles,
            'appliesto' => $post_fields->params->appliesto,
            'expirationdate' => $post_fields->params->expirationdate,
            'maxuses' => $post_fields->params->maxuses,
        );
    } else {
        throw new Exception('Invalid action. Use either "create" or "update".');
    }

    $results = localAPI($command, $postData);

    if ($results['result'] !== 'success') {
        throw new Exception('API call failed: ' . $results['message']);
    }

    $apiresults = $results;
} catch (Exception $e) {
    $apiresults = array("result" => "error", "message" => $e->getMessage());
}
