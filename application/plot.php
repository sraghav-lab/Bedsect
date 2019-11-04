<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv=“Cache-Control” content=“no-cache, no-store, must-revalidate” />
    <meta name="robots" content="noindex, nofollow"/>
    <meta http-equiv=“Pragma” content=“no-cache” />
    <meta http-equiv=“Expires” content=“0" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A far better approach for intersecting bed files">
    <meta name="author" content=" Some ILS students">
    <link rel="icon" href="./assets/favicon.ico">
    <title>Plots</title>
    <!-- All style files-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link href="./assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
 <!--include the nav bash here -->
	<?php include_once("views/nav.php");?>
</head>
<body>
<?php
include_once("config.php");
$session_id = $_GET['uid'];
?>
<div class="container" style="border:0px solid #d6d6d6; min-height:600px;min-width:100%">
<div class="col-xs-4 col-xs-offset-4">
  <!-- Replace <shiny server address> with your shiny server path-->
        <a class="btn btn-danger btn-block" href="./result.php?uid=<?php echo $session_id;?>">
        <i class="glyphicon glyphicon-arrow-left"></i>
        <span style="color: #fff"><b>Go back to result page</b></span></a>
</div>
<iframe id="shiny-server" src="<?php echo $shinyDir.'?'.$session_id?>" style="border: none; width: 100%; height: 1000px" frameborder="0"></iframe>
<div class="col-xs-1 col-xs-offset-9">
  <!-- Replace <shiny server address> with your shiny server path-->
        <a class="btn btn-danger btn-block" href="mailto:imgsb@ils.res.in?Subject=Page : Plots | Run id: <?php echo $session_id;?>" target="_top">
        <i class="glyphicon glyphicon-flag"></i>
        <span style="color: #fff"><b>Report Error</b></span></a>
</div><br>
  </div>
</body>
<?php include("views/footer.php");?>
</html>
