<?php
header('Content-Type: application/json charset=utf-8');
header("Access-Control-Allow-Methods: PUT, GET, POST");

require('../assets/config.php');

$data = file_get_contents('php://input');
$data = json_decode($data);
$Utility = new Utility();
// if ($Utility->validateObj($data)){exit();}

if (isset($data->apptoken)) {
	// code...
	$apptoken = $Utility->input_check($data->apptoken);
	$Auth = new Auth();
	if ($Auth->CheckToken($apptoken)==true) {


		if ($Auth->AuthToken($data->usertoken)==true) {

            $bugs = new Bugs();

           $dont =  $bugs->reportBugs($data->usertoken, $data->desc, $data->filename);

            var_dump($dont);

            return false;
          
            if($bugs->reportBugs($data->usertoken, $data->desc, $data->filename)){

                echo $Utility->outputData(true, "Complaint received. we shall address it ASAP ...", null);
			exit();
            }else{

                echo $Utility->outputData(false, "Unable to process ...", null);
                exit();
            }
	}else{

		echo $Utility->outputData(false, "Register to Countinue ....", null);
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
