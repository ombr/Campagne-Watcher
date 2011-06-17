<?php
/*
values=[];
values[0]=[0,01,1,315,31,3,1];
values[1]=[0,01,1,315,31,3,1];
values[2]=[0,01,1,315,31,3,1];
label="";
values2=[0,1,2]; // nb de votes par liste (dans l'ordre)
label2=["la jacoliste", "l'hydraliste", "l'emiliste"];
animate();
 */

include "../db.php";
/*
$tabstats["la jacoliste"] = 0;
$tabstats["l'hydraliste"] = 1;
$tabstats["l'emiliste"] = 2;
 */
$tabnoms[1]="la dolcelista";
$tabnoms[2]="l'analyste";
$tabnoms[3]="la liste and peace";

function resultat() {
	$ret = "resultats du jour :\n";
	$sql = "SELECT *, Count(*) as nb FROM `vote` WHERE `date` > '".date("Y-m-d ", time())." 00:00:00"."' GROUP BY vote";
	$req = db::query($sql);
	while ($res = mysql_fetch_array($req)) {
		if (isset($tabnoms['nb'])) { // ??
			$vote = $tabnoms[$res['nb']];
			$ret .= "$vote : ".$res['nb']." voix\n";
		}
	}
	return $ret;
}
/*
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
 */
function stats($jour) {
	global $tabstats,$tabnoms;
	for ($i = 0; $i < 24; $i++) {
		foreach($tabnoms as $kkey=>$vvalue){
			$ret[$kkey][$i]=0;
		}
		$sql = "SELECT *, Count(*) as nb 
			FROM `vote` 
			WHERE `date` >= '".date("Y-m-d ", time()+$jour*86400)." $i:00:00"."' 
			AND `date` < '".date("Y-m-d ", time()+$jour*86400)." ".($i+1).":00:00"."' 
			GROUP BY vote";
		$req = db::query($sql);
		while ($res = mysql_fetch_array($req)) {
			$ret[$res['vote']][$i] = $res['nb'];
		}
	}
	return $ret;
}

function stats2($jour) {
	$sql = "SELECT *, Count(*) as nb 
		FROM `vote` 
		WHERE `date` >= '".date("Y-m-d ", time()+$jour*86400)." 00:00:00"."' AND `date` < '".date("Y-m-d ", time()+($jour+1)*86400)." 00:00:00"."' 
		GROUP BY vote";
	$req = db::query($sql);
	while ($res = mysql_fetch_array($req)) {
		$ret[$res['vote']] = $res['nb'];
	}
	if (!isset($ret[1])) { $ret[1] = 0; }
	if (!isset($ret[2])) { $ret[2] = 0; }
	if (!isset($ret[3])) { $ret[3] = 0; }
	return $ret;
}

function jmin() {
	$ret = time() - mktime(0, 0, 0, 2, 2, 2011);
	$ret = $ret/86400;
	$ret = -floor($ret);
	return $ret;
}

$no_jour = 0;
$jmin = jmin();
if (isset($_GET['j'])) {
	$no_jour = $_GET['j'];
}
if ($no_jour >= 0) { 
	$no_jour = 0;
} else if ($no_jour < $jmin) {
	$no_jour = $jmin;
}
if ($no_jour == 0) {
	$jour = "aujourd'hui";
} else if ($no_jour == -1) {
	$jour = "hier";
} else {
	$tabjour = array(1=>"lundi", 2=>"mardi", 3=>"mercredi", 4=>"jeudi", 5=>"vendredi", 6=>"samedi", 7=>"dimanche");
	$temp = (int) date("N", time()+$no_jour*86400);
	$jour = $tabjour[$temp];
}

$s = stats($no_jour);

$k = 0;
foreach ($s as $liste) {
	echo "values[$k]=[";
	$ligne = "";
	for ($i = 0; $i<24; $i++) {
		if (isset($liste[$i])) {
			$ligne .= ",".$liste[$i];
		} else {
			$ligne .= ",0";
		}
	}
	$ligne = substr($ligne,1);
	echo $ligne."];\n";
	$k++;
}
echo "label=\"$jour\";\n";
echo "now=$no_jour;\n";

$s2 = stats2($no_jour);

$jaco = $s2[1];
$hydra = $s2[2];
$emi = $s2[3];
if($s2[1]==0 && $s2[2]==0 && $s2[3]==0){ 
	echo "values2=[1,1,1];\n";
}else{
	echo "values2=[$jaco, $hydra, $emi];\n";

}
echo "labels2=[\"".$tabnoms[1]." ($jaco)\", \"".$tabnoms[2]." ($hydra)\", \"".$tabnoms[3]." ($emi)\"];\n";

echo "animation();\n";

?>
