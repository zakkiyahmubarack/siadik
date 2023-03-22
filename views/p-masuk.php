<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pesan Masuk
        
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
						<th>Pengirim</td>
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
						$h2=mysqli_query($koneksi,"select * from inbox order by UpdatedInDB DESC limit $posisi,$batas");
						while($d2=mysqli_fetch_assoc($h2)){
							if($d2['SenderNumber'] <> "+6287744421420"){
								echo"<tr class='baris'>
								<td>".$no++."</td>
								<td>".tgl_indo($d2['UpdatedInDB'])."</td>
								<td>$d2[SenderNumber]</td>
								<td>".$d2['TextDecoded']."</td>
								<td>
									".link_hapus(base_url()."/".url_segment(1)."/hapus/$d2[ID]")."
								</td>
							  </tr>";
						}
						 }
					  ?>
					</table>
				 <p>
					<?php 
						 $jmldata=mysqli_num_rows(mysqli_query($koneksi,"select * from inbox order by UpdatedInDB"));
						echo halaman($jmldata,$batas,base_url()."/".url_segment(1),$hal);
					?>
				 </p>
					<?php
					break;
					case "hapus" :
								$h3=mysqli_query($koneksi,"DELETE FROM inbox WHERE ID='".url_segment(3)."'");
								if($h3 == TRUE){
										echo jsPesan("Sukses",base_url()."/".url_segment(1));
								}
								else{
										echo jsPesan("Gagal",base_url()."/".url_segment(1));
								}
					break;
					case "hapus-semua" :
								$h3=mysqli_query($koneksi,"DELETE FROM inbox");
								if($h3 == TRUE){
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