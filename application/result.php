<!DOCTYPE html>
<html lang="en">
  <head>
	<meta http-equiv=“Cache-Control” content=“no-cache, no-store, must-revalidate” />
	<meta http-equiv=“Pragma” content=“no-cache” />
	<meta http-equiv=“Expires” content=“0" />
	<meta name="robots" content="noindex, nofollow" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="robots" content="noindex, nofollow">
	<link rel="icon" href="./assets/favicon.ico">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<title>Analysis Result</title>
    <!-- Call nav bar-->
    <?php include("views/nav.php"); ?>
<script>
$(document).ready(function(){
    $.get("page.php?page=1&sess=<?php echo $_GET['uid'];?>", function(data, status){
        $("#results").html(data);
	$("#1").addClass("active");
       });
    $(".page-link").click(function(){
	var pno = this.id;
    	$('ul li').removeClass("active");
	$('#'+pno).addClass("active");
        $.get("page.php?page="+pno+'&sess=<?php echo $_GET['uid'];?>', function(data, status){
            $("#results").html(data);
        });
	return false;
    });
	$('.modal').modal('show');
});
</script>
</head>
  <body>
    <!-- APP BODY STARTS-->
    <div id="content" class="container" style="border:0px solid #d6d6d6;">
      <div class="page-header">
        <h2>Analysis Result</h2>
      </div>
    <div class="result">
<?php
//Get all the post parameters
	$session_id = $_GET['uid'];
	//Data path for the session
	$ulPath = 'data/'.$session_id.'/';
	include_once("config.php");
	//fetch records from database for the session
	$sql = "select * from users where user_id ='$session_id' ";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
    	while($row = $result->fetch_assoc()) {
		$genome = $row["genome"];
		$date = $row["date"];
		$filec = $row["filec"];
		$status = $row["status"];
		$analysis = $row["result"];
		$overlap = $row["overlapsize"];
		if(!empty($row["status"])){
			$gtrd_org = str_replace("_"," ",$row["gtrd_org"]);
		}else{
			$gtrd_org = "NA";
		};
    	};
	$conn->close();
    	// echo "The session data has been expired, please run the experiment again";
	};
	$analysis=explode("\n",$analysis);
	$data1=array_slice($analysis,0,$filec);
	$data2=array_slice($analysis,$filec);
        // Save summary table in the run directory
	$file_path = 'server/files/'.$session_id.'/';
	$inputStat = $file_path.$session_id."_input_stats.tsv";
	$ovlStat = $file_path.$session_id."_overlap_stats.tsv";
	$detailsTable = $file_path.$session_id."_job_details.tsv";
	$input_stat[] = "FileName\tTotalRegions\tMedian";
	$input_stat = array_merge($input_stat,$data1);
        $ovl_stat[] = "Combination\tFileName\tTotalRegions";
	$ovl_stat = array_merge($ovl_stat,$data2);
	$jobDetails = "SessionID\tDate\tGenome\tGTRD_Organism\tOverlapSize\n".$session_id."\t".$date."\t".$genome."\t".$gtrd_org."\t".$overlap;
        file_put_contents($inputStat, implode(PHP_EOL, $input_stat));
	file_put_contents($ovlStat, implode(PHP_EOL, $ovl_stat));
	file_put_contents($detailsTable, $jobDetails);
	if(empty($status)){
	die('<div class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Message</h5>
              </div>
              <div class="modal-body">
                <p>The session data has been expired, please run the experiment again. <a href="./">Click here</a> to return to the Homepage.</p>
              </div>
              <div class="modal-footer">
              </div>
            </div>
          </div>
	</div>');
	};
	if($status==2){
	die('<div class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Message</h5>
	      </div>
	      <div class="modal-body">
	        <p>The analysis is still running/ please wait for the mail or come back later.</p>
	      </div>
	      <div class="modal-footer">
		<button type="button" class="btn btn-success" data-dismiss="modal" onclick="window.location.reload()">Reload</button>
	      </div>
	    </div>
	  </div>
	</div>');
};
?>
    </div>

<div class="row" style="margin-bottom: 5px;">
<div class="col-xs-4 col-xs-offset-4">
  <!-- Replace <shiny server address> with your shiny server path-->
	<a class="btn btn-danger btn-block" href="./plot.php?uid=<?php echo $session_id;?>">
        <i class="glyphicon glyphicon-picture"></i>
      	<span style="color: #fff"><b>Generate Plots</b></span></a>
</div>
</div>
    <div class="row">
        <div class="panel panel-warning">
          <div class="panel-heading"><b>Analysis Details</b></div>
        <table class="table table-bordered">
      <thead>
          <tr>
            <th>Session ID</th>
	    <th>Date Performed</th>
	    <th>Genome</th>
            <th>GTRD Organism</th>
	    <th>Overlap Size (BP)</th>
	    <th>Download Result</th>
          </tr>
        </thead>
        <tbody>
	<?php
              echo "<tr><td>".$session_id."</td><td>".$date."</td><td>".$genome."</td><td>".$gtrd_org."</td><td>".$overlap."</td><td><a href='./downloadFiles.php?uid=".$session_id."'><b>Download</b></a></td><tr>";
         ?>
        </tbody>
        </table>
    </div>
    </div>
    <div class="row">
        <div class="panel panel-warning">
          <div class="panel-heading"><b>Input File Statistics</b>&nbsp<a href='<?php echo "./".$inputStat?>' download>(Download Table)</a></div>
    	<table class="table table-bordered">
      <thead>
          <tr>
            <th>Input File Names</th>
            <th>Total Genomic Regions</th>
            <th>Median Region Size (bp)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($data1 as $dat1) {
              $dat11=explode("\t",$dat1);
              echo "<tr><td>".$dat11[0]."</td><td>".$dat11[1]."</td><td>".$dat11[2]."</td><tr>";
          };
          ?>
        </tbody>
        </table>
    </div>
    </div>
<?php
echo '
<div class="row">
<div class="panel panel-warning">
<div class="panel-heading"><b>Overlapped Regions</b>&nbsp<a href="./'.$ovlStat.'" download>(Download Table)</a></div>
<table class="table table-bordered" style="border: 1px solid #ddd;">
<thead>
 <tr>
   <th>Regions</th>
   <th>No. of Regions</th>
   <th>GREAT</th>
   <th>UCSC Browser</th>
 </tr>
</thead>';
#Result data
        echo '<tbody id="results"></tbody>';
	echo "</table>";
	$items=10;
        $pages=ceil(count($data2)/$items);
        $i=1;
        echo '<ul class="pagination pagination" style="margin: 5px;">';
        while($i<=$pages){
        $j=$i++;
        echo '<li id='.$j.'><a href="javascript:void(0);" class="page-link" id='.$j.'>'.$j.'</a></li>';
        };
        echo "</ul>";
        echo '</div></div>';
?>
    </div><!-- row -->
</div>
<div class="col-xs-1 col-xs-offset-9">
  <!-- Replace <shiny server address> with your shiny server path-->
	<a class="btn btn-danger btn-block" href="mailto:imgsb@ils.res.in?Subject=Page : Analysis Result | Run id: <?php echo $session_id;?>" target="_top">
        <i class="glyphicon glyphicon-flag"></i>
        <span style="color: #fff"><b>Report Error</b></span></a>
</div><br>
    </div><!-- container -->
<?php include_once("views/footer.php");?>
</body>
</html>
