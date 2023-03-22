<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Log Absensi
        
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
	  <div class="box box-primary">
	     <div class="box-body">
	<?php
				switch(url_segment(2)){
					default: 
	?>
	<p>
								<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1);?>/search" class="form">
								<div class="input-group">
								<input type="text" name="cari" id='cari' placeholder="Cari berdasarkan nama" required="true" class="form-control">
								<span class="input-group-btn">
						            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
						          </span>
						        </div>
								</form>
							</p>
			<table border="0" cellspacing="0" cellpadding="5" class="table table-striped" >
			  <tr>
				<th>No</td>
				<th>WAKTU</td>
				<th>KODE</td>
				<th>NAMA</td>
				<th>KETERANGAN</td>
				<th>TERLAMBAT</td>
				<th><a href="<?php echo base_url()."/".url_segment(1);?>/add" class="btn btn-success">Add Data</a></td>
			  </tr>
			  <?php
				$batas = 20;
				if(url_segment(2) == "hal") 
				{
					$hal = url_segment(3);
					$posisi = ($hal-1) * $batas;
					$no= $posisi + 1;
				}
				else 
				{
					$posisi = 0;
					$hal = 1;
					$no=1;
				}
				if(url_segment(2) == "search") 
									{
										$h2=mysqli_query($koneksi,"select * from absen a 
									left join tbl_user c on a.kode_user=c.kode_user
									left join tbl_status d on a.status_absen=d.kode_status WHERE c.nama_user like '%$_POST[cari]%' order by a.id_absen DESC");
									}
									else{
					$h2=mysqli_query($koneksi,"select * from absen a 
									left join tbl_user c on a.kode_user=c.kode_user
									left join tbl_status d on a.status_absen=d.kode_status
									order by a.id_absen DESC LIMIT $posisi,$batas");
				}
				while($d2=mysqli_fetch_assoc($h2)){
					echo"<tr class='baris'>
					<td>".$no++.".</td>
					<td>$d2[waktu_absen]</td>
					<td>$d2[kode_user]</td>
					<td>$d2[nama_user]</td>
					<td>$d2[nama_status]</td>
					<td>$d2[telat_absen] Menit</td>
					<td>".link_hapus(base_url()."/".url_segment(1)."/hapus/".$d2['id_absen'])."</td>
				  </tr>";
				 }
			  ?>
			</table>
         </div>
		 <div class="box-footer">
		 	
			<?php 
			if(url_segment(2) != "search") 
			{
				 $jmldata=mysqli_num_rows(mysqli_query($koneksi,"select * from absen a 
								left join tbl_user c on a.kode_user=c.kode_user"));
				echo halaman($jmldata,$batas,base_url()."/".url_segment(1),$hal);
			}
			?>
			
		 </div>
		 
<?php 
	break;
	case "add":
		?>
			<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/simpan";?>" class="form">
			<p>Tanggal:<br><input type="date" name="tanggal" placeholder="Tanggal" class="form-control" required="true"></p>
			<p>Pukul:<br><input type="time" name="pukul" placeholder="pukul" class="form-control" required="true"></p>
				<p>Mesin:<br>
							<select class="form-control" name="mesin" id="mesin" required="true">
								<option value="">.::Pilih Data:.</option>
							
							<?php
								$h3=mysqli_query($koneksi,"select * from tbl_perangkat order by nama_perangkat ASC");
								while($d3=mysqli_fetch_array($h3)){
									?>
									<option value="<?php echo $d3['id_perangkat'];?>"><?php echo $d3['nama_perangkat'];?></option>
									<?php
								}
							?>
							</select>
							</p>
				<div id="data-user"></div>
				<p>Status Absen?:<br>
				<?php 
					$h4=mysqli_query($koneksi,"select * from tbl_status order by kode_status ASC");
								while($d4=mysqli_fetch_array($h4)){
									?>
									<label>
									<input type='radio' name='status' id='status' value='<?php echo $d4['kode_status'];?>' required="true">
									<?php echo $d4['nama_status'];?></label>
				<?php
								}
				?>
					</p>
						<p>
							<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
						</p>
					</form>
					<script src="<?php echo base_url();?>/assets/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
							<script type="text/javascript">
								$(function(){ // sama dengan $(document).ready(function(){
										$('#mesin').change(function(){
											$('#data-user').html("Loading..."); //Menampilkan loading selama proses pengambilan data kota
											var id = $(this).val(); //Mengambil id provinsi
											$.get("<?php echo base_url();?>/views/ajax_user.php", {id:id}, function(data){
												$('#data-user').html(data);
												$('#data-user2').html('');
											});
										});
									});
								</script>
		<?php
	break;
	case "simpan":
		$cek_data=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM absen where kode_user='$_POST[user]' and tgl_absen='$_POST[tanggal]' and status_absen='$_POST[status]'"));
		if($cek_data < 1){
			//cek kesediaan jadwal
			$bc_jadwal=mysqli_query($koneksi,"select * from tbl_jadwal where kode_jadwal='$_POST[status]'");
			$jdwl=mysqli_fetch_assoc($bc_jadwal);
				if($jdwl['kode_jadwal'] == "0"){
					$selisih=datediff("$jdwl[berhenti_jadwal]", "$_POST[pukul]");
					if($selisih > 0){$jum_menit = $selisih['minutes_total'];}
					else{$jum_menit = "0";}
				}
				else{$jum_menit = "0";}
			
			$h3=mysqli_query($koneksi,"insert into absen (kode_user,tgl_absen,jam_absen,verified_absen,status_absen,waktu_absen,telat_absen) 
											values ('$_POST[user]','$_POST[tanggal]','$_POST[pukul]','0','$_POST[status]','$_POST[tanggal] $_POST[pukul]','$jum_menit')");
			if($h3 == TRUE){
					echo js(base_url()."/".url_segment(1));
			}
			else{
					echo jsPesan("Erorr Query",base_url()."/".url_segment(1)."/add");
			}
		}
		else{
			echo jsPesan("User tersebut sudah melakukan absen",base_url()."/".url_segment(1)."/add");
		}
	break;
	case "hapus":
		$h3=mysqli_query($koneksi,"delete from absen where id_absen='".url_segment(3)."'");
			if($h3 == TRUE){
					echo js(base_url()."/".url_segment(1));
			}
			else{
					echo jsPesan("Erorr Query",base_url()."/".url_segment(1)."");
			}
	break;
	}
?>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->