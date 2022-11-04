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
		// code...
		$user = new Users();

		// echo $data->mail;
		if ($Utility->validateEmail($data->mail) == false) {

			echo $Utility->outputData(true, "Invalid email account..", null);
			exit();
		}

		if ($user->EmailExist($data->mail) == true) {
			echo $Utility->outputData(false, "An account already exists with this email..", null);
			exit();
		} else {

			if ($user->createAdmin($data->mail, $data->fname, $data->lname, $data->pword) == true) {

				echo $Utility->outputData(true, "Registeration successfull..", null);
				exit();
			} else {
				echo $Utility->outputData(false, "Failed to create account..", null);
				exit();
			}
		}
	} else {
		echo $Utility->outputData(false, "Unauthorized access..", null);
		exit();
	}
} else {
	echo $Utility->outputData(false, "Unauthorized access..", null);
	exit();
}
