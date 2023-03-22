<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Bell Otomatis
        
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
	  <div class="box box-primary">
	     <div class="box-body">
			<?php
				//if(url_segment(2) == ""){echo js(base_url()."/".url_segment(1)."/home");}
				switch(url_segment(2)){
					default:  
					?>
								<table class="table table-striped table-bordered" >
								  <tr>
									<th>NO.</td>
									<th>HARI</td>
									<th>JAM</td>
								  </tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_hari
													order by kode_hari ASC");
									while($d2=mysqli_fetch_assoc($h2)){
										$jumlah=mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_bell where kode_hari='$d2[kode_hari]'"));
										echo"<tr class='baris'>
										<td>".$no++.".</td>
										<td>$d2[indo_hari]</td>
										<td><a href='".base_url()."/".url_segment(1)."/home/$d2[kode_hari]"."' title='Klik untuk membuat jadwal'>$jumlah</a></td>
									  </tr>";
									 }
								  ?>
								</table>
								<div class="alert alert-info">Klik pada angka di kolom jam untuk mengatur jadwal jam pelajaran</div>
								<?php
					break;
					case "home":  
						switch(url_segment(4)){
							default:
							$qh=mysqli_query($koneksi,"SELECT * FROM tbl_hari where kode_hari='".url_segment(3)."'");
								$dh=mysqli_fetch_assoc($qh);
								echo "<h3>".$dh['indo_hari']."</h3>";
						?>
								<table class="table table-striped table-bordered" >
								  <tr>
									<th>NO.</td>
									<th>HARI</td>
									<th>NAMA</td>
									<th>PUKUL</td>
									<th>SOUND</td>
									<th><a href="<?php echo base_url()."/".url_segment(1)."/home/".url_segment(3);?>/add" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Buat Baru</a></td>
								  </tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_bell a 
													left join tbl_hari b on a.kode_hari=b.kode_hari 
													where a.kode_hari='".url_segment(3)."'
													order by a.pukul_bell ASC");
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>".$no++.".</td>
										<td>$d2[indo_hari]</td>
										<td>$d2[nama_bell]</td>
										<td>$d2[pukul_bell]</td>
										<td><audio src='".base_url()."/img/sound/$d2[sound_bell]' controls='true'></audio></td>
										<td>
											".link_edit(base_url()."/".url_segment(1)."/home/".url_segment(3)."/edit/$d2[id_bell]")."
											".link_hapus(base_url()."/".url_segment(1)."/home/".url_segment(3)."/hapus/$d2[id_bell]")."
										</td>
									  </tr>";
									 }
								  ?>
								</table>
								<p>
									<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/home/".url_segment(3)."/salin";?>" class="form" enctype="multipart/form-data">
										<p>Salin ke Hari:<br>
										<select name="hari">
											<?php 
												$q1=mysqli_query($koneksi,"SELECT * FROM tbl_hari order by kode_hari ASC");
												while($d1=mysqli_fetch_assoc($q1)){
													if($d1['kode_hari'] <> url_segment(3)){
											?>
											<option value="<?php echo $d1['kode_hari'];?>"><?php echo $d1['indo_hari'];?></option>
											<?php }} ?>
										</select>
											<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-sm btn-success">
									</form>
								</p>
						<?php
					break;
					case 'add' : 
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/home/".url_segment(3)."/simpan";?>" class="form" enctype="multipart/form-data">
						<p>Hari:<br>
						<input type='hidden' name='hari' id='hari' value='<?php echo url_segment(3); ?>' required="true"> 
							<?php 
								$q1=mysqli_query($koneksi,"SELECT * FROM tbl_hari where kode_hari='".url_segment(3)."' order by kode_hari ASC");
								$d1=mysqli_fetch_assoc($q1);
								echo $d1['indo_hari'];
							?>
						</p>
						<p>Pukul:<br><input type="time" name="pukul" placeholder="pukul" class="form-control" required="true"></p>
						<p>Nama:<br><input type="text" name="nama" placeholder="Nama" class="form-control" required="true"></p>
						<p>Sound:<br><input type="file" name="files" placeholder="files" class="form-control" required="true"></p>
						<p>
							<a href="<?php echo base_url()."/".url_segment(1)."/home/".url_segment(3);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
						</p>
					</form>
					<?php
					break;
					case 'simpan' : 
						$tipe_file   = $_FILES['files']['type'];
						$size_file   = $_FILES['files']['size'];
						$lokasi_file = $_FILES['files']['tmp_name'];
						$nama_file 	 = $_FILES['files']['name'];
						$rename=nama_file($nama_file);
							$h3=mysqli_query($koneksi,"insert into tbl_bell (kode_hari,pukul_bell,nama_bell,sound_bell) 
											values ('$_POST[hari]','$_POST[pukul]','$_POST[nama]','$rename')");
							if($h3 == TRUE){
								move_uploaded_file($lokasi_file,"img/sound/$rename");
								echo js(base_url()."/".url_segment(1)."/home/".url_segment(3));
							}
							else{
								echo jsPesan("Erorr Query",base_url()."/".url_segment(1)."/home/".url_segment(3)."/add");
							}
						
					break;
					case 'edit' : 
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_bell a left join tbl_hari b on a.kode_hari=b.kode_hari where a.id_bell='".url_segment(5)."'"));
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/home/".url_segment(3)."/update";?>" class="form" enctype="multipart/form-data">
						
						<p>Pukul:<br><input type="time" name="pukul" placeholder="pukul" class="form-control" required="true" value="<?php echo $data['pukul_bell']; ?>"></p>
						<p>Nama:<br><input type="text" name="nama" placeholder="Nama" class="form-control" required="true" value="<?php echo $data['nama_bell']; ?>"></p>
						<p>Sound:<br>
						<audio src="<?php echo base_url()."/img/sound/$data[sound_bell]";?>" controls="true"></audio><br>
						<input type="file" name="files" placeholder="files" class="form-control"></p>
						<p>
							<a href="<?php echo base_url()."/".url_segment(1)."/home/".url_segment(3);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
							<input type="hidden" name="id" value="<?php echo $data['id_bell'];?>">
							<input type="hidden" name="lama" value="<?php echo $data['sound_bell'];?>">
						</p>
					</form>
					<?php
					break;
					case 'update' : 
						$tipe_file   = $_FILES['files']['type'];
						$size_file   = $_FILES['files']['size'];
						$lokasi_file = $_FILES['files']['tmp_name'];
						$nama_file 	 = $_FILES['files']['name'];
						if(empty($nama_file)){
							$h3=mysqli_query($koneksi,"update tbl_bell SET 
											pukul_bell='$_POST[pukul]',
											nama_bell='$_POST[nama]'
											WHERE id_bell='$_POST[id]'");
						}
						else{
							$rename=nama_file($nama_file);
							$h3=mysqli_query($koneksi,"update tbl_bell SET 
											pukul_bell='$_POST[pukul]',
											nama_bell='$_POST[nama]',
											sound_bell='$rename' 
											WHERE id_bell='$_POST[id]'");
							unlink("img/sound/$_POST[lama]");
							move_uploaded_file($lokasi_file,"img/sound/$rename");
						}
							if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1)."/home/".url_segment(3));
							}
							else{
								echo jsPesan("Error Query",base_url()."/".url_segment(1)."/home/".url_segment(3)."/edit/$_POST[id]");
							}
						
					break;
					case 'hapus' : 
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_bell a left join tbl_hari b on a.kode_hari=b.kode_hari where a.id_bell='".url_segment(5)."'"));
						unlink("img/sound/$data[sound_bell]");
						$h3=mysqli_query($koneksi,"DELETE FROM tbl_bell where id_bell='".url_segment(5)."'");
						if($h3 == TRUE){
							echo js(base_url()."/".url_segment(1)."/home/".url_segment(3));
						}
						else{
							echo jsPesan(base_url()."/".url_segment(1)."/home/".url_segment(3));
						}
					break;
					case 'salin' :
						$q_data=mysqli_query($koneksi,"SELECT * FROM tbl_bell WHERE kode_hari='".url_segment(3)."'");
						while($d_data=mysqli_fetch_assoc($q_data)){
							$sound_lama=explode("-", $d_data['sound_bell']);
							$sound_baru=$rename=nama_file($sound_lama[1]);
							copy("img/sound/$d_data[sound_bell]","img/sound/$sound_baru");
							$query[]=mysqli_query($koneksi,"INSERT INTO tbl_bell (kode_hari,pukul_bell,nama_bell,sound_bell)
								values
								('$_POST[hari]','$d_data[pukul_bell]','$d_data[nama_bell]','$sound_baru')");
						}
						$jum=array_sum($query);
						echo jsPesan("Data tersalin $jum",base_url()."/".url_segment(1)."/".url_segment(2)."/".$_POST['hari']);
					break;	
					}
					
				}
			?>
         </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->