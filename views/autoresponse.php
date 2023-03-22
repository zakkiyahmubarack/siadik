<?php
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
include"../assets/config/koneksi.php";
include"../assets/config/function.php";
include "../assets/config/tgl_indo.php";
$statuss=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM phones order by UpdatedInDB DESC LIMIT 1"));
$status = get_modem_status($statuss['UpdatedInDB'], 1);
//
$iden=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_identitas order by id_identitas DESC"));
$h2=mysqli_query($koneksi,"select * from tbl_perangkat WHERE status_perangkat='1' order by nama_perangkat ASC");
while($d2=mysqli_fetch_assoc($h2)){
	//cek koneksi perangkat
	$Connect = fsockopen($d2['ip_perangkat'], '80', $errno, $errstr, 1);
	if($Connect){
		$soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
		$newLine="\r\n";
		fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
	    fputs($Connect, "Content-Type: text/xml".$newLine);
	    fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
	    fputs($Connect, $soap_request.$newLine);
		$buffer="";
		while($Response=fgets($Connect, 1024)){
			$buffer=$buffer.$Response;
		}
	}else {
		echo $d2['nama_perangkat']." Tidak terkoneksi<br>";
	}
	// end cek koneksi perangkat
	//get data mesin
	$buffer=Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
	$buffer=explode("\r\n",$buffer);
	for($a=0;$a<count($buffer);$a++){
		$data=Parse_Data($buffer[$a],"<Row>","</Row>");
		$PIN=Parse_Data($data,"<PIN>","</PIN>");
		$DateTime=Parse_Data($data,"<DateTime>","</DateTime>");
		$Verified=Parse_Data($data,"<Verified>","</Verified>");
		$Status=Parse_Data($data,"<Status>","</Status>");
		$waktu=explode(" ", $DateTime);
		$waktu_sekarang=date("H:i:s");
		//cek kesediaan jadwal
		$bc_jadwal=mysqli_query($koneksi,"SELECT * FROM tbl_jadwal WHERE mulai_jadwal <='$waktu[1]' and toleransi_jadwal >= '$waktu[1]'");
		$in=mysqli_num_rows($bc_jadwal);
		//echo $waktu[1]." ";
	//jika jadwal tersedia
	if($in == "1"){
		$jdwl=mysqli_fetch_assoc($bc_jadwal);
		$kode_jadwal=$jdwl['kode_jadwal'];
		$cek_user=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM tbl_user where kode_user='$PIN'"));
		if($cek_user > 0){
			$baca_absen=mysqli_query($koneksi,"select * from absen where kode_user='$PIN' and status_absen='$kode_jadwal' and tgl_absen='$waktu[0]' ");
			$fo=mysqli_num_rows($baca_absen);
			if($fo < 1){
				if($jdwl['kode_jadwal'] == "0"){
					if(date($waktu[1]) > date($jdwl['berhenti_jadwal'])){
						$selisih=datediff("$jdwl[berhenti_jadwal]", "$waktu[1]");
						if($selisih > 0){$jum_menit = $selisih['minutes_total'];}
						else{
							$jum_menit = "0";
						}
					}
						else{$jum_menit = "0";}
					}else{$jum_menit = "0";}
				
				
				//echo "$PIN $jdwl[id_jadwal] ok | ";
				mysqli_query($koneksi,"INSERT INTO absen (kode_user,tgl_absen,jam_absen,waktu_absen,verified_absen,status_absen,telat_absen) 
						VALUES ('$PIN','$waktu[0]','$waktu[1]','$DateTime','$Verified','$kode_jadwal','$jum_menit')");
				$dat=mysqli_fetch_array(mysqli_query($koneksi,"select * from tbl_user where kode_user='$PIN'"));

				//if($status == "connect") {
					$pesan ="SIADIK -> [ABSEN ".$jdwl['nama_jadwal']."], $dat[nama_user] pada tanggal ".tgl_indo($waktu[0])." pukul $waktu[1]. #$iden[nama_identitas]#";
					$cek_kontak=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_phone where kode_user='$PIN'"));
					if(!empty($cek_kontak['no_phone'])){
						if($cek_kontak['no_phone'] != "-" ){
							/*
							$token = "FYMHY9xJ7RiNAnp6wsK2i4VfXiunmSATFCUdAzjnXknQw5PxG8";

							$phone= $cek_kontak['no_phone']; //untuk group pakai groupid contoh: 62812xxxxxx-xxxxx
							
							
							$curl = curl_init();
							curl_setopt_array($curl, array(
							  CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => '',
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => 'POST',
							  CURLOPT_POSTFIELDS => 'token='.$token.'&number='.$phone.'&message='.$pesan,
							));
							$response = curl_exec($curl);
							curl_close($curl);
							$data = json_decode($response,true);
							*/
							mysqli_query($koneksi,"INSERT into pesan (tgl_pesan,nomor_pesan,isi_pesan) VALUES (now(),'$cek_kontak[no_phone]', '$pesan')"); 
						}
					}
				//}
				mysqli_query($koneksi,"delete from absen where kode_user=''");
			}
		}		
	}
	// end jika jadwal tersedia
		
  	}
	/*
  	if($status == "connect") {
  	$cekLog=mysqli_query($koneksi,"SELECT * FROM log a left join tbl_perangkat b on a.id_perangkat=b.id_perangkat 
								where a.tgl_log='".date("Y-m-d")."' and a.id_perangkat='$d2[id_perangkat]'");
		if(mysqli_num_rows($cekLog) < 1){
			mysqli_query($koneksi,"INSERT into outbox (DestinationNumber,TextDecoded,CreatorID) VALUES ('+6287744421420', 'SIADIK->$iden[nama_identitas] $d2[nama_perangkat]','Gammu')");
			$cekPerangkat=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM log where id_perangkat='$d2[id_perangkat]'"));
			if($cekPerangkat < 1){mysqli_query($koneksi,"INSERT into log (tgl_log, id_perangkat) values ('".date("Y-m-d")."','$d2[id_perangkat]')");}
			else{mysqli_query($koneksi,"UPDATE log SET tgl_log='".date("Y-m-d")."' where id_perangkat='$d2[id_perangkat]'");}
		}
	}
	*/
 }
//}

?>