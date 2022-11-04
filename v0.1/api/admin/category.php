<?php
header('Content-Type: application/json charset=utf-8');
header("Access-Control-Allow-Methods: PUT, GET, POST");

require('../../assets/config.php');
$Utility = new Utility();
$data = file_get_contents('php://input');
$data = json_decode($data);
if ($Utility->validateObj($data)){exit();}

 

if (!empty($data->apptoken)) {
	// code...
	$apptoken = $Utility->input_check($data->apptoken);
	$Auth = new Auth();
	if ($Auth->CheckToken($apptoken) == true) {
		$cat = new category();
			if ($cat->craeteCategory($data->catname) == true) {

				echo $Utility->outputData(true, "$data->catname added..", null);
				exit();
			} else {
				echo $Utility->outputData(false, "Unable to process..", null);
				exit();
			}
	} else {
		echo $Utility->outputData(false, "Unauthorized access..", null);
		exit();
	}
} else {
	echo $Utility->outputData(false, "Unauthorized access..", null);
	exit();
}
