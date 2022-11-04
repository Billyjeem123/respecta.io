<?php
header('Content-Type: application/json charset=utf-8');
header("Access-Control-Allow-Methods: PUT, GET, POST");

require('../../assets/config.php');

$data = file_get_contents('php://input');
$data = json_decode($data);
$Utility = new Utility();
if ($Utility->validateObj($data)) {
	exit();
}

// echo $data->apptoken;
if (isset($data->apptoken)) {
	// code...
	$apptoken = $Utility->input_check($data->apptoken);
	$Auth = new Auth();
	if ($Auth->CheckToken($apptoken) == true) {

		if ($Auth->AuthToken($data->usertoken) == true) {
			$tribute = new Tribute();
			if ($tribute->uploadTribute($data->usertoken, $data->filename,
			 $data->aboutTribute, $data->tributeDate, $data->catId) == true) {

				echo $Utility->outputData(false, "Tribute uploaded..", null);
				exit();
			} else {

				echo $Utility->outputData(false, "Unable to process..", null);
				exit();
			}
		} else {
			echo $Utility->outputData(false, "Invalid User", null);
			exit();
		}
	} else {
		echo $Utility->outputData(false, "Unauthorized access.", null);
		exit();
	}
} else {
	echo $Utility->outputData(false, "Unauthorized access..", null);
	exit();
}
