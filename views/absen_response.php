<?php
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
include "../assets/config/koneksi.php";
include "../assets/config/function.php";
$iden=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from identitas order by id_identitas DESC"));
$inbox=mysqli_query($koneksi,"select * from inbox where Processed='false'");
while($d_absen=mysqli_fetch_assoc($inbox)){
	$kode=strtoupper($d_absen['TextDecoded']);
	$pecahan=explode(" ",$kode);
	if($pecahan[0] == "ABSEN"){
		$ket_ab=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * from tbl_absen a left join tbl_ket b on a.id_ket=b.id_ket
												left join tbl_member c on a.kode_member=c.kode_member
																	where a.kode_member='$pecahan[1]' 
																	and a.tgl_absen='$pecahan[2]'
																	and a.id_jadwal='1'"));
		$ket_ab2=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * from tbl_absen a left join tbl_ket b on a.id_ket=b.id_ket
												left join tbl_member c on a.kode_member=c.kode_member
																	where a.kode_member='$pecahan[1]' 
																	and a.tgl_absen='$pecahan[2]'
																	and a.id_jadwal='2'"));
		$member=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * from tbl_member where kode_member='$pecahan[1]'"));
		if(!empty($ket_ab['tgl_absen']) and !empty($ket_ab2['tgl_absen'])){$isi="Hadir";}
		elseif(!empty($ket_ab['tgl_absen']) and empty($ket_ab2['tgl_absen'])){
			if($ket_ab['kode_ket'] == "S"){$isi=$ket_ab['nama_ket'];}
			elseif($ket_ab['kode_ket'] == "I"){$isi=$ket_ab['nama_ket'];}
			elseif($ket_ab['kode_ket'] == "A"){$isi=$ket_ab['nama_ket'];}
			else{$isi="Bolos";}
		}
		elseif(empty($ket_ab['tgl_absen']) and empty($ket_ab2['tgl_absen'])){$isi="Alfa";}
		else{$isi="$ket_ab[nama_ket]";}
		
		if(!empty($ket_ab['tgl_absen'])){$masuk="MASUK pada Tgl ".tgl_angka($pecahan[2])." pukul $ket_ab[jam_absen]";}
		elseif(empty($ket_ab['tgl_absen'])){$masuk="Belum melakukan absen MASUK";}
		if(!empty($ket_ab2['tgl_absen'])){$pulang="PULANG pada Tgl ".tgl_angka($pecahan[2])." pukul $ket_ab2[jam_absen]";}
		elseif(empty($ket_ab2['tgl_absen'])){$pulang="Belum melakukan absen PULANG";}
		
		//if(){
			$pesan ="$member[nama_member], $masuk, $pulang. Statusnya $isi. #$iden[nama_identitas]#";
			mysqli_query($koneksi,"INSERT into outbox (DestinationNumber,TextDecoded) VALUES ('$d_absen[SenderNumber]', '$pesan')"); 
			mysqli_query($koneksi,"UPDATE inbox set Processed='true' where ID='$d_absen[ID]'"); 
		//}
	}
}

?>