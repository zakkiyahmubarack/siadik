<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pesan Terkirim
        
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
					<table border="0" cellspacing="0" cellpadding="5" class="table table-striped" >
					  <tr>
						<th>No</td>
						<th>Waktu</td>
						<th>Tujuan</td>
						<th>Pesan</td>
						<th>Terikirim</td>
						<th><a href='<?php echo base_url()."/".url_segment(1);?>/hapus-semua' class='btn btn-danger'>Hapus Semua</a></td>
					  </tr>
					  <?php
						$batas = 10;
						$hal = url_segment(3);
						if($hal == "") 
						{
							$posisi = 0;
							$hal = 1;
						}
						else 
						{
							$posisi = ($hal-1) * $batas;
						}
						$no=1;
						$h2=mysqli_query($koneksi,"SELECT * FROM pesan order by status_pesan DESC, tgl_pesan DESC limit $posisi,$batas");
						while($d2=mysqli_fetch_assoc($h2)){
							//if($d2['DestinationNumber'] <> "+6287744421420"){
								echo"<tr class='baris'>
								<td>".$no++."</td>
								<td>".tgl_angka($d2['tgl_pesan'])."</td>
								<td>$d2[nomor_pesan]</td>
								<td>".$d2['isi_pesan']."</td>
								<td>".$d2['status_pesan']."</td>
								<td>";
								if($d2['status_pesan'] == "N"){
									echo link_pesan(base_url()."/".url_segment(1)."/kirim/$d2[id_pesan]")." ";
								}
									echo  link_hapus(base_url()."/".url_segment(1)."/hapus/$d2[id_pesan]")."
								</td>
							  </tr>";
							//}
						 }
					  ?>
					</table>
				 <p>
					<?php 
						 $jmldata=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM pesan"));
						echo halaman($jmldata,$batas,base_url()."/".url_segment(1),$hal);
					?>
				 </p>
					<?php
					break;
					case "hapus" :
								$h3=mysqli_query($koneksi,"DELETE FROM pesan WHERE id_pesan='".url_segment(3)."'");
								if($h3 == TRUE){
										echo jsPesan("Sukses",base_url()."/".url_segment(1));
								}
								else{
										echo jsPesan("Gagal",base_url()."/".url_segment(1));
								}
					break;
					case "hapus-semua" :
								$h3=mysqli_query($koneksi,"DELETE FROM pesan");
								if($h3 == TRUE){
										echo jsPesan("Sukses",base_url()."/".url_segment(1));
								}
								else{
										echo jsPesan("Gagal",base_url()."/".url_segment(1));
								}
					break;
					case "kirim":

							$query=mysqli_query($koneksi,"SELECT * FROM pesan WHERE id_pesan='".url_segment(3)."'");
							$data=mysqli_fetch_assoc($query);
							$kode_iden = explode(";",$iden['kode_identitas']);
							$api_key   = $kode_iden[0]; // API KEY Anda
							$id_device = $kode_iden[1];
							$url   = 'https://api.watsap.id/send-message'; // URL API
							$no_hp = $data['nomor_pesan']; // No.HP yang dikirim (No.HP Penerima)
							$pesan = $data['isi_pesan']; // Pesan yang dikirim

							$curl = curl_init();
							curl_setopt($curl, CURLOPT_URL, $url);
							curl_setopt($curl, CURLOPT_HEADER, 0);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
							curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
							curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
							curl_setopt($curl, CURLOPT_TIMEOUT, 0); // batas waktu response
							curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
							curl_setopt($curl, CURLOPT_POST, 1);

							$data_post = [
							'id_device' => $id_device,
							'api-key' => $api_key,
							'no_hp'   => $no_hp,
							'pesan'   => $pesan
							];
							curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_post));
							curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
							$response = curl_exec($curl);
							$hasil = json_decode($response, true);
							//print $response;
							curl_close($curl);
							
							if($hasil['kode'] == "200"){
								mysqli_query($koneksi,"UPDATE pesan SET status_pesan='Y' WHERE id_pesan='".$data['id_pesan']."'");
								echo jsPesan("Sukses",base_url()."/".url_segment(1));
							}
							else{
										echo jsPesan("Gagal",base_url()."/".url_segment(1));
							}
							
							
							
						
					break;
					
					
				}
			?>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
