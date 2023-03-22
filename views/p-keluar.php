<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pesan Keluar
        
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
					<table border="0" cellspacing="0" cellpadding="5" class="table table-striped" >
					  <tr>
						<th>No</td>
						<th>Waktu</td>
						<th>Tujuan</td>
						<th>Pesan</td>
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
						$h2=mysqli_query($koneksi,"select * from outbox order by UpdatedInDB DESC limit $posisi,$batas");
						while($d2=mysqli_fetch_assoc($h2)){
							if($d2['DestinationNumber'] <> "+6287744421420"){
								echo"<tr class='baris'>
								<td>".$no++."</td>
								<td>".tgl_angka($d2['UpdatedInDB'])."</td>
								<td>$d2[DestinationNumber]</td>
								<td>".$d2['TextDecoded']."</td>
								<td>
									<a href='".base_url()."/".url_segment(1)."/kirim-ulang/$d2[ID]' class='btn btn-success'>Kirim Ulang</a>
									".link_hapus(base_url()."/".url_segment(1)."/hapus/$d2[ID]")."
								</td>
							  </tr>";
							}
						 }
					  ?>
					</table>
				 <p>
					<?php 
						 $jmldata=mysqli_num_rows(mysqli_query($koneksi,"select * from outbox order by UpdatedInDB"));
						echo halaman($jmldata,$batas,base_url()."/".url_segment(1),$hal);
					?>
				 </p>
					<?php
					break;
					case "hapus" :
								$h3=mysqli_query($koneksi,"DELETE FROM outbox WHERE ID='".url_segment(3)."'");
								if($h3 == TRUE){
										echo jsPesan("Sukses",base_url()."/".url_segment(1));
								}
								else{
										echo jsPesan("Gagal",base_url()."/".url_segment(1));
								}
					break;
					case "hapus-semua" :
								$h3=mysqli_query($koneksi,"DELETE FROM outbox");
								if($h3 == TRUE){
										echo jsPesan("Sukses",base_url()."/".url_segment(1));
								}
								else{
										echo jsPesan("Gagal",base_url()."/".url_segment(1));
								}
					break;
					case "kirim-ulang" :
								
					break;
					
					
				}
			?>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->