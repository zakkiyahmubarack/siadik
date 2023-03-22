<?php
error_reporting(0);
date_default_timezone_set("Asia/Jakarta");
include "../assets/config/koneksi.php";
include "../assets/config/function.php";
include "../assets/config/tgl_indo.php";
$h2=mysqli_query($koneksi,"select * from tbl_perangkat order by nama_perangkat ASC");
$d2=mysqli_fetch_assoc($h2);
/*
while($d2=mysqli_fetch_assoc($h2)){
    $Connect = fsockopen($d2['ip_perangkat'], "80", $errno, $errstr, 1);
    if($Connect){
        $soap_request="<SetDate><ArgComKey xsi:type=\"xsd:integer\">0</ArgComKey><Arg><Date xsi:type=\"xsd:string\">".date("Y-m-d")."</Date><Time xsi:type=\"xsd:string\">".date("H:i:s")."</Time></Arg></SetDate>";
        $newLine="\r\n";
        fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
        fputs($Connect, "Content-Type: text/xml".$newLine);
        fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
        fputs($Connect, $soap_request.$newLine);
        $buffer="";
        while($Response=fgets($Connect, 1024)){
            $buffer=$buffer.$Response;
        }
    }
    $buffer=Parse_Data($buffer,"<Information>","</Information>");
}
*/
echo $d2['ip_perangkat'];
?>