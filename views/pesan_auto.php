<?php
include"lib/koneksi.php";
include "lib/function.php";
$h2=mysqli_query($koneksi,"select * from inbox where Processed='false'");
if(mysqli_num_rows($h2) >= 1){
	while($d2=mysqli_fetch_assoc($h2)){
		$nomor_hp=$d2['SenderNumber'];
		$q_pesan=mysqli_query($koneksi,"select * from autorespon where format_auto='$d2[TextDecoded]'");
		if(mysqli_num_rows($q_pesan) >= 1){
			$data=mysqli_fetch_assoc($q_pesan);
			mysqli_query($koneksi,"INSERT INTO outbox (DestinationNumber, TextDecoded) 
							VALUES 
							('$nomor_hp', '$data[pesan_auto]')"); 
			mysqli_query($koneksi,"UPDATE inbox set Processed='true' where ID='$d2[ID]'");
		}		
	}
}
?>