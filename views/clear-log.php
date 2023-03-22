<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Clear Log Absen Mesin
        
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
					echo"<p>Pilih mesin dibawah ini:</p>";
					$h2=mysqli_query($koneksi,"select * from tbl_perangkat order by nama_perangkat ASC");
					while($d2=mysqli_fetch_assoc($h2)){
					echo"<p><a href='".base_url()."/".url_segment(1)."/mulai/$d2[ip_perangkat]' class='btn btn-default'>Mesin $d2[nama_perangkat]</a></p>";
				 }
				break;
				case 'mulai':
				$Connect = fsockopen(url_segment(3), "80", $errno, $errstr, 1);
				if($Connect){
					$soap_request="<ClearData><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";
					$newLine="\r\n";
					fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
					fputs($Connect, "Content-Type: text/xml".$newLine);
					fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
					fputs($Connect, $soap_request.$newLine);
					$buffer="";
					while($Response=fgets($Connect, 1024)){
						$buffer=$buffer.$Response;
					}
				}else echo "<div class='alert alert-warning'>Koneksi Gagal</div>";
				$buffer=Parse_Data($buffer,"<Information>","</Information>");
				echo "<div class='alert alert-success'><B>Result:</B><BR>".$buffer."</div>";
				//echo $buffer;
				break;
			}
			?>
         </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->