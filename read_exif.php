<?php
/*
01 - France
02 - Italie
03 - Croatie
04 - Slovenie
05 - Montenegro
06 - Albanie
07 - Macedoine
08 - Grece
09 - Turquie
10 - Australie - Melbourne
11 - Australie - Tasmanie
12 - Nouvelle-Zélande (Sud)
13 - Nouvelle-Zélande (Nord)
*/
 
 
 mysql_connect("localhost", "travelbook", "travelbook"); // Connesion à la base de données
 mysql_select_db("travelbook"); // Sélection de la base de données


$dirname = "D:/Voyage/nz/";
$dir = opendir($dirname); 

while($file = readdir($dir)) {
	if($file != '.' && $file != '..' && !is_dir($dirname.$file)) {
        if (exif_read_data($dirname.$file, 'IFD0') !== false) {
            $exif = exif_read_data($dirname.$file, 0, true);
            //var_dump($exif);
            $shootingDate = preg_replace("/(\d{4}).(\d{2}).(\d{2})/", "$1-$2-$3", $exif["IFD0"]["DateTime"]);
           // echo $file." -> ".$shootingDate."<br/>";
            $sql = "SELECT count(*) FROM photos WHERE name = '".$file."' AND shooting_date = '".$shootingDate."'";
            if ( mysql_result(mysql_query ($sql), 0) < 1) {
                echo $file." -> ".$shootingDate."<br/>";
                $sql = "INSERT INTO photos (name, shooting_date, caption) VALUES ('".$file."', '".$shootingDate."', 'Nouvelle-Zélande (Nord)')";
                mysql_query ($sql);
            }
        }
	}
}

closedir($dir);
mysql_close(); 
?>
