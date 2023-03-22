<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Report Absen Berdasarkan Jumlah Terlambat
        
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
					<form name="form1" method="post" action="<?php echo base_url()."/".url_segment(1);?>/cari" class="form" id="formMhs">
					<h3>Rekap Bulanan</h3>
					<p>Bulan:<br>
					<div class="row">
					<div class="col-sm-7">
						<select name='bulan' id='bulan' class="form-control">";
							<option value='<?php echo date("m");?>'><?php echo get_bulan(date("m"));?></option>";
							<?php
							for($i=1;$i<=12;$i++){
								if($i<=9){ $ii="0$i";}
								else{$ii="$i";}
								if($ii <> date("m")){echo"<option value='$ii'>".get_bulan($ii)."</option>";}
							}
							?>
						</select>
					</div>
					<div class="col-sm-5">
					<select name='tahun' id='tahun' class="form-control">";
						<?php
						$thn=date("Y");
						$thn_belakang=$thn - 5;
						for($is=$thn;$is >= $thn_belakang; $is--){
							echo"<option value='$is'>$is</option>";
						}
						?>
					</select>
					</div>
					</div></p>
					<p>Pilih Group:<br>
					<select name='group' id='group' class="form-control">";
					<?php
						$h2=mysqli_query($koneksi,"select * from tbl_group where status_group='1' order by nama_group ASC");
						while($d2=mysqli_fetch_assoc($h2)){
							echo"<option value='$d2[id_group]'>".$d2['nama_group']."</option>";
					 }
					 ?>
					</select></p>
						<p>
						<a href="<?php echo base_url()."/".url_segment(1);?>" class="btn btn-warning">Kembali</a>
							<input type="submit" name="submit" id="button" value="Lihat Data" class="btn btn-success">
						</p>
				</form>
					<?php
					break;
					case "cari" :
						echo"<div id='print-area'>";
						$group=mysqli_fetch_assoc(mysqli_query($koneksi,"select * from tbl_group where id_group='$_POST[group]'"));
						echo"<h4>DAFTAR HADIR KETERLAMBATAN ".strtoupper($group['nama_group'])." BULAN ".strtoupper(get_bulan($_POST['bulan']))." TAHUN $_POST[tahun]</h4>";
						$q_absen=mysqli_query($koneksi,"SELECT * FROM absen a 
											left join tbl_user z on a.kode_user=z.kode_user
											left join tbl_member b on z.kode_user=b.kode_user 
											left join tbl_group c on b.id_group=c.id_group
											where b.id_group='$_POST[group]' and 
											a.tgl_absen like '$_POST[tahun]-$_POST[bulan]%' 
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
								<td  align='center'>TOTAL</td>
							  </tr>";
							  echo"<tr>";
								foreach($tanggal as $data_t){
									echo"<td align='center'>$data_t</td>";
								}
							  echo"<td align='center'>MENIT</td>
								 </tr>";
							  $query=mysqli_query($koneksi,"select * from tbl_member a 
									left join tbl_group b on a.id_group=b.id_group
									left join tbl_user c on a.kode_user=c.kode_user
									 WHERE a.id_group='$_POST[group]'
									order by c.nama_user ASC");
								$nom=1;
								
								while($data=mysqli_fetch_assoc($query)){
									$user=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM tbl_user WHERE kode_user='$data[kode_user]'"));
								  echo"<tr>
									<td>".$nom++."</td>
									<td>$user[kode_user]</td>
									<td>$user[nama_user]</td>";
									foreach($tanggal as $data_t){
										$ket_ab=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * from absen 
																				where kode_user='$data[kode_user]' 
																				and tgl_absen = '$_POST[tahun]-$_POST[bulan]-$data_t'
																				and status_absen='0'"));
										$ket_ab2=mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * from absen 
																				where kode_user='$data[kode_user]' 
																				and tgl_absen ='$_POST[tahun]-$_POST[bulan]-$data_t'
																				and status_absen='1'"));
									//terlambat
									$terlambat=$ket_ab['telat_absen'];
									$data_terlambat[$nom][]=$ket_ab['telat_absen'];
									/*$selisih=datediff("$ket_ab[waktu_absen]", "$ket_ab2[waktu_absen]");
									if(!empty($ket_ab['waktu_absen']) and !empty($ket_ab2['waktu_absen'])){
											$isi = $selisih['minutes_total']; 
											$isi_data[$nom][] = $selisih['minutes_total']; 
									}
									else{
										$isi=0;
										$isi_data[$nom][] = 0;								
									}
									*/
									echo"<td align='center'>".$terlambat."</td>";
								}
								
								//$jum_hari= array_sum($isi_data[$nom]) / $iden['menit_identitas'];
								echo"<td align='center'>".array_sum($data_terlambat[$nom])."</td>";
							  echo"</tr>";
							  }
							echo"</table>";
							echo"</div>";
							echo "<p><br>
								<a href='".base_url()."/views/excel_report-terlambat.php?bulan=$_POST[bulan]&tahun=$_POST[tahun]&group=$_POST[group]' class='btn btn-success'><span class='glyphicon glyphicon-save-file'></span> Export Excel</a>
								<button onClick=\"printDiv('print-area')\" class='btn btn-success'><span class='glyphicon glyphicon-print'></span> Print</button>
								<a href='".base_url()."/".url_segment(1)."' class='btn btn-danger'><span class='glyphicon glyphicon-menu-left'></span> Kembali</a>
							</p>";
							}
							else{
								echo "Data tidak ditemukan, Silahkan <a href='".base_url()."/".url_segment(1)."'>kembali</a>";
							}
					break;
					
					
				}
			?>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->