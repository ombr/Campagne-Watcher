<?php
include_once("../db.php");
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
	return $ret;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Campagne Watcher</title>
        <link rel="stylesheet" href="demo.css" type="text/css" media="screen">
        <link rel="stylesheet" href="demo-print.css" type="text/css" media="print">
	<script src="raphael.js" type="text/javascript" charset="utf-8"></script>

        <script src="jquery.js"></script>
        <script src="pie.js"></script>
        <!--script src="chart.js"></script-->
<script type="text/javascript" charset="utf-8">
values=[];

c=[];
bg=[];
clr=[];
clr[0]="#00FF00";
clr[1]="#0000FF";
clr[2]="#FF0000";
clr2=[];
clr2[0]="#006600";
clr2[1]="#000066";
clr2[2]="#660000";
label="...";
coef=10;
now=0;
function animation() {
	
	var time = 500;
	for (var i = 0; i <3; i++) {
		c[i].animate({path: Path(i), stroke: clr[i]}, time, "<>");
		bg[i].animate({path: Path(i) + "L590,250 10,250z", fill: clr[i]}, time, "<>");
	}
	month.attr({text: label});
	ccam.clear();
	cam=ccam.pieChart(350, 350, 200, values2, labels2, "#fff");
};
function Path(k){
	var path = "",x = 10,y = 0;
	for (var i = 0; i <24; i++) {
		if (i) {
			path += "C" + [x + 10, y, (x += 25) - 10, (y = 240 - (values[k][i]*coef)), x, y];
		} else {
			path += "M" + [10, (y = 240 - (values[k][i]*coef))];
		}
	}
	return path;
}

var values2 = [1,1,1];
var labels2 = ["","",""];
var ccam;
window.onload = function () {
	//Camembert !
	/*$("tr").each(function () {
		values2.push(parseInt($("td", this).text(), 10));
		labels2.push($("th", this).text());
	});
$("table").hide();*/
	ccam=Raphael("holder", 700, 700);
	cam=ccam.pieChart(350, 350, 200, values2, labels2, "#fff");
	//Initialisation des graphs du jours.

	var r = Raphael("holder2", 640, 240);
	xmax=580;
	ymax=240;
	txt = {font: '10px Helvetica, Arial', fill: "#fff",opacity : 0.5},

	i = 0;
	for (var xx = 10; xx <=xmax+10; xx+=xmax/24 ) {
		p=r.path("M"+xx+" 60L"+xx+" "+ymax);
		r.text(xx,40,i).attr(txt);
		i++;
		p.attr({stroke: "#000000", opacity : 0.5});
	}
	for (var yy = 60; yy <=ymax; yy+=30 ) {
		p=r.path("M10 "+yy+"L"+(xmax+10)+" "+yy);
		p.attr({stroke: "#000000", opacity : 0.5});
		if(yy!=ymax){
			r.text(610,yy,(ymax-yy)/coef).attr(txt);
		}
	}
	
	//Interactions :
	//bg = r.rect(243, 14, 134, 11, 13).attr({fill: "#666", stroke: "none"});
	month = r.text(310, 11, "...").attr({fill: "#fff", stroke: "none", "font": '100 18px "Helvetica Neue", Helvetica, "Arial Unicode MS", Arial, sans-serif'});
	rightc = r.circle(364, 11, 10).attr({fill: "#fff", stroke: "none"});
	right = r.path("M360,6l10,5 -10,5z").attr({fill: "#000"});
	leftc = r.circle(256, 11, 10).attr({fill: "#fff", stroke: "none"});
	left = r.path("M260,6l-10,5 10,5z").attr({fill: "#000"});



	for (var i = 0; i <3; i++) {
		values[i]=[];
		for (var j = 0; j <24; j++) {
			values[i][j]=0;
		}
		//clr[i] = Raphael.getColor(1);
		c[i] = r.path("M0,0").attr({fill: "none", "stroke-width": 3});
		bg[i] = r.path("M0,0").attr({stroke: "none", opacity: .3});
		c[i].attr({path: Path(i), stroke: clr[i]});
		bg[i].attr({path: Path(i) + "L590,250 10,250z", fill: clr[i]});
	}
	reload=function(){
		for (var i = 0; i <3; i++) {
			for (var j = 0; j <24; j++) {
				values[i][j]=0;
			}
		}
		$.getScript('stats.php?j='+now,function(){});
	}


	rightc.node.onclick = right.node.onclick = function () {
		if(now<0){
			now++;
		}
		reload();
	};
	leftc.node.onclick = left.node.onclick = function () {
		now--;
		reload();
	};
	reload();
	setInterval("reload()",30000);
}

</script>
	<style type="text/css" media="screen">
	    #holder {
		margin: -350px 0 0 -350px;
		width: 700px;
		height: 700px;
	    }

	</style>

    </head>
    <body>

	<div id="holder"></div>
	<div id="holder2"></div>
    </body>
</html>
