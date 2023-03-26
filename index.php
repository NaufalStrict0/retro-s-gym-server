<?php 
$server_key = "SB-Mid-server-721QBQObWJy14qltiXuwSH98";

$is_production = false;
$api_url = $is_production ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transcations';

if($_SERVER(['REQUEST_URI'], '/charge' )){
    http_response_code(404);
    echo "wrong path, make sure it's  /charge"; exit();   
}
if($_SERVER['REQUEST_METHOD']!== 'POST'){
    http_response_code(404);
    echo "Page not found or wronhg http request method is used"; exit();
}
$request_body = file_get_contents('php://input');
header('Contents-Type: application/json');

$charge_result = chargeAPi($api_url, $server_key, $request_body);
 http_response_code($charge_result['http_code']);
 echo $charge_result['body'];
function chargeApi($api_url, $server_key, $request_body){
    $ch = curl_init();
    $curl_options = array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        //tambahkan header ke permintaan, termasuk otorisasi yang dihasilkan dari kunci server
        CURLOPT_HTTPHEADER => array(
            'Conntent-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic' . base64_encode($server_key. ':')
        ),
        CURLOPT_POSTFIELDS => $request_body
    );
    curl_setopt_array($ch, $curl_options);
    $result = array(
        'body' => curl_exec($ch),
        'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
    );
    return $result;
}
