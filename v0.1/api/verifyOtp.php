<?php
header('Content-Type: application/json charset=utf-8');
header("Access-Control-Allow-Methods: PUT, GET, POST");

require('../assets/config.php');

$data = file_get_contents('php://input');
$data = json_decode($data);
$Utility = new Utility();
if ($Utility->validateObj($data)) {
	exit();
}

if (isset($data->apptoken)) {
	// code...
	$apptoken = $Utility->input_check($data->apptoken);
	$Auth = new Auth();
	if ($Auth->CheckToken($apptoken) == true) {

		// if ($Auth->AuthToken($data->usertoken) == true) {
		$user = new Users();
		$otpExist = $user->validateOtp($data->mail);
		if ($otpExist) {

			if ($otpExist != $data->userotp) {

				echo $Utility->outputData(false, "Please enter your correct OTP..", null);
				exit();
			} else {
				if ($user->activateAccount($data->mail)) {
					echo $Utility->outputData(true, "Congrats...Account have been activated", null);
					exit();
				}
			}
		} else {
			echo $Utility->outputData(false, "Invalid email..", null);
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
