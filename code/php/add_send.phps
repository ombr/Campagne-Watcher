<?php 
//header ("content-type: text/xml");
include "db.php";
//echo '<?xml version="1.0" encoding="utf-8"? >';
function request_jour($i){
	$str="tel IN (SELECT tel FROM vote WHERE date>=\"".date( 'Y-m-d', time()+$i*3600*24)." 00:00:00\" and date <=\"".date( 'Y-m-d', time()+($i+1)*3600*24)." 00:00:00\")";
	echo "CONTRAINTE : ".$str."<br>\n";
	return $str;
}
/*echo request_jour(0)."\n<br>";
echo request_jour(-1)."\n<br>";
echo request_jour(-2)."\n<br>";*/
//$sql = "SELECT DISTINCT(tel) FROM vote WHERE ".request_jour(-1)." and ".request_jour(-2)." and ".request_jour(-3);
//$sql = "SELECT DISTINCT(tel) FROM vote WHERE ".request_jour(-1)." and ".request_jour(-2)." and not ".request_jour(0);
//$sql = "SELECT DISTINCT(tel) FROM vote WHERE ".request_jour(-1)." and not ".request_jour(0);
$msg="CampagneWatcher: Vous n avez pas encore vote aujourd hui, nouveau poste une annonce en envoyant msg:<ton annonce> au 06.78.34.22.72.";
$msg="CampagneWatcher: Un grand merci a nos beta testeur...";
$sql = "SELECT DISTINCT(tel) FROM vote WHERE not ".request_jour(0);
$msg="CampagneWatcher : vous n'avez pas encore voté aujourd'hui\nSoutenez votre liste préférée en répondant 1, 2 ou 3 ;)\nplus d'info www.campagnewatcher.com";
if(strlen($str)>169){
	echo "Message trop long !".strlen($msg);
	exit(0);
}
//$sql = "SELECT DISTINCT(tel) FROM vote WHERE not ".request_jour(-1)." and not ".request_jour(-2)." and ".request_jour(-3);
//$sql = "SELECT DISTINCT(tel) FROM vote WHERE  ".request_jour(-1)." and ".request_jour(-2)." and ".request_jour(0)." and ".request_jour(-3);
$sql = "SELECT DISTINCT(tel) FROM vote WHERE not ".request_jour(0);
echo $sql."\n<br>";
$res = db::query($sql);
$i=0;
while($row=mysql_fetch_array($res)){
	$i++;
	if(isset($_GET['send'])){
		db::query("INSERT INTO sms (dest ,msg)VALUES ('".$row['tel']."', '".db::protect($msg)."');");
	}
	echo $row['tel'].' -->>'.$msg.'<br>'."\n";
}
echo "\n<br><br>\nTOTAL : ".$i."\n<br>";
echo "LONGUEURE DU MESSAGE : ".strlen($msg)."\n<br>";
?>

