<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Nomor HP
        
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
						<a href="<?php echo base_url()."/".url_segment(1);?>/add" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Buat Baru</a>
						<a href="<?php echo base_url()."/".url_segment(1);?>/import" class="btn btn-primary"><span class="glyphicon glyphicon-upload"></span> Import</a>
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
								<table class="table table-striped table-bordered" >
								  <tr>
									<th>NO.</td>
									<th>KODE</td>
									<th>NAMA USER</td>
									<th>NO. HP</td>
									<th></td>
								  </tr>
								  <?php
								  $batas = 10;
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
										$h2=mysqli_query($koneksi,"select * from tbl_phone a LEFT JOIN tbl_user b on a.kode_user=b.kode_user WHERE b.nama_user like '%$_POST[cari]%' order by a.id_phone DESC");
									}
									else{
										$h2=mysqli_query($koneksi,"select * from tbl_phone a LEFT JOIN tbl_user b on a.kode_user=b.kode_user order by a.id_phone DESC LIMIT $posisi,$batas");
									}
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>".$no++.".</td>
										<td>$d2[kode_user]</td>
										<td>$d2[nama_user]</td>
										<td>$d2[no_phone]</td>
										<td>
											".link_edit(base_url()."/".url_segment(1)."/edit/$d2[id_phone]")."
											".link_hapus(base_url()."/".url_segment(1)."/hapus/$d2[id_phone]")."
										</td>
									  </tr>";
									 }

								  ?>
								  
								</table>
								<?php 
								if(url_segment(2) != "search") 
									{
										 $jmldata=mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_phone a LEFT JOIN tbl_user b on a.kode_user=b.kode_user order by a.id_phone DESC"));
										echo halaman($jmldata,$batas,base_url()."/".url_segment(1),$hal);
									}
									?>
						<?php
					break;
					case 'add' : 
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/simpan";?>" class="form">
						<p>Nama User:<br>
							<select class="form-control" name="user" id="user" required="true">
								<option value="">.::Pilih Data:.</option>
							
							<?php
								$h3=mysqli_query($koneksi,"select * from tbl_user order by nama_user ASC");
								while($d3=mysqli_fetch_array($h3)){
									if(mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_phone where kode_user='$d3[kode_user]'")) < 1){
									?>
									<option value="<?php echo $d3['kode_user'];?>"><?php echo $d3['nama_user'];?></option>
									<?php
									}
								}
							?>
							</select>
						</p>
						<p>Nomor HP:<br>
							<input type="text" name="no" placeholder="085624510xxx" class="form-control" required="true">
						</p>
						<p>
							<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
						</p>
					</form>
					<?php
					break;
					case 'simpan' : 
						if(empty($_POST['no'])){
							echo jsPesan('Lengkapi form dengan benar',base_url()."/".url_segment(1)."/add");
						}
						else{
							$h3=mysqli_query($koneksi,"insert into tbl_phone (no_phone,kode_user) 
											values ('$_POST[no]','$_POST[user]')");
							if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
							}
							else{
								echo jsPesan("Erorr Query",base_url()."/".url_segment(1)."/add");
							}
						}
					break;
					case 'edit' : 
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_phone a LEFT JOIN tbl_user b on a.kode_user=b.kode_user where a.id_phone='".url_segment(3)."'"));
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/update";?>" class="form">
						<p>Nama User:<br>
							<select class="form-control" name="user" id="user" required="true">
								<option value="<?php echo $data['kode_user'];?>"><?php echo $data['nama_user'];?></option>
							
							<?php
								$h3=mysqli_query($koneksi,"select * from tbl_user order by nama_user ASC");
								while($d3=mysqli_fetch_array($h3)){
									if(mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_phone where kode_user='$d3[kode_user]'")) < 1){
										if($d3['kode_user']<>$data['kode_user']){
									?>
									<option value="<?php echo $d3['kode_user'];?>"><?php echo $d3['nama_user'];?></option>
									<?php
										}
									}
								}
							?>
							</select>
						</p>
						<p>Nomor HP:<br>
							<input type="text" name="no" placeholder="085624510xxx" class="form-control" required="true" value="<?php echo $data['no_phone'];?>">
						</p>
						<p>
							<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
							<input type="hidden" name="id" value="<?php echo $data['id_phone'];?>">
						</p>
					</form>
					<?php
					break;
					case 'update' : 
						if(empty($_POST['no'])){
							echo jsPesan('Lengkapi form dengan benar',base_url()."/".url_segment(1)."/edit/$_POST[id]");
						}
						else{
							$h3=mysqli_query($koneksi,"UPDATE tbl_phone set
											no_phone='$_POST[no]',
											kode_user='$_POST[user]'
											where id_phone='$_POST[id]'");
							if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
							}
							else{
								echo jsPesan("Error Query",base_url()."/".url_segment(1)."/edit/$_POST[id]");
							}
						}
					break;
					case 'hapus' : 
						$h3=mysqli_query($koneksi,"DELETE FROM tbl_phone where id_phone='".url_segment(3)."'");
						if($h3 == TRUE){
							echo js(base_url()."/".url_segment(1));
						}
						else{
							echo jsPesan('Error',base_url()."/".url_segment(1));
						}
					break;
					case 'import': 
									?>
									<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1);?>/proses-import" class="form"  enctype="multipart/form-data">
										
										<p>File Excel:<br><input type="file" name="files" class="form-control" required="true"></p>
											<p>
												<a href="<?php echo base_url()."/".url_segment(1)."/".url_segment(2);?>" class="btn btn-warning">Kembali</a>
												<input type="submit" name="submit" id="button" class="btn btn-success" value="Upload">
											</p>
									</form>
									<p>Download Format Data Excel <a href='<?php echo base_url();?>/assets/format/format_nohp.xls'>disini</a></p>
								<?php
					break;
					case 'proses-import': 
									if(empty($_FILES['files']['name'])){
										echo jsPesan('Lengkapi form dengan benar',base_url()."/".url_segment(1)."/import");
									}
									else{
										include"assets/config/excel_reader2.php";
										$data = new Spreadsheet_Excel_Reader($_FILES['files']['tmp_name']);
										$jmlbaris = $data->rowcount(0);
										for ($itu=2; $itu<=$jmlbaris; $itu++)
										{
											$id= $data->val($itu, 1);
											$hp = $data->val($itu, 3);
											$no=preg_replace('/[^A-Za-z0-9\-]/','',$hp);
											$no_hp="+".$no;
											$cek_data=mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_phone where kode_user='$id'"));
											if($cek_data < 1){
												$proses_update = mysqli_query($koneksi,"INSERT INTO tbl_phone (kode_user,no_phone) VALUES ('$id','".$no_hp."')");
											}
											else{
												$proses_update = mysqli_query($koneksi,"UPDATE tbl_phone SET no_phone='".$no_hp."' WHERE kode_user='$id'");
											}
										}
										if($proses_update == TRUE){
											echo js( base_url()."/".url_segment(1));
										}
										else{
											echo jsPesan("Error Query", base_url()."/".url_segment(1)."/import");
										}

									}
					break;
					
				}
			?>
         </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->