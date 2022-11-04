<?php

/**
 *
 */
class  authNotifies  extends db
{

    public function authNotify($usertoken, $theOwner, $context){
        $time = time();
        $sql = " INSERT INTO tbl_notification(usertoken, theOwner, context, time) ";
        $sql .= " VALUES (:usertoken, :theOwner, :context, :time)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':theOwner', $theOwner);
        $stmt->bindParam(':context', $context);
        $stmt->bindParam(':time', $time);
      
        if (!$stmt->execute()) {
            // code...
            return false;
        } else {
                return true;
    }

}
public function fetchNotify($theOwner)
    {
        $dataArray = array();
        $sql = " SELECT * FROM tbl_notification";
        $sql .= " WHERE theOwner = :theOwner ";
        $sql .= " ORDER BY id DESC ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':theOwner', $theOwner);
        if (!$stmt->execute()) {
            return false;
        } else {
            $count = $stmt->rowCount();
            if ($count > 0) {
                $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($properties as $tribute) {
                    $array = [
                        'notifyid' => ($tribute['id']),
                        'context' => ($tribute['context']),
                        'datePosted' => date("D d M, Y: H", $tribute['time'])
                    ];
                    array_push($dataArray, $array);
                }
                return $dataArray;
            } else {
                $array = ["success" => false, "message" => "Notfication is empty", 'data' => null];
                $return = json_encode($array);
                echo "$return";
                exit();
            }
        }
    }

}
