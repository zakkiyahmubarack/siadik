<?php
date_default_timezone_set("Asia/Jakarta");
include "../assets/config/koneksi.php";
include "../assets/config/function.php";
$wkt_sekarang=date("H:i:s");
$hari_sekarang=date("D");
$data_bell=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_bell a left join tbl_hari b on a.kode_hari=b.kode_hari
										where b.eng_hari='$hari_sekarang' and a.pukul_bell='$wkt_sekarang'"));
?>
<script type="text/javascript">
	$(function(){
		<?php if($hari_sekarang == $data_bell['eng_hari']){ ?>
		<?php if($wkt_sekarang == $data_bell['pukul_bell']){ ?>
		$('.bell').attr("src","<?php echo base_url();?>/img/sound/<?php echo $data_bell['sound_bell'];?>");
		<?php } ?>
		<?php } ?>
	});
</script>