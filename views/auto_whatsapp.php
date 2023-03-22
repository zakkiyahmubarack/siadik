<?php
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
include"../assets/config/koneksi.php";
include"../assets/config/function.php";
include "../assets/config/tgl_indo.php";
$iden=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_identitas order by id_identitas DESC"));
$kode_iden = explode(";",$iden['kode_identitas']);
$cekData=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(status_pesan) AS jml FROM pesan WHERE status_pesan='N' AND tgl_pesan LIKE '".date("Y-m-d")."%'"));
if($cekData['jml'] > 0){
    $query=mysqli_query($koneksi,"SELECT * FROM pesan WHERE status_pesan='N' AND tgl_pesan LIKE '".date("Y-m-d")."%'");
    while($data=mysqli_fetch_assoc($query)){
        $api_key   = $kode_iden[0]; // API KEY Anda
        $id_device = $kode_iden[1];
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
        'api-key' => $api_key,
        'no_hp'   => $no_hp,
        'pesan'   => $pesan
        ];
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_post));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($curl);
        $hasil = json_decode($response, true);
        curl_close($curl);
        if($hasil['kode'] == "200"){
            mysqli_query($koneksi,"UPDATE pesan SET status_pesan='Y' WHERE id_pesan='".$data['id_pesan']."'");
        }
        
    }
}
							//echo $response;
							//mysqli_query($koneksi,"INSERT into outbox (DestinationNumber,TextDecoded,CreatorID) VALUES ('$cek_kontak[no_phone]', '$pesan','Gammu')"); 
					

?>