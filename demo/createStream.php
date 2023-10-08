<?php

$requestBody = file_get_contents('php://input');
$requestBody = json_decode($requestBody, true);
$prompt = $requestBody['prompt'];
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.chatuapi.com/chat/stream/create',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "accessToken": "your access token",
    "prompt": "'.$prompt.'"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($curl);

if(curl_errno($curl)){
    $error_message = curl_error($curl);
    error_log($error_message."error1");
}
curl_close($curl);
$result = json_decode($response, true);
if ($result && isset($result['data']['streamId'])) {
    $streamId = $result['data']['streamId'];
    echo $streamId;
} else {
}

