<?php	
	include_once("config.php");
	#fetch records from database for the session
	$page=$_GET["page"];
	$session=$_GET["sess"];
	$lim=10;
	$start=($page-1)*$lim;
        $sql = "select genome,result,filec from users where user_id ='$session'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
		$filec=$row["filec"];
                $analysis=$row["result"];
		$genome=$row["genome"];
        };
	};
	$conn->close();
	$ulPath="server/files/".$session."/";
	$app_path=$home.$ulPath;
        $great="http://great.stanford.edu/public/cgi-bin/greatStart.php?requestURL=";
	$ucsc="http://genome.ucsc.edu/cgi-bin/hgTracks?db=".$genome."&hgt.customText=";
        $data= explode("\n",$analysis);
	$gdata=array_slice($data,$filec);
	$gdata1=array_slice($gdata,$start,$lim);
	foreach ($gdata1 as $gudata) {
		$gudata1= explode("\t",$gudata);
		if($genome != 'none'){
			echo "<tr><td>".$gudata1[0]."</td> <td><a href='".$app_path.$gudata1[1]."' download>".$gudata1[2]."</a></td><td> <a href=".$great.$app_path.$gudata1[1]."&requestSpecies=".$genome."&requestName=".$gudata1[1]."&requestSender=BedSect target=_blank>Submit</a></td><td><a href=".$ucsc.$app_path.$gudata1[1]." target=_blank>Submit</a></td></tr>";
		}else{
		echo "<tr><td>".$gudata1[0]."</td> <td><a href='".$app_path.$gudata1[1]."' download>".$gudata1[2]."</a></td><td>NA</td><td>NA</td></tr>";
		};
        };
?>
