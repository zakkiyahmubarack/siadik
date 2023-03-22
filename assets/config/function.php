<?php
function base_url(){
	return "http://localhost/siadik";
}
function js($data){
	return "<script>document.location='$data';</script>";
}
function jsPesan($pesan,$data){
	return "<script>alert('$pesan'); document.location='$data';</script>";
}
function url_segment($segment = ""){
	$url="http://".$_SERVER["SERVER_NAME"]."".$_SERVER['REQUEST_URI'];
	$base = str_replace(base_url(),"",$url);
	$pecah  = explode("/",$base);
	if(empty($pecah[$segment])){ $dataku="";}
	else{$dataku=$pecah[$segment];}
	return $dataku;
	
}
function status_absen($data = ""){
	if($data == 0){ $status="MASUK";}
	elseif($data == 1){ $status="PULANG";}
	return $status;
}
function datediff($tgl1, $tgl2){
$tgl1 = strtotime($tgl1);
$tgl2 = strtotime($tgl2);
$diff_secs = abs($tgl1 - $tgl2);
$base_year = min(date("Y", $tgl1), date("Y", $tgl2));
$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
return array( "years" => date("Y", $diff) - $base_year, "months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1, "months" => date("n", $diff) - 1, "days_total" => floor($diff_secs / (3600 * 24)), "days" => date("j", $diff) - 1, "hours_total" => floor($diff_secs / 3600), "hours" => date("G", $diff), "minutes_total" => floor($diff_secs / 60), "minutes" => (int) date("i", $diff), "seconds_total" => $diff_secs, "seconds" => (int) date("s", $diff) );
}
function password($data){
	$password=md5(md5(md5("$data")));
	return $password;
}
function nama_file($nama_file){
	$nama_file 	 = str_replace (' ', '_', $nama_file);
	$nama_file   = str_replace ('@', '', $nama_file);
	$w = date('dmYHis');
	return "".$w."-".$nama_file;
}
function Parse_Data($data,$p1,$p2){
	$data=" ".$data;
	$hasil="";
	$awal=strpos($data,$p1);
	if($awal!=""){
		$akhir=strpos(strstr($data,$p1),$p2);
		if($akhir!=""){
			$hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
		}
	}
	return $hasil;	
}
function get_modem_status($status, $tolerant)
{
	
	// convert the date to unix timestamp
	$st = explode(" ", $status);
	$date = $st[0];
	$time = $st[1];
	$tgl = explode('-', $date);
	$year = $tgl[0];
	$month = $tgl[1];
	$day = $tgl[2];
	$jam = explode(':', $time);
	$hour = $jam[0];
	$minute = $jam[1];
	$second = $jam[2];
	/*
	list($date, $time) = explode(' ', $status);
	list($year, $month, $day) = explode('-', $date);
	list($hour, $minute, $second) = explode(':', $time);
	*/
	$timestamp = mktime($hour, $minute + $tolerant, $second, $month, $day, $year);
	$now = time();

	//$diff = abs($now-$timestamp);
	if($timestamp > $now)
	{
		return "connect";
	}
	else 
	{
		return "disconnect";
	}
}
function link_pesan($url){
	return "<a href='$url' class='btn btn-sm btn-success'><i class='glyphicon glyphicon-envelope'></i></a>";
}
function link_edit($url){
	return "<a href='$url' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-edit'></i></a>";
}
function link_hapus($url){
	return "<a href='$url' class='btn btn-sm btn-danger' onclick=\"return confirm('Anda yakin ingin menghapusnya?');\"><i class='glyphicon glyphicon-trash'></i></a>";
}
function link_tampil($data="",$url=""){
	if($data == 0){ return "<a href='$url' class='btn btn-sm btn-default' title='Status Tampil'><i class='glyphicon glyphicon-eye-close'></i></a>";}
	elseif($data == 1){ return "<a href='$url' class='btn btn-sm btn-success' title='Status Tampil'><i class='glyphicon glyphicon-eye-open'></i></a>";}
	
}
function potong_text($tulisan, $count) {
            $count = $count;
               $length = strlen($tulisan);
                  if($length>$count) {
                     $i = $count;
                     while($i != 0) {
                        if(substr($tulisan,$i,1) == " ") {
                           break;
                        } else {
                        }
                     $i = $i-1;
                    }
                 $tulisan = substr($tulisan,0,$i);
                 $tulisan = $tulisan;
            } else {
               $tulisan = $tulisan;
            }
           //$tulisan = strtolower($tulisan);
           return $tulisan;
}
function url_judul($judul){
	$judul=strip_tags($judul);
	$judul = trim($judul);
	$judul = str_replace("?","",$judul);
	$judul = str_replace("'","",$judul);
	//$judul = str_replace(",","",$judul);
	$judul = str_replace("/","",$judul);
	$judul = str_replace(":","",$judul);
	$judul = str_replace(";","",$judul);
	$judul = str_replace("@","",$judul);
	$judul = str_replace(".","",$judul);
	//$judul = str_replace(",","",$judul);
	//$judul = str_replace("&","",$judul);
	$judul = str_replace("  "," ",$judul);
	$judul = str_replace(" ","-",$judul);
	return $judul;
}
function kembali_judul($judul){
	$judul = str_replace("-"," ",$judul);
	//$judul = str_replace("&","",$judul);
	$judul = str_replace("/"," ",$judul);
	$judul = str_replace("'","",$judul);
	$judul = str_replace("  "," ",$judul);
	return $judul;
}
function halaman($jmldata,$batas,$page,$hal){
	
						  $jmlhalaman = ceil($jmldata/$batas);
							$file = $page;
							$guri="hal";
							if($jmldata > $batas)
							{
								echo"<nav>
										<ul class=\"pagination\">";
								//link ke halaman sebelumnya (prev)
								if($hal > 1) {
									$previous=$hal-1;
									echo "<li><a href='$file/$guri/1'><< First</a></li>
										<li><a href='$file/$guri/$previous'>< Previous</a></li>";
								}
								//tampilkan link halaman
								//angka awal
								$angka=($hal > 3 ? " <li class=\"disable\"><a href='#'>...</a></li> " : " "); //ternary operator
								for($i=$hal-2;$i<$hal;$i++) {
									if($i < 1)
										continue;
									$angka .= "<li><a href='$file/$guri/$i'>$i</a></li> ";
								}
								//angka tengah
								$angka .=" <li class=\"active\"><a href='#'>$hal</a></li> ";
								for($i=$hal+1;$i<($hal+3);$i++) {
									if($i >$jmlhalaman)
										break;
									$angka .= "<li><a href='$file/$guri/$i'>$i</a></li> ";
								}
								//angka akhir
								$angka .= ($hal+2<$jmlhalaman ? "<li class=\"disable\"><a href='#'>...</a></li><li class=\"disable\"><a href='$file/$guri/$jmlhalaman'>$jmlhalaman</a></li>" : "");
								//cetak seluruh angka
								echo "$angka";
								//link kehalaman berikutnya
								if($hal < $jmlhalaman) {
									$next=$hal+1;
									echo "<li><a href='$file/$guri/$next'>Next ></a></li>
									<li><a href='$file/$guri/$jmlhalaman'>Last >></a></li>";
								}
								echo "<li class=\"disable\"><a href='#'>Total : $jmldata Data</a></li>
								</ul>
							</nav>";
								
							}
}

?>
