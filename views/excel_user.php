<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Excel-User-".date("HisdmY").".xls");
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
									</tr>
								  <?php
									$no=1;
									$h2=mysqli_query($koneksi,"select * from tbl_user a left join tbl_perangkat b on a.id_perangkat=b.id_perangkat order by a.kode_user ASC");
									while($d2=mysqli_fetch_assoc($h2)){
										echo"<tr class='baris'>
										<td>$d2[kode_user]</td>
										<td>$d2[nama_user]</td>
										<td>$d2[id_perangkat]</td>
										<td>$d2[nama_perangkat]</td>
									  </tr>";
									 }
								  ?>
								</table>