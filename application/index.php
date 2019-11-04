<!DOCTYPE html>
<html lang="en">
<head>
<title>BedSect : A tool for multiple genomic region overlap</title>
<meta http-equiv=“Cache-Control” content=“no-cache, no-store, must-revalidate” />
<meta http-equiv=“Pragma” content=“no-cache” />
<meta http-equiv=“Expires” content=“0" />
<meta charset="UTF-8">
<link rel="stylesheet" href="./assets/css/jquery.fileupload.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.css" />
<link href="./assets/css/home.css" rel="stylesheet">
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122996757-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-122996757-1');
</script>
</head>
<body>
<?php 
session_start();
$random = substr(md5(rand()), 0, 10);
$_SESSION["userName"] = $random;
include_once("views/nav.php"); 
?>
  <div class="container" style="padding:10px 20px;">
    <h2>BedSect V3</h1>
	<form id="fileupload" action="./" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="//fileupload_using_blueimp_jquery_code/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="well row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
        <h4> Upload Files </h4>
		<label for="browse">Browse :</label>
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Select Files</span>
                    <input type="file" name="files[]" multiple>
                </span>
		<label for="upload">Upload files :</label>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Upload</span>
                </button>
        <label for="rand">Select all: </label> 
                <input type="checkbox" class="toggle">
                <button type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span> Remove </span>
                </button>
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" style="margin-bottom:1px;" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
<!--extra form fields-->
<div class="col-lg-12">
<div class="form-inline">
 <br>
  <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" class="form-control" name="email" id="email" required value="">
  </div> &nbsp&nbsp
  <div class="form-group">
	<label for="genome">Select Genome for <a href='http://great.stanford.edu/public/html/' target=_blank>GREAT</a> Analysis:</label>
	<a href="#" data-toggle="tooltip" title="The selected genome will be also used for UCSC genome browser." data-placement="right" ><span class="glyphicon glyphicon-info-sign"></span></a>
	    <select required class="form-control" id="genome" name="genome">
	      <option value="none">None</option>
	      <option value="hg38">Human GRCh38</option>
              <option value="hg19">Human hg19</option>
              <option value="mm10">Mouse mm10</option>
              <option value="zv9">Zebrafish zv9</option>
            </select>
  </div> &nbsp&nbsp
  <div class="form-group">
    <label for="overlap">Overlap Size (bp):</label>
    <input type="number" class="form-control" name="overlap" id="overlap" onkeypress="return event.charCode >= 48;" value="1" style="width: 90px;" required>
  </div>&nbsp&nbsp<br><br>
  <div class="form-group">
	<label for="genome">Access <a href='http://gtrd.biouml.org/' target=_blank>Gene Transcription Regulation Database(GTRD)</a> datasets:</label>
	<a href="#" data-toggle="tooltip" title="Use the checkbox to expand/collapse GTRD search box." data-placement="right" ><span class="glyphicon glyphicon-info-sign"></span></a>
        <input type="checkbox" id="gtrd-check" onclick="gtrdLoad()">
  </div> &nbsp&nbsp <br>
    <div id = "gtrd-box" style = "display:none"> <!-- Gtrd start -->
        <div class="form-group">
			<label for="Gtrd_org">Organism: </label>
		<select class="selectpicker" data-live-search="true" id="orgSel">
            <option value="">Nothing Selected</option>
			<option value="homsap">Homo sapiens</option>
			<option value="musmus">Mus musculus</option>
			<option value="danrer">Danio rerio</option>
                        <!--<option value="aratha">Arabidopsis thaliana</option>-->
                        <option value="ratnor">Rattus norvegicus</option>
			<option value="dromel">Drosophila melanogaster</option>
                        <option value="scerev">Saccharomyces cerevisiae</option>
                        <option value="spombe">Schizosaccharomyces pombe</option>
                        <!--<option value="caeele">Caenorhabditis elegans</option>-->
		</select>
  		</div>
        <label for="Gtrd_tf">Transcription Factor Name: </label>
        <select class="selectpicker" id="tf_select" data-show-subtext="false" data-live-search="true" ></select>
        <label for="Gtrd_cell">Cel line/Tissue: </label>
        <select class="selectpicker" id="cell_select" data-show-subtext="false" data-live-search="true"></select>
        <div id="gtrd_result"></div>
    </div><!-- Gtrd end -->
        &nbsp&nbsp<br>
  <div class="form-group">
		<label for="run"> Run BedSect: </label>
                <button type="button" id="runAPP" onclick="runApp()" value="<?php echo $random;?>" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-play-circle"></i>
                    <span>Start Job</span>
                </button>
  </div>
