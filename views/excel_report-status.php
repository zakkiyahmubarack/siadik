<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Excel-Report_Absen_Status-".date("HisdmY").".xls");
include "../assets/config/koneksi.php";
include "../assets/config/function.php";
include "../assets/config/tgl_indo.php";
$group=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_group where id_group='$_GET[group]'"));
						echo"<h4>DAFTAR HADIR ".strtoupper($group['nama_group'])." BULAN ".strtoupper(get_bulan($_GET['bulan']))." TAHUN $_GET[tahun]</h4>";
						$q_absen=mysqli_query($koneksi,"SELECT * FROM absen a 
											left join tbl_user z on a.kode_user=z.kode_user
											left join tbl_member b on z.kode_user=b.kode_user 
											left join tbl_group c on b.id_group=c.id_group
											where b.id_group='$_GET[group]' and 
											a.tgl_absen like '$_GET[tahun]-$_GET[bulan]%' 
											group by a.tgl_absen order by a.tgl_absen ASC");
						$jum_t=mysqli_num_rows($q_absen);
						if($jum_t >= 1){
							while($d_absen=mysqli_fetch_assoc($q_absen)){
								$pecah=explode("-",$d_absen['tgl_absen']);
								$tanggal[]="$pecah[2]";
						}
							
							echo"<table width='100%' cellspacing='0' cellpadding='2' border='1'>
							  <tr>
								<td rowspan='2' align='center'>No.</td>
								<td rowspan='2' align='center'>KODE</td>
								<td rowspan='2' align='center'>NAMA</td>
								<td colspan='".$jum_t."' align='center'>TANGGAL</td>
								<td colspan='3' align='center'>TOTAL</td>
							  </tr>";
							  echo"<tr>";
								foreach($tanggal as $data_t){
									echo"<td align='center'>$data_t</td>";
								}
							  echo"<td align='center'>A</td>
								  <td align='center'>H</td>
								  <td align='center'>A+H</td>
								 </tr>";
							  $query=mysqli_query($koneksi,"select * from tbl_member a 
									left join tbl_group b on a.id_group=b.id_group
									left join tbl_user c on a.kode_user=c.kode_user
									 WHERE a.id_group='$_GET[group]'
									order by c.nama_user ASC");
								$nom=1;
								
								while($data=mysqli_fetch_assoc($query)){
								$hadir=0;
								$alfa=0;
									$user=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_user WHERE kode_user='$data[kode_user]'"));
								  echo"<tr>
									<td>".$nom++."</td>
									<td>$user[kode_user]</td>
									<td>$user[nama_user]</td>";
									foreach($tanggal as $data_t){
										$ket_ab=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * from absen 
																				where kode_user='$data[kode_user]' 
																				and tgl_absen = '$_GET[tahun]-$_GET[bulan]-$data_t'
																				and status_absen='0'"));
										$ket_ab2=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * from absen 
																				where kode_user='$data[kode_user]' 
																				and tgl_absen ='$_GET[tahun]-$_GET[bulan]-$data_t'
																				and status_absen='1'"));
									if(!empty($ket_ab['waktu_absen']) and !empty($ket_ab2['waktu_absen'])){
											$isi = "H"; 
											$hadir++;
									}
									else{
										$isi="A";
										$alfa++;										
									}
									echo"<td align='center'>".$isi."</td>";
								}
								$total=$hadir+$alfa;
								echo"<td align='center'>$alfa</td>";
								echo"<td align='center'>$hadir</td>";
								echo"<td align='center'>$total</td>";
							  echo"</tr>";
							  }
							echo"</table>";
							}
							else{
								echo "Data tidak ditemukan, Silahkan <a href='".base_url()."/".url_segment(2)."'>kembali</a>";
							}
?>
	