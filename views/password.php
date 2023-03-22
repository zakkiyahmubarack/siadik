<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Password Login
        
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
							<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1)."/save";?>" class="form" id="formMhs">
									<p>Password Lama:<br><input type="password" name="pass"  id="pass" placeholder="Password Lama"  class="form-control" required="true"></p>
									<p>Password Baru:<br><input type="password" name="baru"  id="baru" placeholder="Password Baru" class="form-control" required="true"></p>
									<p>Ulangi Password Baru:<br><input type="password" name="ulangi"  id="ulangi" placeholder="Ulangi Password Baru"  class="form-control" required="true"></p>
										<p><input type="submit" name="submit" id="button" value="Perbaharui Password" class="btn btn-success"></p>
								</form>
						<?php
					break;
					case 'save' :
						if(password($_POST['pass']) != $admin['pass_admin']){
							echo jsPesan('Password tidak cocok', base_url()."/".url_segment(1));
						}
						else{
							if($_POST['baru'] != $_POST['ulangi']){echo jsPesan('Password baru tidak cocok', base_url()."/".url_segment(1));}
							else{
								$h3=mysqli_query($koneksi,"update tbl_admin set pass_admin='".password($_POST['baru'])."'
												where id_admin='$admin[id_admin]'");
								if($h3 == TRUE){
									echo jsPesan("Sukses, password anda $_POST[baru]. Silahkan masuk kembali", base_url()."/logout");
								}
								else{
									echo jsPesan("Error", base_url()."/".url_segment(1));
								}
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