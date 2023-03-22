<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Foto User
        
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
	  
			<?php
				if(url_segment(2) == ""){echo js(base_url()."/".url_segment(1)."/home");}
				switch(url_segment(2)){
					case "home":  
						?>
							<p>
								<a href="<?php echo base_url()."/".url_segment(1);?>/add" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Upload Baru</a>
							</p>
							<div class="row">
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_foto a 
													left join  tbl_user b on a.kode_user=b.kode_user
													left join tbl_perangkat c on b.id_perangkat=c.id_perangkat
													order by a.kode_user ASC");
									while($d2=mysqli_fetch_assoc($h2)){
									?>
										<div class="col-xs-12 col-lg-4">
											<div class="box box-primary">
												<div class="box-body">
													<div class="row">
														<div class="col-xs-12 col-lg-5"><img src="<?php echo base_url()."/img/user/$d2[nama_foto]";?>" class="img-circle img-responsive" alt="User Image"></div>
														<div class="col-xs-12 col-lg-7">
															<div style="border-bottom:1px solid #DDD; padding:5px;">Kode:</div>
															<div style="border-bottom:1px solid #DDD; padding:5px;"><?php echo strtoupper($d2['kode_user']);?></div>
															<div style="border-bottom:1px solid #DDD; padding:5px;">Nama Lengkap:</div>
															<div style="border-bottom:1px solid #DDD; padding:5px;"><b><?php echo strtoupper($d2['nama_user']);?></b></div>
															
															<div class="box-footer clearfix">
															  <a href="<?php echo base_url()."/".url_segment(1)."/edit/$d2[kode_user]";?>" class="btn btn-sm btn-warning pull-left">Edit</a>
															  <a href="<?php echo base_url()."/".url_segment(1)."/hapus/$d2[kode_user]";?>" class="btn btn-sm btn-danger pull-right">Hapus</a>
															</div>
														</div>
													</div>
													
												</div>
											 </div>
										  </div>
										  <!-- /.box -->
									<?php
									 }
								  ?>
								</div>
						<?php
					break;
					case 'add' : 
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1);?>/simpan" class="form"  enctype="multipart/form-data">
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
							<p>Foto:<br><input type="file" name="files" class="form-control" required="true"></p>
								<p>
									<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
									<input type="submit" name="submit" id="button" class="btn btn-success" value="Upload">
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
					case 'simpan' : 
						$tipe_file   = $_FILES['files']['type'];
						$size_file   = $_FILES['files']['size'];
						$lokasi_file = $_FILES['files']['tmp_name'];
						$nama_file 	 = $_FILES['files']['name'];
						$explode	 = explode(".",$nama_file); 
						$ganti_nama	 = $_POST['user'].".".$explode[1];
						
						if(mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_foto where kode_user='$_POST[user]'")) < 1){
							$h3=mysqli_query($koneksi,"insert into tbl_foto (kode_user,nama_foto) values ('$_POST[user]','$ganti_nama')");
	
							if($h3 == TRUE){
									move_uploaded_file($lokasi_file,"img/user/$ganti_nama");
									echo js(base_url()."/".url_segment(1));
							}
							else{
									echo jsPesan("Error",base_url()."/".url_segment(1)."/add");
							}
						}
						else{
									echo jsPesan("Foto user tersebut sudah ada dalam sistem",base_url()."/".url_segment(1)."/add");
						}
					break;
					case 'edit' : 
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_foto a 
													left join  tbl_user b on a.kode_user=b.kode_user
													left join tbl_perangkat c on b.id_perangkat=c.id_perangkat
													WHERE a.kode_user='".url_segment(3)."'"));
						?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1);?>/update" class="form"  enctype="multipart/form-data">
							<input type="hidden" name="id" value="<?php echo $data['kode_user'];?>">
							<input type="hidden" name="lama" value="<?php echo $data['nama_foto'];?>">
							<p>Mesin:<br>
								<input type="text" name="mesin" class="form-control" value="<?php echo $data['nama_perangkat'];?>" disabled="true">
							</p>
							<p>User:<br>
								<input type="text" name="user" class="form-control" value="<?php echo $data['nama_user'];?>" disabled="true">
							</p>
							<p>Foto:<br><input type="file" name="files" class="form-control" required="true"></p>
								<p>
									<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
									<input type="submit" name="submit" id="button" class="btn btn-success" value="Upload">
								</p>
						</form>
						<?php
					break;
					case 'update' : 
						$tipe_file   = $_FILES['files']['type'];
						$size_file   = $_FILES['files']['size'];
						$lokasi_file = $_FILES['files']['tmp_name'];
						$nama_file 	 = $_FILES['files']['name'];
						$explode	 = explode(".",$nama_file); 
						$ganti_nama	 = $_POST['id'].".".$explode[1];
							$h3=mysqli_query($koneksi,"UPDATE tbl_foto SET nama_foto='$ganti_nama' WHERE kode_user='$_POST[id]'");
	
							if($h3 == TRUE){
									unlink($lokasi_file,"img/user/$_POST[lama]");
									move_uploaded_file($lokasi_file,"img/user/$ganti_nama");
									echo js(base_url()."/".url_segment(1));
							}
							else{
									echo jsPesan("Error",base_url()."/".url_segment(1)."/edit/$_POST[id]");
							}
					break;
									
					case 'hapus' : 
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_foto a 
													left join  tbl_user b on a.kode_user=b.kode_user
													left join tbl_perangkat c on b.id_perangkat=c.id_perangkat
													WHERE a.kode_user='".url_segment(3)."'"));
						unlink("img/user/$data[nama_foto]");
						$h3=mysqli_query($koneksi,"delete from tbl_foto where kode_user='".url_segment(3)."'");
	
						if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
						}
						else{
								echo jsPesan("Error",base_url()."/".url_segment(1)."");
						}
					break;
					
				}
			?>
    </section>
    <!-- /.content -->
	