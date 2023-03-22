<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Setup Group
        
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
									<th>NO.</td>
									<th>NAMA GROUP</td>
									<th><a href="<?php echo base_url()."/".url_segment(1);?>/add" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Buat Baru</a></td>
								  </tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_group order by status_group DESC, nama_group ASC");
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>".$no++.".</td>
										<td>$d2[nama_group]</td>
										<td>
											".link_tampil($d2['status_group'],base_url()."/".url_segment(1)."/tampil/$d2[id_group]")."
											".link_edit(base_url()."/".url_segment(1)."/edit/$d2[id_group]")."
											".link_hapus(base_url()."/".url_segment(1)."/hapus/$d2[id_group]")."
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
						<p>Nama:<br><input type="text" name="nama" placeholder="Nama" class="form-control" required="true"></p>
						<p>Tampilkan?:<br>
							<label>
							  <input type='radio' name='status' id='status' value='0' required="true">
							  Tidak</label>
							  <label>
							  <input name='status' type='radio' id='status' value='1'  required="true" checked>
								Ya</label>
						</p>
						<p>
							<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
						</p>
					</form>
					<?php
					break;
					case 'simpan' : 
						if(empty($_POST['nama'])){
							echo jsPesan('Lengkapi form dengan benar',base_url()."/".url_segment(1)."/add");
						}
						else{
							$h3=mysqli_query($koneksi,"insert into tbl_group (nama_group,status_group) 
											values ('$_POST[nama]','$_POST[status]')");
							if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
							}
							else{
								echo jsPesan("Erorr Query",base_url()."/".url_segment(1)."/add");
							}
						}
					break;
					case 'edit' : 
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_group where id_group='".url_segment(3)."'"));
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/update";?>" class="form">
						<p>Nama:<br><input type="text" name="nama" placeholder="Nama" class="form-control" required="true" value="<?php echo $data['nama_group'];?>"></p>
						<p>Tampilkan?:<br>
							<label>
							  <input type='radio' name='status' id='status' value='0' required="true" <?php if($data['status_group'] == 0){ echo "checked";}?>>
							  Tidak</label>
							  <label>
							  <input name='status' type='radio' id='status' value='1'  required="true" <?php if($data['status_group'] == 1){ echo "checked";}?>>
								Ya</label>
						</p>
						<p>
							<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
							<input type="hidden" name="id" value="<?php echo $data['id_group'];?>">
						</p>
					</form>
					<?php
					break;
					case 'update' : 
						if(empty($_POST['nama'])){
							echo jsPesan('Lengkapi form dengan benar',base_url()."/".url_segment(1)."/edit/$_POST[id]");
						}
						else{
							$h3=mysqli_query($koneksi,"UPDATE tbl_group set
											nama_group='$_POST[nama]',
											status_group='$_POST[status]'
											where id_group='$_POST[id]'");
							if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
							}
							else{
								echo jsPesan("Error Query",base_url()."/".url_segment(1)."/edit/$_POST[id]");
							}
						}
					break;
					case 'hapus' : 
						$h3=mysqli_query($koneksi,"DELETE FROM tbl_group where id_group='".url_segment(3)."'");
						if($h3 == TRUE){
							echo js(base_url()."/".url_segment(1));
						}
						else{
							echo jsPesan(base_url()."/".url_segment(1));
						}
					break;
					case 'tampil' : 
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_group where id_group='".url_segment(3)."'"));
						if($data['status_group'] == 1){$status="0";}
						elseif($data['status_group'] == 0){$status="1";}
						$h3=mysqli_query($koneksi,"UPDATE tbl_group SET status_group='$status' where id_group='".url_segment(3)."'");
						if($h3 == TRUE){
							echo js(base_url()."/".url_segment(1));
						}
						else{
							echo jsPesan(base_url()."/".url_segment(1));
						}
					break;
				}
			?>
         </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->