<?php

/**
 *
 */
class Bugs extends db
{

    public function reportBugs($usertoken, $desc, $filename)
    {
       
        $time = time();
           $sql = " INSERT INTO `tblbug` (usertoken,desc,filename,time)";
         $sql .= " VALUES ('$usertoken','$desc','$filename','$time')";
        $stmt = $this->connect()->prepare($sql);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