</div>
</div>
<!--end exhta fields-->
        </div>
        <!-- The table listing the files available for upload/download -->
        <div id="gtrd_added" style = "display:none">
        <br><h4>GTRD files added for comparison :</h4><br>
        <table class='table table-striped gtrd_search' id="get_exp">
        <thead>
        <th>Experiment</th><th>Antibody</th><th>Factor</th><th>Origin/Cell line</th><th>Treatment</th><th>Reference</th><th>Remove</th>
        </thead>
        <tbody id= "gtrd_list">
        </tbody>
        </table>
        <br><h4>User files:</h4>
        </div>
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>
	<br>
<!--info panel starts-->
<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">User Instructions</h3>
        </div>
        <div class="panel-body">
<div class="alert alert-danger">
  <b>Allowed special characters in file name are '.' , '_' and '-'. Uploading multiple files with same name will lead to error the analysis process.</b>
</div>
                <a type="button" class="btn btn-primary btn-md" href="./result.php?uid=cdc37414f0dff82">
                    <i class="glyphicon glyphicon-cloud-download"></i>
                    <span>With Genome Demo</span>
                </a>
                &nbsp&nbsp
                <a type="button" class="btn btn-primary btn-md" href="./result.php?uid=33ad9b5d545c1ee">
                    <i class="glyphicon glyphicon-cloud-download"></i>
                    <span>Without Genome Demo</span>
                </a>
		&nbsp&nbsp
                <a type="button" class="btn btn-danger btn-md" href="./BedSect_demo.zip" download>
                    <i class="glyphicon glyphicon-download-alt"></i>
                    <span>Download Demo Data</span>
                </a>
        <h4>Input file structure</h4>
        <p> All the inputs should follow the <a href="https://asia.ensembl.org/info/website/upload/bed.html" target=_blank>standard bed file format</a> without any header. An example provided below.</p>
        <pre>
chr1  213941196  213942363
chr1  213942363  213943530
chr1  213943530  213944697
chr2  158364697  158365864
chr2  158365864  158367031
</pre>
<b> For further references visit <a href="./help.php">Help & FAQs</a> page.</b>
<br>
        <h4>Analysis Parameters</h4>
        <li> To get a copy of analysis report fill email id field.</li>
        <li> Select a genome build for GREAT analysis and visualization in UCSC Genome Browser.</li>
        <li> Report bugs to our <a href='https://github.com/sraghav-lab/BedSect' target=_blank>Github page</a>.</li>
        </div>
    </div> <!--info panel ends-->
  </div>
<div id="alert"></div>
<div class="modal hide fade" id="loading">
  <div class="modal-body">
    <img src="./assets/loading.gif">
  </div>
</div>
<?php include_once("./views/footer.php");?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="./assets/js/jquery.ui.widget.js"></script>
<script src="./assets/js/tmpl.min.js"></script>
<script src="./assets/js/jquery.iframe-transport.min.js"></script>
<script src="./assets/js/jquery.fileupload.min.js"></script>
<script src="./assets/js/jquery.fileupload-process.min.js"></script>
<script src="./assets/js/jquery.fileupload-validate.min.js"></script>
<script src="./assets/js/jquery.fileupload-ui.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="./assets/js/app.js"></script>
<script src="./assets/js/common.js"></script>
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Discard</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Discard</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
</body>
</html>
