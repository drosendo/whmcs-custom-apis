<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be access directly!");
}

function get_env($vars)
{
    $array = array();

    if (isset($vars['cmd'])) {
        //Local API mode
        $array['action'] = $vars['cmd'];
        $array['params'] = (object) $vars['apivalues1'];
        $array['adminuser'] = $vars['adminuser'];
    } else {
        //Post CURL mode
        $array['action'] = $vars['action'];
        unset($vars['_POSTbackup']['username']);
        unset($vars['_POSTbackup']['password']);
        unset($vars['_POSTbackup']['action']);
        $array['userid'] = $vars['userid'];
        $array['itemamount'] = $vars['itemamount'];
        $array['paymentmethod'] = $vars['paymentmethod'];
    }
    return (object) $array;
}

try {

    $postFields = get_env(get_defined_vars());

    $command = 'CreateInvoice';
    $postData = array(
        'userid' => $postFields->userid,
        //'status' => 'Unpaid',
        'sendinvoice' => '1',
        'paymentmethod' => $postFields->paymentmethod,
        'notes' => 'Credit request via API',
        'itemdescription1' => 'Add Credtit', // OR other custom message
        'itemamount1' => $postFields->itemamount, // Total amount to be charged
    );

    $results = localAPI($command, $postData);

    //Necessary to add "AddFunds" to table row so that WHMCS process payment accordingly and adds the amount to credit
    $update_result = Capsule::table('tblinvoiceitems')
        ->where('invoiceid', $results['invoiceid'])
        ->update(
            [
                'type' => 'AddFunds',
            ]
        );

    $ds_getPaymentData = array(
        'invoiceid' => $results['invoiceid'],
        'paymentmethod' => $postFields->paymentmethod,
        'amount' => $postFields->itemamount
    );

    $results['paymentMethod'] = ds_getPaymentData($ds_getPaymentData); //Returns the Payment data information 


    $apiresults = $results;
} catch (Exception $e) {
    $apiresults = array("result" => "error", "message" => $e->getMessage());
}


function ds_getPaymentData($data)
{

    $paymentmethod = $data['paymentmethod'];
    $invoiceid = $data['invoiceid'];

    $paymentdata = array(
        'amount' => $data['amount'],
    );

    $url = Capsule::table('tblconfiguration')
        ->where('setting', 'SystemURL')
        ->first();

    //Customize your payment gateway information for retrieval
    switch ($paymentmethod) {
        case 'stripe':

            $paymentdata['data'] = '';
            break;
        case 'paypal':
            $paymentdata['data'] = $url->value . 'viewinvoice.php?id=' . $invoiceid;
            break;
        default:
            $query = Capsule::table('tblpaymentgateways')->select('value')->where('setting', 'instructions')->where('gateway', $paymentmethod);
            $results = $query->get();

            $paymentdata['data'] = $results[0]->value;

            break;
    }

    return $paymentdata;
}
