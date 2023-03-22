<?php
$token = "FYMHY9xJ7RiNAnp6wsK2i4VfXiunmSATFCUdAzjnXknQw5PxG8";
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.ruangwa.id/api/device',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'token='.$token,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);
echo $response."<br>";
curl_close($curl);
$data = json_decode($response,true);
echo $data['message'];
?>