<?php
// copy to /usr/local/issabel
// create a cronjob. 
// Save the file missed_calls.cron that contains   
// * * * * *  root /usr/bin/php -q /usr/local/issabel/missed_calls_to_email.php
// to folder /etc/cron.d/

	//error_reporting(0);
	/*------------------------------------------------------------------------
		# copyright Copyright (C) 2018 sbzsystems.com. All Rights Reserved.
		# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
		# Websites: https://www.sbzsystems.com
	-------------------------------------------------------------------------*/
	
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header('Content-Type: text/html; charset=UTF-8');
	//error_reporting(0);
	
	$host = 'localhost';
	$user = 'root';
	$password = 'XXXXXXXX';
	$db = 'asteriskcdrdb';
	$logfile='/usr/local/issabel/missed_calls.log';
	
	// Connects to your Database
	$link=mysqli_connect("$host", $user, $password) or die(mysqli_error($link));
	mysqli_select_db($link,"$db") or die(mysqli_error($link));
	mysqli_set_charset($link,'utf8'); 
	
	
	//GET SQL TIME INTERVAL 10 MINUTE
	$query="
	
	select calldate AS Timestamp, src AS CallerID from cdr 
	where calldate > date_sub(now(), interval 1 MINUTE) 
	/*and dst=600*/
	
	group by CallerID, Timestamp
	
	";
	
	$data = mysqli_query($link,$query) or die(mysqli_error($link));
	//AND disposition like 'NO ANSWER' 
	$txt='';
	while($alldata = mysqli_fetch_array( $data ))
	{
		
		if (strlen($alldata['CallerID'])>5) {
			$txt=$txt. $alldata['Timestamp'].' <a href="tel:'.$alldata['CallerID'].'">'.$alldata['CallerID'].'</a><br>';  	 	
		}
		
	}
	//
	mysqli_close($link);
	
	//file_put_contents($logfile, '#'.$txt.'#'.$query.'#'."\n", FILE_APPEND | LOCK_EX);
	
	if ($txt) {
		
		
		
		$to = "info@bikemall.gr";
		$subject = "Missed calls";
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: info@bikemall.gr" . "\r\n";
		
		mail($to,$subject,$txt,$headers);
		
	}
	
	
	
	
	
	
?>
