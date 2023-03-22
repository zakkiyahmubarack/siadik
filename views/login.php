<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Login
        
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
	<div class="row">
		<div class="col-xs-12 col-md-6">
		
		<?php
			if($_POST['submit'] == true){
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
		?>
		
	     <div class="box box-primary">
			<form name="form1" method="post" action="" class="form">
				<div class="box-header with-border">
				  <h3 class="box-title">Login Siadik v17</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<p>Username:<br>
						<input type="text" name="username" placeholder="Username" class="form-control" required="TRUE"></p>
					<p>Password:<br><input type="password" name="password" placeholder="Password" class="form-control" required="TRUE"></p>
				</div>
				<!-- /.box-body -->
				<div class="box-footer clearfix">
				  <input type="submit" name="submit" id="button" class="btn btn-success pull-right" value="Log In">
				</div>
			</form>
			</div>
			</div>
          </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->