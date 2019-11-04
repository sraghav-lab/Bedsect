<?php
$session_id = $_GET['uid'];
$zip = new ZipArchive;
$tmp_file = 'tmp/.'.$session_id.'.zip';
$file_path = 'server/files/'.$session_id.'/';
if ($zip->open($tmp_file,  ZipArchive::CREATE | ZIPARCHIVE::OVERWRITE)) {
	$files = new RecursiveIteratorIterator(
    		new RecursiveDirectoryIterator($file_path),
    		RecursiveIteratorIterator::LEAVES_ONLY
	);
	foreach ($files as $name => $file){
    		// Skip directories (they would be added automatically)
		if (!$file->isDir()){
        		// Get real and relative path for current file
	    		$filePath = $file->getRealPath();
        		$relativePath = substr($filePath, strlen($rootPath) + 1);
        		// Add current file to archive
        		$zip->addFile($filePath, basename($relativePath));
    		}
	};
        #$zip->addFile('folder/bootstrap.js', 'bootstrap.js');
	#$zip->addFile('folder/bootstrap.min.js', 'bootstrap.min.js');
	#$zip->addGlob($file_path."*");
        $zip->close();
        #echo 'Archive created!';
        header('Content-disposition: attachment; filename=Bedsect_'.$session_id.'.zip');
        header('Content-type: application/zip');
        readfile($tmp_file);
   } else {
       echo 'Failed!';
   }
?>
