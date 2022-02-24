<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be access directly!");
}

function get_env($vars)
{
    $array = array('action' => array(), 'params' => array());
    if (isset($vars['cmd'])) {
        //Local API mode
        $array['action'] = $vars['cmd'];
        $array['params'] = $vars['apivalues1'];
        $array['adminuser'] = $vars['adminuser'];
        $array['invoiceid'] = $vars['invoiceid'];
    } else {
        //Post CURL mode
        $array['action'] = $vars['action'];
        unset($vars['_POST']['username']);
        unset($vars['_POST']['password']);
        unset($vars['_POST']['action']);
        $array['invoiceid'] = $vars['invoiceid'];
    }
    return $array;
}

try {
    $params = get_env(get_defined_vars());


    $command = 'GetInvoice';
    $postData = array(
        'invoiceid' => $params['invoiceid']
    );

    $results = localAPI($command, $postData);

    $amount = $results['total'];
    $paymentmethod = $results['paymentmethod'];
    $invoiceid = $params['invoiceid'];

    $paymentdata = array(
        'order' => $results,
        'amount' => $amount,
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


    $apiresults = array("result" => "success", "paymentdata" => $paymentdata);
} catch (Exception $e) {
    $apiresults = array("result" => "error", "message" => $e->getMessage());
}

{
    "result": "success",
    "paymentdata": {
        "order": {
            "result": "success",
            "invoiceid": "66",
            "invoicenum": "",
            "userid": "1",
            "date": "2019-07-16",
            "duedate": "2019-07-16",
            "datepaid": "0000-00-00 00:00:00",
            "lastcaptureattempt": "0000-00-00 00:00:00",
            "subtotal": "11.99",
            "credit": "0.00",
            "tax": "2.76",
            "tax2": "0.00",
            "total": "14.75",
            "balance": "14.75",
            "taxrate": "23.00",
            "taxrate2": "0.00",
            "status": "Unpaid",
            "paymentmethod": "banktransfer",
            "notes": "",
            "ccgateway": false,
            "items": {
                "item": [
                    {
                        "id": "121",
                        "type": "DomainRegister",
                        "relid": "37",
                        "description": "Register Domain - samplesthis.com - 1 year) (16/07/2019 - 15/07/2020)\n",
                        "amount": "14.75",
                        "taxed": "1"
                    }
                ]
            },
            "transactions": ""
        },
        "amount": "14.75",
        "data": "Bank XPTO"
    }
}
