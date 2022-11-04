<?php
header('Content-Type: application/json charset=utf-8');
header("Access-Control-Allow-Methods: PUT, GET, POST");

require_once('../assets/config.php');
$data = file_get_contents('php://input');
$data = json_decode($data);
$Utility = new Utility();
if($Utility->validateObj($data)){exit();}
if (isset($data->apptoken)) {
	// code...
	$apptoken = $Utility->input_check($data->apptoken);
	$Auth = new Auth();
	if ($Auth->CheckToken($apptoken)==true) {
		// code...

if ($Utility->validateEmail($data->mail)==true) {
	// code...
	$User = new Users();
	$login = $User->tryLogin($data->mail, $data->pword);
	if ($login !==false) {
		// code...
		echo $Utility->outputData(true, "Login successful..", $login);
			exit();
	}else{
		echo $Utility->outputData(false, $_SESSION['err'], null);
			exit();
	}
}else{
	echo $Utility->outputData(false, "Invalid email.", null);
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

?>