<?php
$record = 2000;
$jum_outbox=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM outbox"));
$jum_sentitems=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM sentitems"));
if($jum_outbox >= $record){
	mysqli_query($koneksi,"TRUNCATE outbox");
}
if($jum_sentitems >= $record){
	mysqli_query($koneksi,"TRUNCATE sentitems");
}
?>