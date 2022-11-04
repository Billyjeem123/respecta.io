<?php
header('Content-Type: application/json charset=utf-8');
header("Access-Control-Allow-Methods: PUT, GET, POST");

require('../assets/config.php');

$data = file_get_contents('php://input');
$data = json_decode($data);
$Utility = new Utility();
if ($Utility->validateObj($data)){exit();}

if (isset($data->apptoken)) {
	// code...
	$apptoken = $Utility->input_check($data->apptoken);
	$Auth = new Auth();
	if ($Auth->CheckToken($apptoken)==true) {


		if ($Auth->AuthToken($data->usertoken)==true) {
		// code...
        $user = new Users();
        if($Utility->verifyImageType($data->image) == false){

            echo $Utility->outputData(false, "File format not supported..", null);
			exit();
        }

        if($user->uploadImage($data->image, $data->usertoken) == true){

            echo $Utility->outputData(true, "Image uploaded..", null);
			exit();
        }else{
            echo $Utility->outputData(false, "Unable to process..", null);
			exit();

        }
	}else{

		echo $Utility->outputData(false, "Invalid User....", null);
			exit();
	}
	}else{

		echo $Utility->outputData(false, "Unauthorized access.", null);
			exit();
	}
}else{

		echo $Utility->outputData(false, "Unauthorized access..", null);
			exit();
}
