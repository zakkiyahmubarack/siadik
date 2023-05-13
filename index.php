<?php
session_start();
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
include "assets/config/koneksi.php";
include "assets/config/function.php";
include "assets/config/tgl_indo.php";
include "views/auto_delete.php";
$iden=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_identitas order by id_identitas DESC"));
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $iden['title_identitas'];?></title>
  <link rel="icon" href="<?php echo base_url()."/img/$iden[logo_identitas]";?>" type="image/x-icon">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url()."/assets/AdminLTE/bootstrap"; ?>/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url()."/assets/AdminLTE/bootstrap"; ?>/css/main.css">
 
  <link rel="stylesheet" href="<?php echo base_url()."/assets/AdminLTE/dist"; ?>/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo base_url()."/assets/AdminLTE/dist"; ?>/css/skins/skin-blue.min.css">
  <script src="<?php echo base_url()."/assets/AdminLTE/"; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="<?php echo base_url()."/assets/AdminLTE/"; ?>bootstrap/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url()."/assets/AdminLTE/"; ?>dist/js/app.min.js"></script>
  <script language="javascript">
    	function printDiv(divName) {
			 var printContents = document.getElementById(divName).innerHTML;
			 var originalContents = document.body.innerHTML;
			 document.body.innerHTML = printContents;
			 
			 
			 window.print();
			 document.body.innerHTML = originalContents;
	   }
    </script>
