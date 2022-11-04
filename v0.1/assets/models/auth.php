<?php

/**
 * 
 */
class Auth extends db
{
	
	// authenticate apptoken
	public function CheckToken($apptoken='')
	{
		// code...
		 try {
         $sql = "SELECT * FROM apptoken WHERE apptoken = '$apptoken'";
                $stmt = $this->connect()->prepare($sql);
                if (!$stmt->execute()) {
                    $stmt = null;
                  $_SESSION['err'] = "Something went wrong, please try again..";
                    return false;
                }else{
              if($stmt->rowCount() == 0){

           $stmt = null;
           $_SESSION['err'] = "No app found.";
            return false;
            // code...
        }else{
            
           if($biz = $stmt->fetchAll(PDO::FETCH_ASSOC)){

$posts_arr = array();

return true;
    }else{
    	return false;
    }

    }
}
    } catch (PDOException $e) {
        $_SESSION['err'] = $e->getMessage();
  return false;
    }
	}
// / authenticate apptoken
public function AuthToken($usertoken = '')
{
    try{
    $sql = "SELECT * FROM tbluser WHERE usertoken = '$usertoken'";
    $stmt = $this->connect()->prepare($sql);
    if (!$stmt->execute()) {
        $stmt = null;
        $_SESSION['err'] = "Something went wrong, please try again..";
        return false;
    } else {
        if ($stmt->rowCount() == 0) {

            $stmt = null;
            $_SESSION['err'] = "No User found.";
            return false;
            // code...
        } else {

            if ($biz = $stmt->fetchAll(PDO::FETCH_ASSOC)) {

                $posts_arr = array();

                return true;
            } else {
                return false;
            }
        }
    }
}   catch (PDOException $e) {
$_SESSION['err'] = $e->getMessage();
return false;

}

}

}
