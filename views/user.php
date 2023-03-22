<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Mesin
        
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
								<a href="<?php echo base_url()."/".url_segment(1);?>/import" class="btn btn-default"><span class="glyphicon glyphicon-open"></span> Import dari Excel</a>
								<a href="<?php echo base_url();?>/views/excel_user.php" class="btn btn-default"><span class="glyphicon glyphicon-save"></span> Export ke Excel</a>
							</p>
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
									<th>KODE</td>
									<th>NAMA LENGKAP</td>
									<th>MESIN</td>
									<th><a href="<?php echo base_url()."/".url_segment(1);?>/add" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Buat Baru</a></td>
								  </tr>
								  <?php
									$batas = 15;
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
										$h2=mysqli_query($koneksi,"select * from tbl_user a left join tbl_perangkat b on a.id_perangkat=b.id_perangkat WHERE a.nama_user like '%$_POST[cari]%' order by a.kode_user ASC");
									}
									else{
										$h2=mysqli_query($koneksi,"select * from tbl_user a left join tbl_perangkat b on a.id_perangkat=b.id_perangkat order by a.kode_user ASC LIMIT $posisi,$batas");
									}
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>$d2[kode_user]</td>
										<td>$d2[nama_user]</td>
										<td>$d2[nama_perangkat]</td>
										<td>
											".link_edit(base_url()."/".url_segment(1)."/edit/$d2[kode_user]")."
											".link_hapus(base_url()."/".url_segment(1)."/cek_hapus/$d2[kode_user]")."
										</td>
									  </tr>";
									 }
								  ?>
								</table>
								<div class="row">
		 	<div class="col-sm-8">
								<?php 
								if(url_segment(2) != "search") 
									{
										 $jmldata=mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_user a left join tbl_perangkat b on a.id_perangkat=b.id_perangkat order by a.kode_user ASC"));
										echo halaman($jmldata,$batas,base_url()."/".url_segment(1),$hal);
									}
								?>
						</div>
					<div class="col-sm-4">
						<?php
							$no=1;
											$h2=mysqli_query($koneksi,"select * from tbl_perangkat order by nama_perangkat ASC");
											while($d2=mysqli_fetch_assoc($h2)){
												$jumUser=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM tbl_user where id_perangkat='$d2[id_perangkat]'"));
												$dataUser[]=$jumUser;
												echo "<li>$d2[nama_perangkat] : $jumUser Data</li>";
												
											 }
											 echo "<li>Total : ".array_sum($dataUser)." Data</li>";
						?>
					</div>
			</div>
								
						<?php
					break;
					case 'add' : 
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1);?>/simpan" class="form">
						<p>KODE:<br><input type="text" name="nis" id='nis' placeholder="Kode atau Nomor Identitas" required="true" class="form-control"></p>
						<p>Nama Lengkap:<br><input type="text" name="nama" id='nama' placeholder="Nama Lengkap" required="true" class="form-control"></p>
						<p>Mesin:<br><select name='mesin' id='mesin' class="form-control">
								<option value=''>Pilih Mesin</option>
						<?php
								$query=mysqli_query($koneksi,"select * from tbl_perangkat order by nama_perangkat ASC");
								while($data=mysqli_fetch_assoc($query)){
									echo"<option value='$data[id_perangkat]'>$data[nama_perangkat]</option>";
								}
						?>
							</select></p>
							<p>Apakah data mau di simpan ke mesin ?<br>
							<label>
							  <input type='radio' name='buat' id='buat' value='Tidak' required="true">
							  Tidak</label>
							  <label>
							  <input name='buat' type='radio' id='buat' value='Ya'  required="true" checked>
								Ya</label></p>
							<p>
								<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
								<input type="submit" name="submit" id="button" class="btn btn-success" value="Simpan">
							</p>
					</form>
					<?php
					break;
					case 'simpan' : 
						$cek_kode=mysqli_num_rows(mysqli_query($koneksi,"SELECT * from tbl_user where kode_user='$_POST[kode]'"));
						if($cek_kode < 1){
						$h3=mysqli_query($koneksi,"INSERT INTO tbl_user 
											(kode_user,nama_user,id_perangkat,tgl_user)
											 values
											('$_POST[nis]','$_POST[nama]','$_POST[mesin]',now())");
						if($_POST['buat'] == "Ya"){
							$perangkat=mysqli_fetch_array(mysqli_query($koneksi,"select * from tbl_perangkat where id_perangkat='$_POST[mesin]'"));
							$Connect = fsockopen($perangkat['ip_perangkat'], "80", $errno, $errstr, 1);
							if($Connect){
								$soap_request="<SetUserInfo>
													<ArgComKey Xsi:type=\"xsd:integer\">".$perangkat['key_perangkat']."</ArgComKey>
													<Arg>
														<PIN>".$_POST['nis']."</PIN>
														<Name>".$_POST['nama']."</Name>
													</Arg>
												</SetUserInfo>";
								$newLine="\r\n";
								fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
								fputs($Connect, "Content-Type: text/xml".$newLine);
								fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
								fputs($Connect, $soap_request.$newLine);
								$buffer="";
								while($Response=fgets($Connect, 2024)){
									$buffer=$buffer.$Response;
								}
							$buffer=parse_data($buffer,"<Information>","</Information>");
							}
						}
						if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
						}
						else{
								echo jsPesan("Error",base_url()."/".url_segment(1)."/add");
						}
					}else{
						echo jsPesan("Kode Sudah terdaftar",base_url()."/".url_segment(1)."/add");
					}
					break;
					case 'edit' : 
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_user where kode_user='".url_segment(3)."'"));
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1);?>/update" class="form">
						<input type="hidden" name="id" id='id' placeholder="Kode atau Nomor Identitas" value="<?php echo $data['kode_user'];?>" required="true" class="form-control">
						<p>KODE:<br><input type="text" name="nis" id='nis' placeholder="Kode atau Nomor Identitas" value="<?php echo $data['kode_user'];?>" disabled="true"></p>
						<p>Nama Lengkap:<br><input type="text" name="nama" id='nama' placeholder="Nama Lengkap" required="true" class="form-control" value="<?php echo $data['nama_user'];?>"></p>
							<p>
								<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
								<input type="submit" name="submit" id="button" class="btn btn-success" value="Simpan">
							</p>
					</form>
					<div class="box box-default collapsed-box box-solid">
						<div class="box-header">
							<b>Warning!</b>
							<ul>
								<li>Perubahan Hanya bisa dilakukan pada data diaplikasi</li>
								<li>Jika ingin merubah data di mesin dan aplikasi maka silahkan hapus terlebih dahulu</li>
							</ul>
						</div>
					</div>
					<?php
					break;
					case 'update' : 
						$h3=mysqli_query($koneksi,"UPDATE tbl_user SET
							nama_user='$_POST[nama]'
							where kode_user='$_POST[id]'");
	
						if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
						}
						else{
								echo jsPesan("Error",base_url()."/".url_segment(1)."/edit/$_POST[id]");
						}
					break;
					case 'cek_hapus' :
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_user a 
															left join tbl_perangkat c on a.id_perangkat=c.id_perangkat
															where a.kode_user='".url_segment(3)."'"));
						?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1);?>/hapus" class="form">
						<input type="hidden" name="id" id='id' placeholder="Kode atau Nomor Identitas" value="<?php echo $data['kode_user'];?>" required="true" class="form-control">
						<p>KODE:<br><input type="text" name="nis" id='nis' placeholder="Kode atau Nomor Identitas" value="<?php echo $data['kode_user'];?>" disabled="true"></p>
						<p>Nama Lengkap:<br><input type="text" name="nama" id='nama' placeholder="Nama Lengkap" required="true" class="form-control" value="<?php echo $data['nama_user'];?>" disabled="true"></p>
						<p>Mesin:<br><input type="text" name="nama" id='nama' placeholder="Nama Lengkap" required="true" class="form-control" value="<?php echo $data['nama_perangkat'];?>" disabled="true"></p>
							<p>
								<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
								<input type="submit" name="submit" id="button" class="btn btn-success" value="Hapus">
							</p>
					</form>
					<?php
					break;					
					case 'hapus' : 
						$h3=mysqli_query($koneksi,"delete from tbl_user where kode_user='$_POST[id]'");
	
						if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
						}
						else{
								echo jsPesan("Error",base_url()."/".url_segment(1)."/cek_hapus/$_POST[id]");
						}
					break;
					case 'import' : 
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1);?>/proses-import" class="form"  enctype="multipart/form-data">
							<p>Mesin:<br>
							<?php
								$h3=mysqli_query($koneksi,"select * from tbl_perangkat order by nama_perangkat ASC");
								while($d3=mysqli_fetch_array($h3)){
									?>
									<label><input type="radio" name="perangkat" value="<?php echo $d3['id_perangkat'];?>" required="true"><?php echo $d3['nama_perangkat'];?></label><br>
									<?php
								}
							?>
							</p>
							<p>Fille Excel:<br><input type="file" name="files" class="form-control" required="true"></p>
							<p>Hapus semua data di mesin ?<br>
								<label>
								  <input type='radio' name='buat' id='buat' value='Tidak' required="true" checked>
								  Tidak</label>
								  <label>
								  <input name='buat' type='radio' id='buat' value='Ya' required="true">
									Ya</label></p>
								<p>
									<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
									<input type="submit" name="submit" id="button" class="btn btn-success" value="Upload">
								</p>
						</form>
						<p class="alert alert-warning">Download Format Data Excel <a href='<?php echo base_url();?>/assets/format/format_user.xls'>disini</a></p>
					<?php
					break;
					case 'proses-import' : 
						if(empty($_POST['perangkat']) || empty($_FILES['files']['name'])){
							echo jsPesan('Lengkapi form dengan benar',base_url()."/".url_segment(1)."/import");
						}
						else{
							include "assets/config/excel_reader2.php";
							$perangkat=mysqli_fetch_array(mysqli_query($koneksi,"select * from tbl_perangkat where id_perangkat='$_POST[perangkat]'"));
							if($_POST['buat'] == "Ya"){
								$Connect = fsockopen($perangkat['ip_perangkat'], "80", $errno, $errstr, 1);
								if($Connect){
									$soap_request="<ClearData><ArgComKey xsi:type=\"xsd:integer\">".$perangkat['key_perangkat']."</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";
									$newLine="\r\n";
									fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
									fputs($Connect, "Content-Type: text/xml".$newLine);
									fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
									fputs($Connect, $soap_request.$newLine);
									$buffer="";
									while($Response=fgets($Connect, 1024)){
										$buffer=$buffer.$Response;
									}
								}else echo "Koneksi Gagal";
								$buffer=Parse_Data($buffer,"<Information>","</Information>");
								mysqli_query($koneksi,"DELETE FROM tbl_user WHERE id_perangkat='$_POST[perangkat]'");
							}
						
						$data = new Spreadsheet_Excel_Reader($_FILES['files']['tmp_name']);
						$jmlbaris = $data->rowcount(0);
						for ($itu=2; $itu<=$jmlbaris; $itu++)
						{
						$id= $data->val($itu, 1);
						$nama = $data->val($itu, 2);
						$nama_fix=str_replace("'","",$nama);
						
							$Connect = fsockopen($perangkat['ip_perangkat'], "80", $errno, $errstr, 1);
							if($Connect){
								$soap_request="<SetUserInfo>
													<ArgComKey Xsi:type=\"xsd:integer\">".$perangkat['key_perangkat']."</ArgComKey>
													<Arg>
														<PIN>".$id."</PIN>
														<Name>".$nama_fix."</Name>
													</Arg>
												</SetUserInfo>";
								$newLine="\r\n";
								fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
								fputs($Connect, "Content-Type: text/xml".$newLine);
								fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
								fputs($Connect, $soap_request.$newLine);
								$buffer="";
								while($Response=fgets($Connect, 2024)){
									$buffer=$buffer.$Response;
								}
							$buffer=parse_data($buffer,"<Information>","</Information>");
							$cekUser = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(kode_user) AS jml FROM tbl_user WHERE kode_user='".$id."'"));
							if($cekUser['jml'] < 1){
								$querys =mysqli_query($koneksi,"INSERT INTO tbl_user (kode_user, nama_user,id_perangkat,tgl_user) 
										VALUES ('$id', '$nama_fix','$_POST[perangkat]',now())");
							}
							mysqli_query($koneksi,"DELETE FROM tbl_user WHERE kode_user=''");
							
						}	
						}
							if($querys == TRUE){
								echo js(base_url()."/".url_segment(1)."/");
							}
							else{
								echo jsPesan("ERROR",base_url()."/".url_segment(1)."/import");
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