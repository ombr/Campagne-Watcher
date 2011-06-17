<?php
include "db.php";

function resultat() {
	$ret = "resultats du jour :\n";
	$sql = "SELECT *, Count(*) as nb FROM `vote` WHERE `date` > '".date("Y-m-d ", time())." 00:00:00"."' GROUP BY vote";
	$req = db::query($sql);
	while ($res = mysql_fetch_array($req)) {
		$s = "";
		if ($res['nb'] > 1) { $s = "s"; }
		$ret .= $res['vote']." : ".$res['nb']." voie$s\n";
	}
	return $ret;
}

function stats($jour) {
	//for ($jour = 0; $jour < )
	//a faire : while jour > date(debut de campagne)...
	for ($i = 0; $i < 24; $i++) {
		$sql = "SELECT *, Count(*) as nb 
			FROM `vote` 
			WHERE `date` >= '".date("Y-m-d ", time()+$jour*86400)." $i:00:00"."' AND `date` < '".date("Y-m-d ", time()+$jour*86400)." ".($i+1).":00:00"."' 
			GROUP BY vote";
		$req = db::query($sql);
		while ($res = mysql_fetch_array($req)) {
			$ret[$res['vote']][$i] = $res['nb'];
		}
	}
	echo "stats ($jour) :";
	print_r($ret);
}

if(isset($_POST['msg']) && isset($_POST['num'])){
	$msg = mysql_real_escape_string($_POST['msg']);
	$num = mysql_real_escape_string($_POST['num']);

	// recuperation du nom
	$vote = "";
	$sql = "SELECT `nom` FROM `liste` WHERE `vote` LIKE '%$msg%'";
	$req = db::query($sql);
	if ($res = mysql_fetch_array($req)) {
		$vote = $res[0];

		// verification qu'il a pas encore vote
		$sql = "SELECT * FROM `vote` WHERE `tel` LIKE '%$num%' AND `date` > '".date("Y-m-d ", time())." 00:00:00"."' ORDER BY `date` DESC";
		$req = db::query($sql);
		if ($res = mysql_fetch_array($req)) {
			echo "vous avez deja vote aujourd'hui (pour ".$res['vote'].")\n";
		} else {
			$sql = "INSERT INTO `vote` (`vote`,`tel`) VALUES (\"$vote\",'$num')";
			db::query($sql);
			echo "vous avez vote pour $vote\n";
		}

		file_put_contents("logs.txt", $_POST['num']."\t".$_POST['msg']."\n", FILE_APPEND | LOCK_EX);
	}
} else {
	echo "votre vote n'est pas valide...\n";
	file_put_contents("logs.txt", "---\n", FILE_APPEND | LOCK_EX);
}
echo "\n";
echo resultat();
?>
