<?php
$user = $_POST["user"];
$email = $_POST["email"];
$genome = $_POST["genome"];
$overlap = $_POST["overlap"];
$gtrd = $_POST["gtrd"];
$date= date("Y-m-d");

include_once("config.php");
if(!empty($gtrd)){
    $gtrd = rtrim($gtrd,',');
    $gtrd_exp = str_replace(",", "','", $gtrd);
    $gtrd_file_sql = "select Experiment,TF,Organism from gtrd_data where Experiment in ('$gtrd_exp')";
    $result = $conn->query($gtrd_file_sql);
    while($row = $result->fetch_assoc()) {
      switch($row['Organism']){
        case "homsap":
        $organism = "Homo_sapiens";
        break;
        case "musmus":
        $organism = "Mus_musculus";
        break;
        case "danrer":
        $organism = "Danio_rerio";
	break;
	case "ratnor":
        $organism = "Rattus_norvegicus";
	break;
	case "dromel":
        $organism = "Drosophila_melanogaster)";
	break;
	case "spombe":
        $organism = "Schizosaccharomyces_pombe";
	break;
	case "scerev":
        $organism = "Saccharomyces_cerevisiae";
	break;
      }
      $gtrd_names[] = 'gtrd/'.$organism.'/'.$row['TF'].'/'.$row['TF'].'_'.$row['Experiment'].'.bed';
    };
    $gtrd_file_count = count(explode(',',$gtrd));
    $gtrd_files = implode(" ",$gtrd_names);
};

if(!empty($email) and filter_var($email, FILTER_VALIDATE_EMAIL)==true){
	if(($overlap <= 1000 && ($overlap >= 1))){
    $sql1 = "select name from files where user_id ='$user'";
    $result = $conn->query($sql1);
    $user_file_count = $result->num_rows;
    if ($user_file_count >= 2 || $gtrd_file_count >= 2 || $user_file_count+$gtrd_file_count >= 2) {
        while($row = $result->fetch_assoc()) {
                $filenames[]=$row["name"];
        };
        $filec=count($filenames)+$gtrd_file_count;
        $files=implode(" ",$filenames);
        # $com= "/usr/bin/perl -f bedsect.pl ".$user." ".$files." ".$genome." ".$rand;
        if(empty($gtrd)){
        $com= "/usr/bin/perl -f bedsect_gtrd.pl server/files/".$user." --ext ".$files." ".$overlap;
        }elseif(empty($files)){
        // Create the directory as for GTRD data directory does not exists
        mkdir("server/files/".$user, 0755, true);
        $com= "/usr/bin/perl -f bedsect_gtrd.pl server/files/".$user." --gtrd ".$gtrd_files." ".$overlap;
        }else{
        $com= "/usr/bin/perl -f bedsect_gtrd.pl server/files/".$user." --ext ".$files." --gtrd ".$gtrd_files." ".$overlap;
        };
        $sql2 = "INSERT INTO users (user_id, filec, date, email, status, command, genome, overlapsize, gtrd_org) VALUES ('$user','$filec','$date', '$email','2', '$com', '$genome', '$overlap', '$organism')";
        if ($conn->query($sql2) === TRUE) {
              shell_exec("/usr/bin/php jobControl.php '".$user."' 'alert' >> jobStatus.log &");		
              $alert= 'The job has been submitted and a mail will be sent when it is completed.<br> Redirecting in 5 secs to the result page. If the the page does not redirect automatically an email will be sent. </p></div>
              <script type="text/javaScript">
              redirectTime = "5000";
              redirectURL = "./result.php?uid='.$user.'";
              setTimeout("location.href = redirectURL;",redirectTime);
              </script>
              <div class="modal-footer">
              <button type="button" class="btn btn-success" data-dismiss="modal" onClick="window.location.reload()">New Analysis</button><br>';
              $subject="BedSect Job id #".$user." submitted successfully";
              $mailbody="Your request has been submitted.<br><br> Regards,<br>Imgsb Team";
	      #include_once("mail.php"); #Email sending disabled by default
        } else {
    		$alert='The job is already started/running, refresh the page to start a new analysis. </p></div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>';
	      };
	      $conn->close();
    } else {
        $alert='Upload atleast two files to overlap. </p></div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        };
	}else{
        $alert='Insert an overlap size in 1-1000 BP range.</p></div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
	};
}else{
	    $alert= 'An valid email is required for starting a Job. </p></div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
};
?>
<div class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Message</h5>
        </button>
      </div>
      <div class="modal-body">
        <p><?php echo $alert;?>
	</div>
    </div>
  </div>
</div>
