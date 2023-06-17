<?php
// This opencart script enables seamless integration between platforms such as Issabel, FreePBX, and others, 
// enhancing their functionality by displaying caller ID information based on the clients' data in Opencart.
//   1. Access your Opencart installation directory.
//   2. Locate the folder where your Opencart files are stored.
//   3. Create a new folder within the Opencart directory and name it "issabel."
//   4. Move the script you have to the newly created "issabel" folder.
//By following these steps, you will successfully create the "issabel" folder within Opencart and place the provided script inside it.
	
require "../config.php";
	
$host = DB_HOSTNAME;
$user = DB_USERNAME;
$password = DB_PASSWORD;
$db = DB_DATABASE;
$dbprefix = DB_PREFIX;



	
	$ip=$_SERVER['REMOTE_ADDR'];   // USER'S IP 
	$number=$_REQUEST['number'];   
	$number=str_replace("-", "", $number);
	$number=str_replace("(", "", $number);
	$number=str_replace(")", "", $number);
	$number=str_replace(" ", "", $number);
	
	if (strlen($number)>10) {
		$number = substr($number, -10);
	}

	
	if (!$number) { exit; }
	
	
	
		// Connects to your Database
	$link=mysqli_connect("$host", $user, $password) or die(mysqli_error($link));
	mysqli_select_db($link,"$db") or die(mysqli_error($link));
	
	mysqli_set_charset($link, 'utf8');
	
	
	

	$apo=$number.' UKNOWN';
	
	
	
	
$query="

select firstname, lastname  
from ".$dbprefix."order WHERE
REPLACE(REPLACE(REPLACE(REPLACE(telephone,'-',''),' ',''),'+',''),'.','') like '%".$number."%' 
or REPLACE(REPLACE(REPLACE(REPLACE(fax,'-',''),' ',''),'+',''),'.','') like '%".$number."%'

union ALL

SELECT firstname, lastname 
FROM ".$dbprefix."customer where 
 
REPLACE(REPLACE(REPLACE(REPLACE(telephone,'-',''),' ',''),'+',''),'.','') like '%".$number."%' 
or REPLACE(REPLACE(REPLACE(REPLACE(fax,'-',''),' ',''),'+',''),'.','') like '%".$number."%'

union ALL

SELECT firstname, lastname
FROM ".$dbprefix."address where 
 
REPLACE(REPLACE(REPLACE(REPLACE(custom_field,'-',''),' ',''),'+',''),'.','') like '%".$number."%';


";

//echo $query;
 

	$data = mysqli_query($link,$query) or die(mysqli_error($link)); 
	
	

	while($alldata = mysqli_fetch_array( $data ))
	{
		$name=$alldata['lastname'].' '.$alldata['firstname'];  	 	
		$apo= ($name);
		
		 
		
		
		break;
	}
	
		mysqli_close($link);


/*
if (!$name) {
	
	$apo='';
	$onom=file_get_contents('http://www.domain.com/issabel/phone.php?number='.$number);	
	$onom = mb_convert_encoding($onom, 'UTF-8', 'UTF-8');
	if (strlen($onom)>5) {
		$apo= '*'.trim($onom).'*';
	}
	
	
}
*/
 
	
	if (!$apo) {
		$apo=$number.' UKNOWN';
	}

	
	$apo=str_ireplace("  ", " ", $apo);
	echo greekTOgreeklish($apo);
	
	
	
	
	
	function greekTOgreeklish ($arx) {
		$gr_ =  array("ά", "έ", "ή", "ί", "ό", "ύ", "ώ", "ς" , "ϊ" , "ϋ" );
		$grn_ = array("α", "ε", "η", "ι", "ο", "υ", "ω", "σ", "ι", "υ");
		$arx= str_replace(  $gr_, $grn_,$arx);
		
		$grn_ = array("Α", "Β", "Γ", "Δ", "Ε", "Ζ", "Η", "Θ", "Ι", "Κ", "Λ", "Μ", "Ν", "Ξ", "Ο", "Π", "Ρ", "Σ", "Τ", "Υ", "Φ", "Χ", "Ψ", "Ω");
		$gr_ =  array("α", "β", "γ", "δ", "ε", "ζ", "η", "θ", "ι", "κ", "λ", "μ", "ν", "ξ", "ο", "π", "ρ", "σ", "τ", "υ", "φ", "χ", "ψ", "ω");
		$arx= str_replace(   $grn_,$gr_,$arx);
		
		$grn_ = array("αυρ","αυ","ευθ" ,"ου", "ευ");
		$gr_ =  array("avr","af","efth","ou", "ev"); 
		$arx= str_replace(   $grn_,$gr_,$arx);
		
		$gr_ =  array("α", "β", "γ", "δ", "ε", "ζ", "η", "θ", "ι", "κ", "λ", "μ", "ν", "ξ", "ο", "π", "ρ", "σ", "τ", "υ", "φ", "χ", "ψ", "ω");
		$eng_ = array("a", "b", "g", "d", "e", "z", "i", "th", "i", "k", "l", "m", "n", "ks", "o", "p", "r", "s", "t", "i", "f", "ch", "ps", "o");
		$arx= str_replace(  $gr_, $eng_,$arx);
		
		
		$arx= str_replace('&amp;','&',$arx);
	
		
		
		$arx= strtoupper($arx);
		
		$arx= str_replace('& SIA','',$arx);
		$arx= str_replace('O.E','',$arx);
		$arx= str_replace('O.E.','',$arx);
		
		//$arx= str_replace('&','k',$arx);
		
		return $arx;
	}
	
	function get_between($input, $start, $end)
	{
		$substr = substr($input, strlen($start)+strpos($input, $start), (strlen($input) - strpos($input, $end))*(-1));
		return $substr;
	} 
	
	
?> 
