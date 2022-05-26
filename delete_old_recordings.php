<?php



$path    = '/var/spool/asterisk/monitor';

$array=(getDirContents($path));

foreach ($array as $value) {

	$ctime =date("Ymd", filemtime($value) );
	$ntime =   date("Ymd",strtotime('-60 days' ) )  ;
	
	if ($ctime<$ntime) {
		echo   date("d/m/Y", filemtime($value) ) ."     $value \n";
		//unlink ($path .'/'. $file);
	}
	
}





function getDirContents($dir, &$results = array()) {
	$files = scandir($dir);

	foreach ($files as $key => $value) {
		$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
		if (!is_dir($path)) {
			$results[] = $path;
		} else if ($value != "." && $value != "..") {
			getDirContents($path, $results);
			$results[] = $path;
		}
	}

	return $results;
}














?>					