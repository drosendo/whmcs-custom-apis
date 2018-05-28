Currently WHMCS API GetProducts (<a href="https://developers.whmcs.com/api-reference/getproducts/" target="_blank" rel="external nofollow noopener">https://developers.whmcs.com/api-reference/getproducts/</a>) retrieves all products and no information regarding if the product is active or not.

So I just created my own API call to handle this and only retrieve the visible (not hidden) products.

Just upload to /includes/api .
<h3>Request Parameters "GetProductsActive"</h3>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Description</th>
<th>Required</th>
</tr>
</thead>
<tbody>
<tr>
<td>action</td>
<td>string</td>
<td>“GetProductsActive”</td>
<td>Required</td>
</tr>
<tr>
<td>pid</td>
<td>int</td>
<td>string</td>
<td>Obtain a specific product id configuration. Can be a list of ids comma separated</td>
</tr>
<tr>
<td>gid</td>
<td>int</td>
<td>Retrieve products in a specific group id</td>
<td>Optional</td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
</tbody>
</table>
<h3>Response Parameters</h3>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td>result</td>
<td>string</td>
<td>The result of the operation: success or error</td>
</tr>
<tr>
<td>totalresults</td>
<td>int</td>
<td>The total number of results available</td>
</tr>
<tr>
<td>startnumber</td>
<td>int</td>
<td>The starting number for the returned results</td>
</tr>
<tr>
<td>numreturned</td>
<td>int</td>
<td>The number of results returned</td>
</tr>
<tr>
<td>products</td>
<td>array</td>
<td>An array of products matching the criteria passed</td>
</tr>
</tbody>
</table>
<h3>Example Request (CURL)</h3>
<pre class="ipsCode prettyprint lang-html prettyprinted"><span class="pln">$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.example.com/includes/api.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    http_build_query(
        array(
            'action' =&gt; 'GetProductsActive',
            // See https://developers.whmcs.com/api/authentication
            'username' =&gt; 'IDENTIFIER_OR_ADMIN_USERNAME',
            'password' =&gt; 'SECRET_OR_HASHED_PASSWORD',
            'pid' =&gt; '1',
            'responsetype' =&gt; 'json',
        )
    )
);
$response = curl_exec($ch);
curl_close($ch);</span></pre>
&nbsp;
<h3>Example Request (Local API)</h3>
<pre class="ipsCode prettyprint lang-html prettyprinted"><span class="pln">$command = 'GetProductsActive';
$postData = array(
    'pid' =&gt; '1', // or gid =&gt; '1' or both
);
$adminUsername = 'ADMIN_USERNAME'; // Optional for WHMCS 7.2 and later

$results = localAPI($command, $postData, $adminUsername);
print_r($results);</span></pre>
&nbsp;

Have a better code or add something? Please do!

You can download the code here: <a href="https://www.rosendo.pt/wp-content/uploads/2017/10/getproductsactive.zip">WHMCS API getproductsactive</a>

Cheers,

David
