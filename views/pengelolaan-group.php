<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pengelolaan Group
        
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
	  
			<?php
				if(url_segment(2) == ""){
					?>
					<div class="box box-primary">
						<div class="box-body">
						<h3>Pilih Group dibawah ini</h3>
						<table class="table table-striped table-bordered" >
								  <tr>
									<th>NO.</td>
									<th>NAMA GROUP</td>
									<th></td>
								  </tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_group order by status_group DESC, nama_group ASC");
									while($d2=mysqli_fetch_assoc($h2)){
										$jum_data=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM tbl_member where id_group='$d2[id_group]'"));
										echo"<tr class='baris'>
										<td>".$no++.".</td>
										<td>$d2[nama_group] ($jum_data)</td>
										<td>";
										?>
										<a href="<?php echo base_url()."/".url_segment(1)."/$d2[id_group]";?>" class="btn btn-success"><span class="glyphicon glyphicon-chevron-right"></span> Buka</a>
										<?php
										echo"</td>
									  </tr>";
									 }
								  ?>
								</table>
						</div>
					</div>
				  <!-- /.box -->
					<?php
				}
				else{
					$group=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_group where id_group='".url_segment(2)."'"));
					?>
						<div class="callout callout-info">
							<h4>Group: <?php echo $group['nama_group'];?></h4>
							<p>Silahkan gunakan file excel untuk memasukan data pada group ini, data group digunakan sebagai daftar nama pada pelaporan. Data group juga bisa digunakan sebagai pengelompokan jabatan seperti (kelas, guru, tata usaha,kepala bagian, dll.)</p>
						</div>
					<div class="box box-primary">
						<div class="box-body">
							<?php
								//if(url_segment(3) == ""){echo js(base_url()."/".url_segment(1)."/".url_segment(2)."/home");}
								switch(url_segment(3)){
								default:  
										?>
								<p>
									<a href="<?php echo base_url()."/".url_segment(1)."/".url_segment(2)."/import";?>" class="btn btn-success"><span class="glyphicon glyphicon-open-file"></span> Import</a>
									<a href="<?php echo base_url()."/views/excel_pengelolaan-group.php?group=".url_segment(2)."";?>" class="btn btn-success"><span class="glyphicon glyphicon-save-file"></span> Eksport</a>
									<a href="<?php echo base_url()."/".url_segment(1)."/".url_segment(2)."/hapus-semua";?>" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus semua data digroup ini?');"><span class="glyphicon glyphicon-plus"></span> Hapus Semua</a>
								</p>
								<p>
								<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/".url_segment(2);?>/search" class="form">
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
									<th>KODE USER</td>
									<th>NAMA LENGKAP</td>
									<th></td>
								  </tr>
								  <?php
									$batas = 10;
									if(url_segment(3) == "hal") 
									{
										$hal = url_segment(4);
										$posisi = ($hal-1) * $batas;
										
									}
									else 
									{
										$posisi = 0;
										$hal = 1;
									}
									$no=1;
									if(url_segment(3) == "search") 
									{
										$h2=mysqli_query($koneksi,"select * from tbl_member a 
													left join tbl_user b on a.kode_user=b.kode_user
													left join tbl_group c on a.id_group=c.id_group
													where a.id_group='".url_segment(2)."' and  b.nama_user like '%$_POST[cari]%' order by a.kode_user ASC");
									}
									else{
										$h2=mysqli_query($koneksi,"select * from tbl_member a 
													left join tbl_user b on a.kode_user=b.kode_user
													left join tbl_group c on a.id_group=c.id_group
													where a.id_group='".url_segment(2)."'
													order by a.kode_user ASC, b.nama_user ASC
													LIMIT $posisi,$batas");
									}
									while($d2=mysqli_fetch_assoc($h2)){
										$data_user=mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_user where kode_user='$d2[kode_user]'"));
										if($data_user < 1){$style="background-color:#dd4b39;color:white;";}
										else{$style="";}
										echo"<tr style='$style'>
										<td>$d2[kode_user]</td>
										<td>$d2[nama_user]</td>
										<td>".link_hapus(base_url()."/".url_segment(1)."/".url_segment(2)."/hapus/$d2[kode_member]")."</td>
									  </tr>";
									 }
								  ?>
								</table>
								 <div class="box-footer">
								<?php 
									if(url_segment(3) != "search") 
									{
										 $jmldata=mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_member a 
													left join tbl_user b on a.kode_user=b.kode_user
													left join tbl_group c on a.id_group=c.id_group
													where a.id_group='".url_segment(2)."'
													order by a.kode_user ASC, b.nama_user ASC"));
										echo halaman($jmldata,$batas,base_url()."/".url_segment(1)."/".url_segment(2),$hal);
									}
									?>
								 </div>
								<?php
									break;
									case 'import': 
									?>
									<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/".url_segment(2);?>/proses-import" class="form"  enctype="multipart/form-data">
										
										<p>File Excel:<br><input type="file" name="files" class="form-control" required="true"></p>
											<p>
												<a href="<?php echo base_url()."/".url_segment(1)."/".url_segment(2);?>" class="btn btn-warning">Kembali</a>
												<input type="submit" name="submit" id="button" class="btn btn-success" value="Upload">
											</p>
									</form>
									<p>Download Format Data Excel <a href='<?php echo base_url();?>/assets/format/format_pgroup.xls'>disini</a></p>
								<?php
									break;
									case 'proses-import': 
									if(empty($_FILES['files']['name'])){
										echo jsPesan('Lengkapi form dengan benar',base_url()."/".url_segment(1)."/".url_segment(2)."/import");
									}
									else{
										include"assets/config/excel_reader2.php";
										$data = new Spreadsheet_Excel_Reader($_FILES['files']['tmp_name']);
										$jmlbaris = $data->rowcount(0);
										for ($itu=2; $itu<=$jmlbaris; $itu++)
										{
											$id= $data->val($itu, 1);
											$nama = $data->val($itu, 2);
											$cek_data=mysqli_num_rows(mysqli_query($koneksi,"select * from tbl_member where kode_user='$id' and id_group='".url_segment(2)."'"));
											if($cek_data < 1){
												$proses_update = mysqli_query($koneksi,"INSERT INTO tbl_member (kode_user,id_group) VALUES ('$id','".url_segment(2)."')");
											}
										}
										if($proses_update == TRUE){
											echo js( base_url()."/".url_segment(1)."/".url_segment(2));
										}
										else{
											echo jsPesan("Error Query", base_url()."/".url_segment(1)."/".url_segment(2)."/import");
										}

									}
									break;
									case 'hapus':
										$h3=mysqli_query($koneksi,"DELETE FROM tbl_member where kode_member='".url_segment(4)."'");
										if($h3 == TRUE){
											echo js(base_url()."/".url_segment(1)."/".url_segment(2));
										}
										else{
											echo jsPesan(base_url()."/".url_segment(1)."/".url_segment(2));
										}
									break;
									case 'hapus-semua':
										$h3=mysqli_query($koneksi,"DELETE FROM tbl_member where id_group='".url_segment(2)."'");
										if($h3 == TRUE){
											echo js(base_url()."/".url_segment(1)."/".url_segment(2));
										}
										else{
											echo jsPesan(base_url()."/".url_segment(1)."/".url_segment(2));
										}
									break;
								}
							?>
						</div>
					</div>
				  <!-- /.box -->
				  <p>
					<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
				  </p>
					<?php
				}
			?>
        
    </section>
    <!-- /.content -->