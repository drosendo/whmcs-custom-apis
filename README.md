# WHMCS CUSTOM API's Solutions

Currently WHMCS API is very limited and doesnt provide a full experience to comunicate with it.



This project consists of sharing custom API solutions:

### Currently Available:
  - GetProductsActive
  - AddCreditApi
  - GetProductsGroups
  - PaymentData
  - AddPromoCode

### Register API's in WHMCS system to be used in Role Management

Add the following hook file in /includes/hooks/ directory. Make your changes to fit your group needs.

##### GetProductsActive

Currently WHMCS API GetProducts [api-reference/getproducts](https://developers.whmcs.com/api-reference/getproducts/) retrieves all products and no information regarding if the product is active or not.

So I just created my own API call to handle this and only retrieve the visible (not hidden) products.

Just upload to /includes/api .

##### Request Parameters "GetProductsActive" 

| Parameter | Type | Description | Required |
| ------ | ------ | ------ | ------ |
| action | string | “GetProductsActive” | Required |
| pid | int | Obtain a specific product id configuration. Can be a list of ids comma separated | optional |
| gid | int | Retrieve products in a specific group id | optional |

##### Response Parameters

| Parameter | Type | Description 
| ------ | ------ | ------ |
| result | string | The result of the operation: success or error |
| totalresults | int | The total number of results available | 
| startnumber | int | The starting number for the returned results |
| numreturned | int | The number of results returned |
| products | array | An array of products matching the criteria passed | 

### Example Request (Local API)

```php
$command = 'GetProductsActive';
$postData = array(
    'pid' => '1', // or gid => '1' or both
);
$adminUsername = 'ADMIN_USERNAME'; // Optional for WHMCS 7.2 and later

$results = localAPI($command, $postData, $adminUsername);
print_r($results);
```

### Example Request (CURL)

```php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.example.com/includes/api.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    http_build_query(
        array(
            'action' => 'GetProductsActive',
            // See https://developers.whmcs.com/api/authentication
            'username' => 'IDENTIFIER_OR_ADMIN_USERNAME',
            'password' => 'SECRET_OR_HASHED_PASSWORD',
            'pid' => '1',
            'responsetype' => 'json',
        )
    )
);
$response = curl_exec($ch);
curl_close($ch);
```

#### Example output:
```json
{
    "result": "success",
    "totalresults": 1,
    "products": {
        "product": [
            {
                "pid": "123",
                "gid": "",
                "type": "",
                "name": "XPTO",
                "description": "",
                "module": "cpanel",
                "paytype": "recurring",
                "pricing": {
                    "EUR": {
                        "prefix": "",
                        "suffix": "€",
                        "msetupfee": "0.00",
                        "qsetupfee": "0.00",
                        "ssetupfee": "0.00",
                        "asetupfee": "0.00",
                        "bsetupfee": "0.00",
                        "tsetupfee": "0.00",
                        "monthly": "-1.00",
                        "quarterly": "-1.00",
                        "semiannually": "-1.00",
                        "annually": "40.00",
                        "biennially": "76.00",
                        "triennially": "-1.00"
                    }
                },
                "customfields": {
                    "customfield": []
                },
                "configoptions": {
                    "configoption": [{}]
                }
            }
        }
    }
```

##### AddCreditApi

Currently WHMCS API AddCredit [api-reference/addcredit](https://developers.whmcs.com/api-reference/addcredit/) allows to add credit to a given client.

I needed an API request that allowed me to charge a client for credit and after successful payment credit be added automatically.

Just upload to /includes/api .

##### Request Parameters "AddCreditApi" 

| Parameter | Type | Description | Required |
| ------ | ------ | ------ | ------ |
| action | string | “AddCreditApi” | Required |
| userid | int | The userid of client to make credit request | required |
| itemamount | float | The amount of credit to add or remove. Must be a positive value. | required |
| paymentmethod | string | The payment method to charge with | required

##### Response Parameters

| Parameter | Type | Description 
| ------ | ------ | ------ |
| result | string | The result of the operation: success or error |
| invoiceid | int | invoice Number | 
| status | string | The status os the invoice |
| paymentMethod | array | Returns the payment information |

### Example Request (Local API)

```php
$command = 'AddCreditApi';
$postData = array(
    'userid' => '1',
    'itemamount' => '50',
    'paymentmethod' => 'paypal',
);
$adminUsername = 'ADMIN_USERNAME'; // Optional for WHMCS 7.2 and later

$results = localAPI($command, $postData, $adminUsername);
print_r($results);
```

### Example Request (CURL)

```php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.example.com/includes/api.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    http_build_query(
        array(
            'action' => 'AddCreditApi',
            // See https://developers.whmcs.com/api/authentication
            'username' => 'IDENTIFIER_OR_ADMIN_USERNAME',
            'password' => 'SECRET_OR_HASHED_PASSWORD',
            'userid' => '1',
            'itemamount' => '50',
            'paymentmethod' => 'paypal',
            'responsetype' => 'json',
        )
    )
);
$response = curl_exec($ch);
curl_close($ch);
```

#### Example output:
```json
{
    "result": "success",
    "invoiceid": 129,
    "status": "Unpaid",
    "paymentMethod": {
        "amount": "52",
        "data": "Bank: XPTO Bank\r\n"
    }
}
```

##### GetProductsGroups

Currently WHMCS API has no method to retrieve your current product groups.

I needed an API request that allowed me to retrive the products groups I have ACTIVE in WHMCS or just a specific one for some future porpuse.

Just upload to /includes/api .

##### Request Parameters "GetProductsGroups" 

| Parameter | Type | Description | Required |
| ------ | ------ | ------ | ------ |
| action | string | “GetProductsGroups” | Required |
| gid | int | The group id to get information of | optional |

##### Response Parameters

| Parameter | Type | Description 
| ------ | ------ | ------ |
| result | string | The result of the operation: success or error |
| groups | array | Returns the groups information |

### Example Request (Local API)

```php
$command = 'GetProductsGroups';
$postData = array(
    'gid' => '1', // optional data
);
$adminUsername = 'ADMIN_USERNAME'; // Optional for WHMCS 7.2 and later

$results = localAPI($command, $postData, $adminUsername);
print_r($results);
```

### Example Request (CURL)

```php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.example.com/includes/api.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    http_build_query(
        array(
            'action' => 'GetProductsGroups',
            // See https://developers.whmcs.com/api/authentication
            'username' => 'IDENTIFIER_OR_ADMIN_USERNAME',
            'password' => 'SECRET_OR_HASHED_PASSWORD',
            'gid' => '1', // Optional
            'responsetype' => 'json',
        )
    )
);
$response = curl_exec($ch);
curl_close($ch);
```

#### Example output:
```json
{
    "result": "success",
    "groups": [
        {
            "id": "1",
            "name": "Web Hosting - Linux"
        }
    ]
}
```

##### PaymentData

Currently WHMCS API GetInvoice [api-reference/getinvoice](https://developers.whmcs.com/api-reference/getinvoice/) retrieves a specific invoice.

I needed a solution to retrieve the payment information from it

Just upload to /includes/api .

##### Request Parameters "PaymentData" 

| Parameter | Type | Description | Required |
| ------ | ------ | ------ | ------ |
| action | string | “GetProductsActive” | Required |
| invoiceid | int | The ID of the invoice to retrieve | Required |

##### Response Parameters

| Parameter | Type | Description 
| ------ | ------ | ------ |
| result | string | The result of the operation: success or error |
| products | array | An array of invoice data and payment method | 

### Example Request (Local API)

```php
$command = 'PaymentData';
$postData = array(
    'invoiceid' => '66', 
);
$adminUsername = 'ADMIN_USERNAME'; // Optional for WHMCS 7.2 and later

$results = localAPI($command, $postData, $adminUsername);
print_r($results);
```

### Example Request (CURL)

```php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.example.com/includes/api.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    http_build_query(
        array(
            'action' => 'PaymentData',
            // See https://developers.whmcs.com/api/authentication
            'username' => 'IDENTIFIER_OR_ADMIN_USERNAME',
            'password' => 'SECRET_OR_HASHED_PASSWORD',
            'invoiceid' => '66',
            'responsetype' => 'json',
        )
    )
);
$response = curl_exec($ch);
curl_close($ch);
```

#### Example output:
```json
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
```

##### Request Parameters "addPromoCode"

| Parameter      | Type   | Description                                               | Required |
| -------------- | ------ | --------------------------------------------------------- | -------- |
| action         | string | Either "create" (to create a new promotion) or "update" (to update an existing promotion) | Required |
| code           | string | The promotion code                                        | Required |
| type           | string | The discount type, either 'percentage' or 'fixed'        | Required |
| value          | float  | The discount value                                        | Required |
| cycles         | string | The billing cycles to which the promo code applies (e.g., 'Monthly') | Optional |
| appliesto      | string | A comma-separated list of product IDs the promotion applies to | Optional |
| expirationdate | string | The promotion code expiration date in the format 'YYYY-MM-DD' | Optional |
| maxuses        | int    | The maximum number of times the promo code can be used   | Optional |
| promotionid    | int    | The ID of the promotion to update (only required for 'update' action) | Conditional |

##### Response Parameters

| Parameter  | Type   | Description                             |
| ---------- | ------ | --------------------------------------- |
| result     | string | The result of the operation: success or error |
| message    | string | A message describing the result of the API call |
| promotionid | int    | The ID of the created or updated promotion |

### Example Request (Local API)

```php
$command = 'addPromoCode';
$postData = array(
    'action' => 'create',
    'code' => 'PROMO10',
    'type' => 'percentage',
    'value' => '10',
    'cycles' => 'Monthly',
    'appliesto' => '1,2,3',
    'expirationdate' => '2023-12-31',
    'maxuses' => '50',
);
$adminUsername = 'ADMIN_USERNAME'; // Optional for WHMCS 7.2 and later

$results = localAPI($command, $postData, $adminUsername);
print_r($results);
```

#### Example JSON Output for 'create' action:

```json
{
  "result": "success",
  "message": "Promotion code created successfully",
  "promotionid": 1234
}

{
  "result": "success",
  "message": "Promotion code updated successfully",
  "promotionid": 1234
}
```

### Todos

 - Share MORE API's
 - Wait for feedback and other developers shares

License
----

MIT


**Free Software, Hell Yeah!**
