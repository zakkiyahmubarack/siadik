<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Tulis Pesan

	</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-primary">
		<div class="box-body">
			<?php
			if (url_segment(2) == "") {
				echo js(base_url() . "/" . url_segment(1) . "/home");
			}
			switch (url_segment(2)) {
				case "home":
			?>
					<form name="form1" method="post" action="<?php echo base_url() . "/" . url_segment(1); ?>/kirim" class="form" id="formMhs">
						<p>Tipe SMS.:<br>
							<input type="radio" name="tipe" id='tipe' value='personal' required="TRUE" checked> Personal &nbsp;
							<?php
							$q_kls = mysqli_query($koneksi, "select * from tbl_group where status_group='1' order by nama_group ASC");
							while ($d_kls = mysqli_fetch_assoc($q_kls)) {
								echo "<input type=\"radio\" name=\"tipe\" id='tipe' value='$d_kls[id_group]' required> $d_kls[nama_group]";
							}
							?>
						</p>
						<p>Nomor Hp.:<br><input type="number" name="nomor" id='nomor' placeholder="6285624510xxx" class="form-control"></p>
						<p>Pesan:<br><textarea name="isi" id='isi' rows="10" cols="50" class="form-control" required="TRUE"></textarea>
						</p>
						<p><input type="submit" name="submit" id="button" class="btn btn-success" value="Kirim"></p>
					</form>
			<?php
					break;
				case "kirim":
					// send to whatapp
					if ($_POST['submit'] == TRUE) {
						// API endpoint URL
						$url = 'http://localhost:3000/api/send-message';

						// Request body
						$data = array(
							'number' => $_POST['nomor'],
							'message' => $_POST['isi']
						);

						// Convert data array to JSON
						$requestBody = json_encode($data);

						// Initialize cURL
						$curl = curl_init();

						// Set the cURL options
						curl_setopt($curl, CURLOPT_URL, $url);
						curl_setopt($curl, CURLOPT_POST, true);
						curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
						curl_setopt($curl, CURLOPT_HTTPHEADER, array(
							'Content-Type: application/json',
							'Content-Length: ' . strlen($requestBody)
						));
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

						// Execute the cURL request
						$response = curl_exec($curl);

						// Check for errors
						if (curl_errno($curl)) {
							$error_message = curl_error($curl);
							// Handle the error appropriately
						}

						// Close cURL
						curl_close($curl);


						if ($_POST['tipe'] == "personal") {
							$h3 = mysqli_query($koneksi, "INSERT into pesan (tgl_pesan,nomor_pesan,isi_pesan) VALUES (now(),'$_POST[nomor]','$_POST[isi]')");
							if ($h3) {
								echo jsPesan("Sukses", base_url() . "/" . url_segment(1));
							} else {
								echo jsPesan("Gagal", base_url() . "/" . url_segment(1));
							}
						} else {
							$q_kls = mysqli_query($koneksi, "select * from tbl_phone a left join tbl_user b on a.kode_user=b.id_kode_user
													left join tbl_group c on b.id_group=c.id_group
													where b.id_group='$_POST[tipe]'");
							while ($d_kls = mysqli_fetch_assoc($q_kls)) {
								$h3 = mysqli_query($koneksi, "INSERT into pesan (tgl_pesan,nomor_pesan,isi_pesan) VALUES (now(),'" . $d_kls['no_phone'] . "','$_POST[isi]')");
							}

							if ($h3 == TRUE) {
								echo jsPesan("Sukses", base_url() . "/" . url_segment(1));
							} else {
								echo jsPesan("Gagal", base_url() . "/" . url_segment(1));
							}
						}
					}
					break;
			}
			?>
		</div>
		<!-- /.box -->
</section>
<!-- /.content -->