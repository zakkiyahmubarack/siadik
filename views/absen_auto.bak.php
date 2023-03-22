<?php
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
	<div class="col-xs-12 col-lg-2"><img src="<?php echo base_url()."/img/user/$foto";?>" class="img-responsive" alt="User Image"></div>
		<div class="col-xs-12 col-lg-5">
			<div style="border-bottom:1px solid #DDD; padding:6px; background:#F5F5F5">Waktu:</div>
			<div style="border-bottom:1px solid #DDD; padding:6px;"><?php echo $log_akhir['waktu_absen'];?></div>
			<div style="border-bottom:1px solid #DDD; padding:6px; background:#F5F5F5">Kode:</div>
			<div style="border-bottom:1px solid #DDD; padding:6px;"><?php echo strtoupper($log_akhir['kode_user']);?></div>
			<div style="border-bottom:1px solid #DDD; padding:6px; background:#F5F5F5">Nama Lengkap: </div>
			<div style="border-bottom:1px solid #DDD; padding:6px;"><?php echo $log_akhir['nama_user'];?></div>
			<div style="border-bottom:1px solid #DDD; padding:6px; background:#F5F5F5">TERLAMBAT: <?php echo  $log_akhir['telat_absen'];?> Menit</div>
			<div class="box-footer clearfix">ABSEN <b><?php echo status_absen($log_akhir['status_absen']);?></b></div>
	</div>
	<div class="col-xs-12 col-lg-5">
             <div class="callout callout-info"> <h3><span class="glyphicon glyphicon-calendar"></span> <?php echo tgl_angka(date("Y-m-d"));?> <span class="glyphicon glyphicon-time"></span> <?php echo $wkt_sekarang;?></h3></div>
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
					order by a.waktu_absen DESC limit 1,10");
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