<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Jadwal Jam Kerja
        
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
								<table class="table table-striped table-bordered" >
								  <tr>
									<th>NO.</td>
									<th>JAM</td>
									<th>MULAI</td>
									<th>BERHENTI</td>
									<th>TOLERANSI TERLAMBAT</td>
									<th></td>
								  </tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_jadwal order by kode_jadwal ASC");
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>".$no++.".</td>
										<td>$d2[nama_jadwal]</td>
										<td>$d2[mulai_jadwal]</td>
										<td>$d2[berhenti_jadwal]</td>
										<td>$d2[toleransi_jadwal]</td>
										<td>
											".link_edit(base_url()."/".url_segment(1)."/edit/$d2[kode_jadwal]")."
										</td>
									  </tr>";
									 }
								  ?>
								</table>
						<?php
					break;
					case 'edit' : 
						$data=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_jadwal where kode_jadwal='".url_segment(3)."'"));
					?>
						<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/update";?>" class="form" enctype="multipart/form-data">
						<p>Nama:<br><input type="text" name="nama" placeholder="Nama" class="form-control" required="true" value="<?php echo $data['nama_jadwal']; ?>"></p>
						<p>Mulai:<br><input type="time" name="mulai" placeholder="Mulai Scaning Mesin" class="form-control" required="true" value="<?php echo $data['mulai_jadwal']; ?>"></p>
						<p>Berhenti:<br><input type="time" name="berhenti" placeholder="Berhenti" class="form-control" required="true" value="<?php echo $data['berhenti_jadwal']; ?>"></p>
						<p>Toleransi:<br><input type="time" name="toleransi" placeholder="Stop Scaning Mesin" class="form-control" required="true" value="<?php echo $data['toleransi_jadwal']; ?>"></p>
						
							<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success">
							<input type="hidden" name="id" value="<?php echo $data['kode_jadwal'];?>">
						</p>
					</form>
					<?php
					break;
					case 'update' : 
							$rename=nama_file($nama_file);
							$h3=mysqli_query($koneksi,"update tbl_jadwal SET 
											nama_jadwal='$_POST[nama]',
											mulai_jadwal='$_POST[mulai]',
											berhenti_jadwal='$_POST[berhenti]',
											toleransi_jadwal='$_POST[toleransi]' 
											WHERE kode_jadwal='$_POST[id]'");
							if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
							}
							else{
								echo jsPesan("Error Query",base_url()."/".url_segment(1)."/edit/$_POST[id]");
							}
						
					break;
				}
			?>
         </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->