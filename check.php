<?php
/**
 * Created by PhpStorm.
 * User: farhad-mbp
 * Date: 2/3/18
 * Time: 9:34 PM
 */
$curl = curl_init();

define("RefreshToken", "__REFRESH_TOKEN");

parse_str($_SERVER['QUERY_STRING']);

// Get Access code

$fields = array(
    grant_type => "refresh_token",
    client_id => "__CLIENT_ID",
    client_secret => "__CLIENT_SECRET",
    refresh_token => constant("RefreshToken")
);

foreach ($fields as $key => $value) {
    $fields_string .= $key . '=' . $value . '&';
}
rtrim($fields_string, '&');

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://pardakht.cafebazaar.ir/devapi/v2/auth/token/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $fields_string,
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $all = json_decode($response, true);
    $access_token = $all["access_token"];
}


// Now Validate billing
$url = 'https://pardakht.cafebazaar.ir/devapi/v2/api/validate/' . $package . '/inapp/' . $proid . '/purchases/' . $putoken . '?access_token=' . $access_token;
$curl2 = curl_init();
curl_setopt_array($curl2, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
));

$result = curl_exec($curl2);
//$finalerr = curl_error($curl);

curl_close($curl2);
// Get HTTP Status code from the response
$status_code = array();
preg_match('/\d\d\d/', $result, $status_code);

switch ($status_code[0]) {
    case 301:
        var_dump($result);
        break;
}

