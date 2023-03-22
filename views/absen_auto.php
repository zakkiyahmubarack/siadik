<?php
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
include "../assets/config/koneksi.php";
include "../assets/config/function.php";
include "../assets/config/tgl_indo.php";
$log_akhir=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from absen a 
										left join tbl_user c on a.kode_user=c.kode_user
										order by a.waktu_absen DESC limit 0,1"));
$foto_user=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_foto where kode_user='$log_akhir[kode_user]'"));
if(!empty($foto_user['nama_foto'])){$foto=$foto_user['nama_foto'];}
elseif(file_exists("../img/user/$log_akhir[kode_user].jpg") == true){$foto=$log_akhir['kode_user'].".jpg";}
else{$foto="foto_kosong.jpg";}
$wkt_sekarang=date("H:i:s");

?>
<script type="text/javascript">
	$(function(){
		<?php if($wkt_sekarang == "21:53:00"){ ?>
		$('.bell').attr("src","<?php echo base_url();?>/views/sound.mp3");
		<?php } ?>
	});
</script>


<div class="row">
	<div class="col-xs-12 col-lg-6">
		<div class="row">
			<div class="col-sm-6 callout callout-info"> <h3><span class="glyphicon glyphicon-calendar"></span> <?php echo tgl_angka(date("Y-m-d"));?></h3></div>
		<div class="col-sm-6 callout callout-danger"> <h3><span class="glyphicon glyphicon-time"></span> <?php echo $wkt_sekarang;?></h3></div>
		</div>
		<table class="table table-striped table-bordered" >
								  <tr>
									<th>NO.</td>
									<th>JAM</td>
									<th>MULAI</td>
									<th>BERHENTI</td>
									<th>TOLERANSI</td>
								  </tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_jadwal order by kode_jadwal ASC");
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>".$no++.".</td>
										<td>$d2[nama_jadwal]</td>
										<td>$d2[mulai_jadwal]</td>
										<td>$d2[berhenti_jadwal]</td>
										<td>$d2[toleransi_jadwal]</td>
									  </tr>";
									 }
								  ?>
								</table>
	</div>
	<div class="col-xs-12 col-lg-6">
             
			<table class="table table-striped table-bordered" >
								  <tr>
									<th>KODE</td>
									<th>NAMA</td>
									<th>IP ADDRESS</td>
									<th>SCAN</td>
								  </tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_perangkat order by nama_perangkat ASC");
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>$d2[id_perangkat]</td>
										<td>$d2[nama_perangkat]</td>
										<td>$d2[ip_perangkat]</td>
										<td>
											".link_tampil($d2['status_perangkat'],base_url()."/".url_segment(1)."/status/$d2[id_perangkat]")."
										</td>
									  </tr>";
									 }
								  ?>
								</table>
			<div class="row">
			<?php
			$jum_tdkterkirim=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(status_pesan) AS jumlah FROM pesan WHERE status_pesan='N' AND tgl_pesan like '".date("Y-m-d")."%'"));
			$jum_terkirim=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(status_pesan) AS jumlah FROM pesan WHERE status_pesan='Y' AND tgl_pesan like '".date("Y-m-d")."%'"));
			
			?>
				<div class="col-sm-6 alert alert-success" style="text-align:center" >
					Terkirim
					<h1 style="margin:0"><?=$jum_terkirim['jumlah']?></h1>
				</div>
				<div class="col-sm-6 alert alert-warning" style="text-align:center">
					Tidak Terkirim
					<h1 style="margin:0"><?=$jum_tdkterkirim['jumlah']?></h1>
					</div>
			</div>
			
	</div>
</div>
<p>
<table border="0" cellspacing="0" cellpadding="5" class="table table-striped" >
  <tr>
    <th>No</td>
    <th>WAKTU</td>
    <th>KODE</td>
    <th>NAMA</td>
    <th>TERLAMBAT</td>
    <th>KETERANGAN</td>
  </tr>
  <?php
  	$no=1;
  	$h2=mysqli_query($koneksi,"select * from absen a 
					left join tbl_user c on a.kode_user=c.kode_user
					order by a.waktu_absen DESC limit 0,10");
	while($d2=mysqli_fetch_assoc($h2)){
		echo"<tr class='baris'>
		<td>".$no++.".</td>
		<td>$d2[waktu_absen]</td>
		<td>$d2[kode_user]</td>
		<td>$d2[nama_user]</td>
		<td>$d2[telat_absen] Menit</td>
		<td>".status_absen($d2['status_absen'])."</td>
	  </tr>";
	 }
  ?>
</table>
</p>