<script type="text/javascript">
var auto_refresh = setInterval(
function ()
{
$('#div_whats').load('<?php echo base_url();?>/views/auto_whatsapp.php').fadeIn("fast");
$('#div_autoresponse').load('<?php echo base_url();?>/views/autoresponse.php').fadeIn("fast");
}, 15000); // refresh every 10000 milliseconds
setInterval(
	function ()
	{
	$('#status-gammu').load('<?php echo base_url();?>/views/status-gammu.php').fadeIn("fast");
  $('#absen').load('<?php echo base_url();?>/views/absen_auto.php').fadeIn("fast");
	},
1000);
</script>
 <script>
    $(window).bind("load", function() {
		$('#loading-overlay').fadeOut();
		$('#halaman').fadeIn();
	});
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="div_autoresponse"></div>
<div id="div_whats"></div>
<div id="bell_auto"></div>
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="<?php echo base_url();?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">SIADIK</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SIADIK</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation" style="vertical-align:center;">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
		<ul class="nav navbar-nav">
              <li><a href="#"><h4><?php echo $iden['title_identitas'];?></h4></a></li>
              <li><a href="#"><span id="status-gammu"></span></a></li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel" style="text-align:center">
          <img src="<?php echo base_url()."/img/$iden[logo_identitas]";?>" class="img-circle img-responsive" alt="User Image">
      </div>


      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">SIADIK CONTENT</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="<?php echo base_url();?>"><i class="glyphicon glyphicon-home"></i> <span>Home</span></a></li>
        <li><a href="<?php echo base_url();?>/setting"><i class="glyphicon glyphicon-cog"></i> <span>Setting</span></a></li>
        <li><a href="<?php echo base_url();?>/password"><i class="glyphicon glyphicon-lock"></i> <span>Password</span></a></li>
        <li><a href="<?php echo base_url();?>/jadwal"><i class="glyphicon glyphicon-time"></i> <span>Jam Kerja</span></a></li>
		 <li class="treeview">
          <a href="#"><i class="glyphicon glyphicon-inbox"></i> <span>Setup Mesin</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url();?>/mesin"><i class="glyphicon glyphicon-inbox"></i> <span>Data Mesin</span></a></li>
			<li><a href="<?php echo base_url();?>/user"><i class="glyphicon glyphicon-user"></i> <span>User</span></a></li>
			<li><a href="<?php echo base_url();?>/sidik-jari"><i class="glyphicon glyphicon-hand-up"></i> <span>Sidik Jari</span></a></li>
			
			<li><a href="<?php echo base_url();?>/sys-time"><i class="glyphicon glyphicon-time"></i> <span>Syncronize Time</span></a></li>
			<li><a href="<?php echo base_url();?>/clear-log"><i class="glyphicon glyphicon-trash"></i> <span>Clear Log</span></a></li>
          </ul>
        </li>
        
   <li><a href="<?php echo base_url();?>/no-telp"><i class="glyphicon glyphicon-phone"></i> <span>Nomor HP</span></a></li>
		 <li><a href="<?php echo base_url();?>/group"><i class="glyphicon glyphicon-folder-open"></i> <span>Group</span></a></li>
        <li><a href="<?php echo base_url();?>/pengelolaan-group"><i class="glyphicon glyphicon-user"></i> <span>Pengelolaan Group</span></a></li>
        <li><a href="<?php echo base_url();?>/log-absen"><i class="glyphicon glyphicon-book"></i> <span>Log Absen</span></a></li>
		<li class="treeview">
          <a href="#"><i class="glyphicon glyphicon-signal"></i> <span>Report Absen</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
			<li><a href="<?php echo base_url();?>/report-absen"><i class="glyphicon glyphicon-book"></i> <span>Jumlah Jam</span></a></li>
			<li><a href="<?php echo base_url();?>/report-absen-status"><i class="glyphicon glyphicon-check"></i> <span>Status Absen</span></a></li>
			<li><a href="<?php echo base_url();?>/report-absen-waktu"><i class="glyphicon glyphicon-time"></i> <span>Waktu Absen</span></a></li>
			<li><a href="<?php echo base_url();?>/report-terlambat"><i class="glyphicon glyphicon-time"></i> <span>Terlambat</span></a></li>
			</ul>
        </li>
		<li class="treeview">
          <a href="#"><i class="glyphicon glyphicon-envelope"></i> <span>WhatsApp</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
			<li><a href="<?php echo base_url();?>/p-tulis"><i class="glyphicon glyphicon-envelope"></i> <span>Tulis Pesan</span></a></li>
			<li><a href="<?php echo base_url();?>/p-terkirim"><i class="glyphicon glyphicon-envelope"></i> <span>Pesan Terkirim</span></a></li>
		</ul>
        </li>
        <li><a href="<?php echo base_url();?>/help"><i class="glyphicon glyphicon-star"></i> <span>Bantuan</span></a></li>
        <li><a href="<?php echo base_url();?>/logout"><i class="glyphicon glyphicon glyphicon-off"></i> <span>Keluar</span></a></li>
        
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php
	//echo url_segment(1);
		if(empty($_SESSION['username']) and empty($_SESSION['password'])){
			include "views/home.php";
		}
		else{
			$admin=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_admin where user_admin='$_SESSION[username]' and pass_admin='$_SESSION[password]'"));
			switch(url_segment(1)){
				default			: include "views/home.php"; break;
				case 'logout'	: include "views/logout.php"; break;
				case 'setting'	: include "views/setting.php"; break;
				case 'password'	: include "views/password.php"; break;
				case 'mesin'	: include "views/mesin.php"; break;
				case 'user'		: include "views/user.php"; break;
				case 'sidik-jari'	: include "views/sidik-jari.php"; break;
				case 'foto-user'	: include "views/foto-user.php"; break;
				case 'no-telp'	: include "views/no-telp.php"; break;
				case 'group'	: include "views/group.php"; break;
				case 'pengelolaan-group'	: include "views/pengelolaan-group.php"; break;
				case 'sys-time'	: include "views/sys-time.php"; break;
				case 'clear-log'	: include "views/clear-log.php"; break;
				case 'p-keluar'	: include "views/p-keluar.php"; break;
				case 'p-masuk'	: include "views/p-masuk.php"; break;
				case 'p-terkirim'	: include "views/p-terkirim.php"; break;
				case 'p-tulis'	: include "views/p-tulis.php"; break;
				case 'log-absen'	: include "views/log-absen.php"; break;
				case 'report-absen'	: include "views/report-absen.php"; break;
				case 'report-absen-status'	: include "views/report-absen-status.php"; break;
				case 'report-absen-waktu'	: include "views/report-absen-waktu.php"; break;
				case 'report-terlambat'	: include "views/report-terlambat.php"; break;
				case 'grafik-sms'	: include "views/grafik-sms.php"; break;
				case 'help'	: include "views/help.php"; break;
				case 'bell'	: include "views/bell.php"; break;
        case 'jadwal' : include "views/jadwal.php"; break;
				case 'report-individu'	: include "views/report-individu.php"; break;
				case 'qr_whatsapp'	: include "views/qr_whatsapp.php"; break;
			}
		}
	?>
  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<audio class="bell" autoplay="true">
</audio>

<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url()."/assets/AdminLTE/"; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url()."/assets/AdminLTE/"; ?>bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()."/assets/AdminLTE/"; ?>dist/js/app.min.js"></script>

</body>
</html>
