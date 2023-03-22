<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename='Excel-Pengelolaan-Group-".date("HisdmY").".xls'");
include "../assets/config/koneksi.php";
include "../assets/config/function.php";
include "../assets/config/tgl_indo.php";
?>
								<table border="1">
								  <tr>
									<th>KODE</td>
									<th>NAMA LENGKAP</td>
									</tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_member a 
													left join tbl_user b on a.kode_user=b.kode_user
													left join tbl_group c on a.id_group=c.id_group
													where a.id_group='$_GET[group]'
													order by a.kode_user ASC, b.nama_user ASC");
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>$d2[kode_user]</td>
										<td>$d2[nama_user]</td>
									  </tr>";
									 }
								  ?>
								</table>