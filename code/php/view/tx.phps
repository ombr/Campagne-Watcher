<?php
include "../db.php";

$tabjours[-2]="avant hier";
$tabjours[-1]="hier";
$tabjours[0]="aujourd'hui";

function tx($jour) {
	global $tabjours;

	// nombre total de votes
	$sql = "SELECT Count(*) as nb FROM `vote` WHERE `date` > '".date("Y-m-d ", time()+86400*$jour)." 00:00:00"."'";
	$res = mysql_fetch_array(db::query($sql));
	$tot = $res[0];
	
	$ret = "resultats d'".$tabjours[$jour]." :\n";

	// recuperation des votes par liste
	$sql = "SELECT *, Count(*) as nb FROM `vote` WHERE `date` > '".date("Y-m-d ", time()+86400*$jour)." 00:00:00"."' GROUP BY vote";
	$req = db::query($sql);
	while ($res = mysql_fetch_array($req)) {
		$ret .= $res['vote']." :\t".jauge($res['nb'], $tot);
	}
	$ret .= "la dolcelista :\t".jauge(1, $tot);
	return $ret;
}

function jauge($nb, $tot) {
	$p = 0;
	if ($tot > 0) {
		$p = $nb * 100/$tot;
	}
	$j = "";
	for ($i = 0; $i < $p; $i++) {
		$j .= "=";
	}
	$j .= "\n";
	return $j;
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

echo tx(-1);
echo "\n";
echo tx(0);
?>
