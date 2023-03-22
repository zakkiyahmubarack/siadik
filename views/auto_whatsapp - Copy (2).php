<?php
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
include"../assets/config/koneksi.php";
include"../assets/config/function.php";
include "../assets/config/tgl_indo.php";
$iden=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_identitas order by id_identitas DESC"));
$kode_iden = explode(";",$iden['kode_identitas']);
$cekData=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(status_pesan) AS jml FROM pesan WHERE status_pesan='N'"));
if($cekData['jml'] > 0){
    $query=mysqli_query($koneksi,"SELECT * FROM pesan WHERE status_pesan='N'");
    while($data=mysqli_fetch_assoc($query)){
        $api_key   = "641FKTYF7Z7EL9S0X9LQ"; // API KEY Anda
        $id_device = $kode_iden[1];
        $url   = 'https://api.watsap.id/send-message'; // URL API
        $no_hp = '6287744421420'; // No.HP yang dikirim (No.HP Penerima)
		
        $pesan = $data['isi_pesan']; // Pesan yang dikirim

        $curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://panel.rapiwha.com/send_message.php?apikey=".$api_key."&number=".$no_hp."&text=".$pesan."",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
       // $response = curl_exec($curl);
        //$hasil = json_decode($response, true);
        curl_close($curl);
        //if($hasil['kode'] == "200"){
            mysqli_query($koneksi,"UPDATE pesan SET status_pesan='Y' WHERE id_pesan='".$data['id_pesan']."'");
       // }
        
    }
}
							//echo $response;
							//mysqli_query($koneksi,"INSERT into outbox (DestinationNumber,TextDecoded,CreatorID) VALUES ('$cek_kontak[no_phone]', '$pesan','Gammu')"); 
					

?>