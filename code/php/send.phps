<?php 
header ("content-type: text/xml");
include "db.php";
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<send>';
$sql = "SELECT * FROM sms WHERE sended=0 ORDER BY ID LIMIT 20";
$res = db::query($sql);
while($row=mysql_fetch_array($res)){
	echo '<item num="'.$row['num'].'">'.$row['msg'].'</item>';
}
db::query("UPDATE sms SET sended=1 ORDER BY ID LIMIT 20");
echo '</send>';
?>
	
