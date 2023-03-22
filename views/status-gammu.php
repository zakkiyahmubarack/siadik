<?php
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
include "../assets/config/koneksi.php";
$iden=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_identitas order by id_identitas DESC"));
$kode_iden = explode(";",$iden['kode_identitas']);
$token = $kode_iden[0];
        $api_key   = $token; // API KEY Anda
        $id_device = $kode_iden[1]; // ID DEVICE yang di SCAN (Sebagai pengirim)
        $url   = 'https://api.watsap.id/send-message'; // URL API
        $no_hp = $data['nomor_pesan']; // No.HP yang dikirim (No.HP Penerima)
        $pesan = $data['isi_pesan']; // Pesan yang dikirim

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0); // batas waktu response
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, 1);

        $data_post = [
        'id_device' => $id_device,
        'api-key' => $api_key
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_post));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($curl);
        $status = json_decode($response, true);
        curl_close($curl);
//echo $response;
if($status['kode'] <> "") {
	
	if($status['kode'] == "404") {
		echo "<span class=\"label bg-green\"><span class='glyphicon glyphicon-phone'></span> Tersambung</span>";
	}
	else {
		echo "<span class=\"label bg-yellow\"><span class='glyphicon glyphicon-phone'></span> Terputus</span>";
	}
	//echo " | <span class=\"glyphicon glyphicon-signal\"></span> $data[batteryLevel]%";
	//echo " | <i class=\"fa fa-battery-full\" aria-hidden=\"true\"></i> $data[batteryLevel]%";
}
else {
	echo "<span class=\"label bg-red\"><span class='glyphicon glyphicon-phone'></span> Unknown</span>";
}
curl_close($curl);
?>