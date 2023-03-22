<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sidik Jari User
        
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
								<a href="<?php echo base_url()."/".url_segment(1);?>/tarik-data" class="btn btn-default"><span class="glyphicon glyphicon-open"></span> Download dari Mesin</a>
								<a href="<?php echo base_url();?>/views/excel_jari.php" class="btn btn-default"><span class="glyphicon glyphicon-save"></span> Export ke Excel</a>
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
								<table class="table table-striped table-bordered">
								  <tr>
									<th>KODE</th>
									<th>NAMA LENGKAP</th>
									<th>MESIN</th>
									<th></th>
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
									mysqli_query($koneksi,"delete from tbl_jari where kode_user='0'");
									if(url_segment(2) == "search") 
									{
										$h2=mysqli_query($koneksi,"select * from tbl_jari a 
													left join  tbl_user b on a.kode_user=b.kode_user
													left join tbl_perangkat c on b.id_perangkat=c.id_perangkat WHERE b.nama_user like '%$_POST[cari]%' order by a.kode_user ASC");
									}
									else{
										$h2=mysqli_query($koneksi,"select * from tbl_jari a 
													left join  tbl_user b on a.kode_user=b.kode_user
													left join tbl_perangkat c on b.id_perangkat=c.id_perangkat
													order by a.kode_user ASC LIMIT $posisi,$batas");
									}
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>$d2[kode_user]</td>
										<td>$d2[nama_user]</td>
										<td>$d2[nama_perangkat]</td>
										<td>
											".link_hapus(base_url()."/".url_segment(1)."/hapus/$d2[kode_user]")."
										</td>
									  </tr>";
									 }
								  ?>
								</table>
								<?php 
								if(url_segment(2) != "search") 
									{
										 $jmldata=mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_jari a 
													left join  tbl_user b on a.kode_user=b.kode_user
													left join tbl_perangkat c on b.id_perangkat=c.id_perangkat
													order by a.kode_user ASC"));
										echo halaman($jmldata,$batas,base_url()."/".url_segment(1),$hal);
									}
									?>
						<?php
					break;
					case 'tarik-data' : 
						echo"<p>Pilih mesin dibawah ini:</p>";
						$h2=mysqli_query($koneksi,"select * from tbl_perangkat order by nama_perangkat ASC");
						while($d2=mysqli_fetch_assoc($h2)){
							echo"<p><a href='".base_url()."/".url_segment(1)."/proses-tarik-data/$d2[id_perangkat]' class='btn btn-success'>Mesin $d2[nama_perangkat]</a></p>";
						 }
					break;
					case 'proses-tarik-data' : 
						$h2=mysqli_query($koneksi,"select * from tbl_user b
										left join tbl_perangkat c on b.id_perangkat=c.id_perangkat 
										where b.id_perangkat='".url_segment(3)."'
										order by b.kode_user ASC");
						while($d2=mysqli_fetch_assoc($h2)){
						$Connect = fsockopen($d2['ip_perangkat'], "80", $errno, $errstr, 1);
							if($Connect){
									$soap_request="<GetUserTemplate>
													<ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey>
													<Arg>
														<PIN xsi:type=\"xsd:integer\">".$d2['kode_user']."</PIN>
														<FingerID xsi:type=\"xsd:integer\">".$d2['kode_user']."</FingerID>
													</Arg>
													</GetUserTemplate>";
									$newLine="\r\n";
									fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
									fputs($Connect, "Content-Type: text/xml".$newLine);
									fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
									fputs($Connect, $soap_request.$newLine);
									$buffer="";
									while($Response=fgets($Connect, 2024)){
										$buffer=$buffer.$Response;
									}
							}else echo "Koneksi Gagal";
								$buffer=Parse_Data($buffer,"<GetUserTemplateResponse>","</GetUserTemplateResponse>");
								$buffer=explode("\r\n",$buffer);
								for($a=0;$a<count($buffer);$a++){
								$data=Parse_Data($buffer[$a],"<Row>","</Row>");
								$PIN=Parse_Data($data,"<PIN>","</PIN>");
								$Template=Parse_Data($data,"<Template>","</Template>");
								if(mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_jari where kode_user='$PIN'")) < 1){
										$proses_update = mysqli_query($koneksi,"INSERT INTO tbl_jari (kode_user,isi_jari) values ('$PIN','$Template')");
								}
								else{
										$proses_update = mysqli_query($koneksi,"UPDATE tbl_jari SET isi_jari='$Template' where kode_user='$PIN'");
								}
								//$proses_update = mysqli_query($koneksi,"UPDATE tbl_member SET jari_member='$Template' where kode_member='$PIN'");
								
								}
								
							}
							if($proses_update == TRUE){
								echo js(base_url()."/".url_segment(1));
							}
							else{
								echo jsPesan("Gagal..",base_url()."/".url_segment(1)."/tarik-data");
							}
					break;
									
					case 'hapus' : 
						$h3=mysqli_query($koneksi,"delete tbl_jari where kode_user='".url_segment(3)."'");
	
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
							<p>File Excel:<br><input type="file" name="files" class="form-control" required="true"></p>
								<p>
									<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
									<input type="submit" name="submit" id="button" class="btn btn-success" value="Upload">
								</p>
						</form>
						<p class="alert alert-warning">Download Format Data Excel <a href='<?php echo base_url();?>/assets/format/format_jari.xls'>disini</a></p>
					<?php
					break;
					case 'proses-import' : 
						if(empty($_POST['perangkat']) || empty($_FILES['files']['name'])){
								echo jsPesan('Lengkapi form dengan benar',base_url()."/".url_segment(1)."/import");
							}
							else{
							include"assets/config/excel_reader2.php";
							$perangkat=mysqli_fetch_array(mysqli_query($koneksi,"select * from tbl_perangkat where id_perangkat='$_POST[perangkat]'"));
							$data = new Spreadsheet_Excel_Reader($_FILES['files']['tmp_name']);
							$jmlbaris = $data->rowcount(0);
							for ($itu=2; $itu<=$jmlbaris; $itu++)
							{
								$id= $data->val($itu, 1);
								$sidik = $data->val($itu, 2);
								//echo"$sidik<br>";
								if($data->val($itu, 2) != ""){
									$Connect = fsockopen($perangkat['ip_perangkat'], "80", $errno, $errstr, 1);
									if($Connect){
										$soap_request="<SetUserTemplate><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">".$id."</PIN><FingerID xsi:type=\"xsd:integer\">0</FingerID><Size>".strlen($sidik)."</Size><Valid>1</Valid><Template>".$sidik."</Template></Arg></SetUserTemplate>";
										$newLine="\r\n";
										fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
										fputs($Connect, "Content-Type: text/xml".$newLine);
										fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
										fputs($Connect, $soap_request.$newLine);
										$buffer="";
										while($Response=fgets($Connect, 2024)){
											$buffer=$buffer.$Response;
										}
									}else echo "Koneksi Gagal";
									//	echo $buffer;
									$buffer=Parse_Data($buffer,"<SetUserTemplateResponse>","</SetUserTemplateResponse>");
									$buffer=Parse_Data($buffer,"<Information>","</Information>");
									if(mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_jari where kode_user='$sidik'")) < 1){
										$proses_update = mysqli_query($koneksi,"INSERT INTO tbl_jari (kode_user,isi_jari) values ('$id','$sidik')");
									}
									else{
										$proses_update = mysqli_query($koneksi,"UPDATE tbl_jari SET isi_jari='$sidik' where kode_user='$id'");
									}
									
									//Refresh DB
									$Connect = fsockopen($perangkat['ip_perangkat'], "80", $errno, $errstr, 1);
									$soap_request="<RefreshDB><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey></RefreshDB>";
									$newLine="\r\n";
									fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
									fputs($Connect, "Content-Type: text/xml".$newLine);
									fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
									fputs($Connect, $soap_request.$newLine);
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