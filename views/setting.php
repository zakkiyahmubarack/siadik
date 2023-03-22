<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Setting
        
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
							<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/save";?>" class="form" enctype="multipart/form-data">
								<p>Nama:<br><input type="text" name="nama"  id="nama" placeholder="SMK Plus Mekargading"  class="form-control" value="<?php echo "$iden[nama_identitas]";?>" required="true"></p>
								<p>Jumlah Jam Kerja dalam satu hari (menit):<br><input type="number" name="menit"  id="menit" placeholder="Jumlah Jam Kerja dalam satu hari (menit)" class="form-control" value="<?php echo "$iden[menit_identitas]";?>" required="true"></p>
								<p>Title Aplikasi:<br><input type="text" name="title"  id="title" placeholder="Title Aplikasi" class="form-control" value="<?php echo "$iden[title_identitas]";?>" required="true"></p>
								<p>Alamat:<br><input type="text" name="alamat"  id="alamat" placeholder="Alamat Lengkap Sekolah" class="form-control" value="<?php echo "$iden[alamat_identitas]";?>" required="true"></p>
								<p>Logo:<br>
								<img src="<?php echo base_url()."/img/$iden[logo_identitas]";?>" height="100"><br>
								<input type="file" name="files"  id="files"></p>
									<p><input type="hidden" name="lama" id="lama" value="<?php echo "$iden[logo_identitas]";?>">
									<input type="submit" name="submit" id="button" value="Simpan" class="btn btn-success"></p>
							</form>
						<?php
					break;
					case 'save' :
						$nama_file=$_FILES['files']['name'];
						$lokasi_file=$_FILES['files']['tmp_name'];
						if(!empty($nama_file)){
							$nama=str_replace(" ","_",$nama_file);
							$nama=date("HisYmd")."@".$nama;
							$h3=mysqli_query($koneksi,"update tbl_identitas set 
											nama_identitas='$_POST[nama]',
											title_identitas='$_POST[title]',
											alamat_identitas='$_POST[alamat]',
											menit_identitas='$_POST[menit]',
											logo_identitas='$nama'
											where id_identitas='$iden[id_identitas]'");
							unlink("img/$_POST[lama]");
							move_uploaded_file($lokasi_file,"img/$nama");
						}
						else{
							$h3=mysqli_query($koneksi,"update tbl_identitas set 
											nama_identitas='$_POST[nama]',
											title_identitas='$_POST[title]',
											alamat_identitas='$_POST[alamat]',
											menit_identitas='$_POST[menit]'
											where id_identitas='$iden[id_identitas]'");
						}
							if($h3 == TRUE){
								echo js(base_url()."/".url_segment(1));
							}
							else{
								echo jsPesan("Error",base_url()."/".url_segment(1));
							}
					break;
				}
			?>
         </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->