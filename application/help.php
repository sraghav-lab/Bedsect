<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv=“Cache-Control” content=“no-cache, no-store, must-revalidate” />
    <meta http-equiv=“Pragma” content=“no-cache” />
    <meta http-equiv=“Expires” content=“0" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A far better approach for intersecting bed files">
    <meta name="author" content=" Some ILS students">
    <link rel="icon" href="./assets/favicon.ico">
    <title>Help Page</title>
    <!-- All style files-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link href="./assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
 <!--include the nav bash here -->
	<?php include_once("views/nav.php");?>
</head>
<body>

<div class="container" style="border:0px solid #d6d6d6; min-height:600px;">
      <div class="page-header">
        <h2>Help & FAQs</h2>
        <p class="lead">BedSect V3</p>
      </div>
	<div class="panel panel-warning">
      <div class="panel-heading"><strong>What is BedSect?</strong></div>
      <div class="panel-body" style="margin: 10px;">
<br>
<b>BedSect</b> is a web server tool to overlap multiple genomic regions datasets either uploaded or selected from the <a href="http://gtrd.biouml.org/" target=_blank>GTRD</a> database to  perform functional annotation of the unique as well as intersecting genomic regions using GREAT and UCSC. The integrated ShinyApp can be used to generate publication ready figures.
<br><br>
	</div><!--Panel body ends-->
	</div><!--Panel ends-->
        <div class="panel panel-warning">
      <div class="panel-heading"><strong>Does BedSect has a stand-alone version?</strong></div>
      <div class="panel-body" style="margin: 10px;">
<br>
No, the tool is only available as a web server utility, but we are working on a stand-alone version. 
<br><br>
        </div><!--Panel body ends-->
        </div><!--Panel ends-->
	<div class="panel panel-warning">
      <div class="panel-heading"><strong>Input file type and limits</strong></div>
      <div class="panel-body" style="margin: 10px;">
<br>
The <b>BedSect</b> only supports genomic region files with BED (browser extention format) and formatting as input. The upload limit per file is <strong>2 Megabyte</strong>. For functional annotation select the appropriate genome version beforehand. Each session data will be stored for 30 days from the date of analysis.
<br><strong>Input File Structure</strong>
<br><pre>chr1  213941196  213942363
chr1  213942363  213943530
chr1  213943530  213944697
chr2  158364697  158365864
chr2  158365864  158367031
</pre>
<p class="text-justify">First column will contain the standard chromosome name, 2<sup>nd</sup> column will have start position and 3<sup>rd</sup> column will contain the end position. Strand details is not necessary for the analysis. With each run of the analysis the server will generate a unique <kbd>Session id</kbd> which can be used for future access of the results. On the last date of each month the reports and the analysis details will be cleared to save storage space. So we recommend you to keep a backup of input files for future analysis.</p>
* Do not include any file header.
<br>Ref: <a href="https://asia.ensembl.org/info/website/upload/bed.html" target=_blank>Ensembl.org</a>
        </div><!--Panel body ends-->
        </div><!--Panel ends-->
        <div class="panel panel-warning">
      <div class="panel-heading"><strong>Tool Details</strong></div>
      <div class="panel-body" style="margin: 10px;">
<h4>Homepage</h4>
<img src="assets/fig_1_bedsect.png" alt="Homepage BedSect" class="img-responsive center-block" />
<strong>Options: (Ref: Fig. 1)</strong>
<li>1. Select files to upload.</li>
<li>2. Click here to upload all the files.</li>
<li>3. This button can be used to remove any uploaded files</li>
<li>4. User should provide their email id to obtain analysis result for future reference (mandatory)</li>
<li>5. Genome of an organism (e.g use the exact version used during Peak calling from ChIPseq data) can be selected to perform functional
annotation using GREAT and visualization in UCSC genome browser.</li>
<li>6. Users can choose the overlap size of intersecting regions</li>
<li>7. click on the check box to access the GTRD datasets.</li>
<li>8. Finally, click here to start the overlap analysis .</li>
<br>
<h4>Result page</h4>
<img src="assets/fig_2_bedsect.png" alt="Result Page BedSect" class="img-responsive center-block" />
<strong>Details of the result page.(Ref: Fig. 2)</strong>
<li>1. Click here to generate UpSet and correlation plot using ShinyApp</li>
<li>2. Click here to download the BED files</li>
<li>3. Click Submit button to perform functional annotation using UCSC</li>
<li>4. Click Submit button to perform functional annotation using UCSC</li>
        </div><!--Panel body ends-->
        </div><!--Panel ends-->
  </div>
</body>
<?php include("views/footer.php");?>
</html>
