<?php
	if(url_segment(2) == "status"){
		$cek_mesin=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_perangkat WHERE id_perangkat='".url_segment(3)."'"));
		if($cek_mesin['status_perangkat'] == "1"){$data="0";}else{$data="1";}
		$query=mysqli_query($koneksi,"UPDATE tbl_perangkat set status_perangkat='$data' WHERE id_perangkat='$cek_mesin[id_perangkat]'");
		if($query == TRUE){ echo js(base_url());}
		else{echo js(base_url());}
	}
?>
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h3 style="margin:0">PROGRAM PENGABDIAN KEPADA MASYARAKAT (PkM)</h3>
	  <h3 style="margin:0">POLITEKNIK NEGERI INDRAMAYU (POLINDRA)</h3>
    </section>

    <!-- Main content -->
    <section class="content">
	
	<?php
	if(empty($_SESSION['username']) and empty($_SESSION['password'])){
			if(isset($_POST['submit'])){
				if($_POST['submit']=="Log In"){
				if(empty($_POST['username']) || empty($_POST['password'])){
					echo "<div class='alert alert-warning'>Silahkan lengkapi form dengan benar'</div>";
				}
				else{
					$h=mysqli_query($koneksi,"select * from tbl_admin where user_admin='$_POST[username]' and pass_admin='".password($_POST['password'])."'");
					$jum=mysqli_num_rows($h);
					if($jum == "1"){
						$data=mysqli_fetch_assoc($h);
						//session_register('username');
						//session_register('password');
						$_SESSION['username']=$data['user_admin'];
						$_SESSION['password']=$data['pass_admin'];
						echo js(base_url());
					}
					else{
						echo "<div class='alert alert-danger'>Belum terdaftar</div>";
					}
				}
				}
			}
	}
		?>
	
	  <div class="box box-primary">
	  
	  <div class="box-header">
		<div class="row">
			<div class="col-xs-8">
			<h3>Log Absensi Terakhir</h3></div>
			<div class="col-xs-4">
			<?php if(empty($_SESSION['username']) and empty($_SESSION['password'])){ ?>
			<a href="#" class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#login">Log In</a>
		<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				<form name="form1" method="post" action="" class="form">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="login">Login Siadik v17</h4>
				  </div>
				  <div class="modal-body">
				  <p>Username:<br>
						<input type="text" name="username" placeholder="Username" class="form-control" required="TRUE"></p>
					<p>Password:<br><input type="password" name="password" placeholder="Password" class="form-control" required="TRUE"></p>
				</div>
				  <div class="modal-footer">
					<input type="submit" name="submit" id="button" class="btn btn-success pull-left" value="Log In"> 
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  </div>
				  </form>
				</div>
			  </div>
			</div>
			
			</div>
			<?php } ?>
		</div>
	  </div>
	     <div class="box-body">
			<div id="absen"></div>
         </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->