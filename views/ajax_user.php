<?php
include "../assets/config/koneksi.php";
include "../assets/config/function.php";
include "../assets/config/tgl_indo.php";
$sql = mysqli_query($koneksi,"SELECT * FROM tbl_user WHERE id_perangkat='$_GET[id]' ORDER by nama_user ASC");
$jumlah=mysqli_num_rows($sql);
if($jumlah > 0 ){
?>
<p>
User: <br>
<select name="user" class="form-control" required="true">
	<option value="">..:Pilih Data:..</option>
	<?php
		while ($r = mysqli_fetch_assoc($sql)){
			$data=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_foto WHERE kode_user='$r[kode_user]'"));
			if($r['kode_user'] <> $data['kode_user']){
	?>
			<option value="<?php echo $r['kode_user']; ?>"> <?php echo $r['kode_user']." - ".$r['nama_user']; ?> </option>

	<?php }} /* end while */	?>

</select>
</p>
<?php } ?>