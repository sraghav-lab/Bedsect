<?php
include("config.php");
$user=$argv[1];
$sql1 = "select command,email,genome,overlapsize from users where user_id ='$user'";
        $result = $conn->query($sql1);
        if ($result == TRUE) {
        // output data of each row
	while($row = $result->fetch_assoc()) {
		$genome=$row["genome"];
		$command=$row["command"];
		$email=$row["email"];
		$overlap = $row["overlapsize"];
        };
	exec($command,$res);
	file_put_contents('sqlDump/'.$user.'.res', $res);
	$res=implode("\n",$res);
	$sql2 = "UPDATE users SET result='$res',status='1' WHERE user_id ='$user'";
	file_put_contents('sqlDump/'.$user.'.log', $sql2);
	$conn->query($sql2);
	if($conn->query($sql2) == TRUE){
	echo "Run successful id: ".$user." Timestamp: ".date("Y-m-d h:i:sa")."\n";
        $subject="BedSect Job id #".$user." completed";
        $mailbody='<b>Details of the analysis </b><br><br><b>Session ID: </b>'.$user.' <br><br><b>Genome Used: </b>'.$genome.'<br><br><b>Overlap Size: </b>'.$overlap.' Bp<br><br><b><a href="http://imgsb.org/bedsect/result.php?uid='.$user.'">Analysis Result</a></b>
<br><br> Please keep the session id for future reference. The the session id will become invalid after seven days from now.<br> Thank you for using BedSect toool hosted by Institute of Life Sciences, Bhubaneswar Web-Server. For queries please reply to this email, spamming will cause permanent ban from ILS server.';
	#include_once("mail.php");
	}
	}else{
	die("Connection failed: " . $conn->connect_error."\n");
	};
$conn->close();
?>
