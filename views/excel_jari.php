<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename='Excel-Jari-".date("HisdmY").".xls'");
include "../assets/config/koneksi.php";
include "../assets/config/function.php";
include "../assets/config/tgl_indo.php";
?>
								<table border="1">
								  <tr>
									<th>KODE</td>
									<th>NAMA LENGKAP</td>
									<th>KODE MESIN</td>
									<th>MESIN</td>
									<th>SIDIK JARI</td>
									</tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_jari a 
													left join  tbl_user b on a.kode_user=b.kode_user
													left join tbl_perangkat c on b.id_perangkat=c.id_perangkat");
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>$d2[kode_user]</td>
										<td>$d2[nama_user]</td>
										<td>$d2[id_perangkat]</td>
										<td>$d2[nama_perangkat]</td>
										<td>$d2[isi_jari]</td>
									  </tr>";
									 }
								  ?>
								</table>