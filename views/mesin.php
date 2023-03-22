<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mesin Sidik Jari
        
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
	  <div class="box box-primary">
	     <div class="box-body">
			<?php
			if(url_segment(2) == ""){echo js(base_url()."/".url_segment(1)."/home");}
				switch(url_segment(2)){
					case "home": 
						?>
								<table class="table table-striped table-bordered" >
								  <tr>
									<th>KODE</td>
									<th>NAMA</td>
									<th>IP ADDRESS</td>
									<th>KEY</td>
									<th><a href="<?php echo base_url()."/".url_segment(1);?>/add" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Buat Baru</a></td>
								  </tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_perangkat order by nama_perangkat ASC");
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>$d2[id_perangkat]</td>
										<td>$d2[nama_perangkat]</td>
										<td>$d2[ip_perangkat]</td>
										<td>$d2[key_perangkat]</td>
										<td>
											".link_edit(base_url()."/".url_segment(1)."/edit/$d2[id_perangkat]")."
											".link_hapus(base_url()."/".url_segment(1)."/hapus/$d2[id_perangkat]")."
										</td>
									  </tr>";
									 }
								  ?>
								</table>
						<?php
					break;
					case 'add' : 
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/simpan";?>" class="form">
						<p>Kode:<br><input type="text" name="kode" placeholder="Kode" class="form-control" required="true"></p>
						<p>Nama:<br><input type="text" name="nama" placeholder="Nama" class="form-control" required="true"></p>
						<p>IP:<br><input type="text" name="ip" placeholder="IP Address" class="form-control" required="true"></p>
						<p>Key:<br><input type="text" name="key" placeholder="Key" value="0" class="form-control" required="true"></p>
						<p>
							<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
						</p>
					</form>
					<?php
					break;
					case 'simpan' : 
						if(empty($_POST['nama']) || empty($_POST['ip'])){
							echo jsPesan('Lengkapi form dengan benar',base_url()."/".url_segment(1)."/add");
						}
						else{
							$h3=mysqli_query($koneksi,"insert into tbl_perangkat (id_perangkat,nama_perangkat,ip_perangkat,key_perangkat) 
											values ('$_POST[kode]','$_POST[nama]','$_POST[ip]','$_POST[key]')");
							if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
							}
							else{
								echo jsPesan("Erorr Query",base_url()."/".url_segment(1)."/add");
							}
						}
					break;
					case 'edit' : 
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_perangkat where id_perangkat='".url_segment(3)."'"));
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/update";?>" class="form">
						<input type="hidden" name="id" value="<?php echo $data['id_perangkat'];?>">
						<p>Kode:<br><input type="text" name="kode" placeholder="Kode" value="<?php echo $data['id_perangkat'];?>" class="form-control" disabled="true"></p>
						<p>Nama:<br><input type="text" name="nama" placeholder="Nama" value="<?php echo $data['nama_perangkat'];?>" class="form-control" required="true"></p>
						<p>IP:<br><input type="text" name="ip" placeholder="IP Address" value="<?php echo $data['ip_perangkat'];?>" class="form-control" required="true"></p>
						<p>Key:<br><input type="text" name="key" placeholder="Key" value="<?php echo $data['key_perangkat'];?>" class="form-control" required="true"></p>
						<p>
							<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
						</p>
					</form>
					<?php
					break;
					case 'update' : 
						if(empty($_POST['nama']) || empty($_POST['ip'])){
							echo jsPesan('Lengkapi form dengan benar',base_url()."/".url_segment(1)."/edit/$_POST[id]");
						}
						else{
							$h3=mysqli_query($koneksi,"UPDATE tbl_perangkat set
											nama_perangkat='$_POST[nama]',
											ip_perangkat='$_POST[ip]',
											key_perangkat='$_POST[key]' 
											where id_perangkat='$_POST[id]'");
							if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
							}
							else{
								echo jsPesan("Error Query",base_url()."/".url_segment(1)."/edit/$_POST[id]");
							}
						}
					break;
					case 'hapus' : 
						$h3=mysqli_query($koneksi,"DELETE FROM tbl_perangkat where id_perangkat='".url_segment(3)."'");
						if($h3 == TRUE){
							echo js(base_url()."/".url_segment(1));
						}
						else{
							echo jsPesan('Error',base_url()."/".url_segment(1));
						}
					break;
				}
			?>
         </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->