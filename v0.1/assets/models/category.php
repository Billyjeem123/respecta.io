<?php

/**
 *
 */
class category extends db



{

    public function craeteCategory($catname)
    {
        if ($this->catExists($catname) == true) {

            $time = time();
            $sql =  " INSERT INTO  tblcategory(catname, time) ";
            $sql .=  " VALUES(:catname, :time)";
            $stmt =  $this->connect()->prepare($sql);
            $stmt->bindParam(':catname', $catname);
            $stmt->bindParam(':time', $time);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            $echo  = new Utility;
            $echo->outputData(false, ' ' . $catname . ' already exists', null);
            exit();
        }
    }


    public function fetchCategory():array
    {
        $dataArray = array();
        $sql = " SELECT * FROM tblcategory";
        $stmt =  $this->connect()->prepare($sql);
        if (!$stmt->execute()) {
            return false;
        } else {
            $count = $stmt->rowCount();
            if ($count > 0) {
                $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($properties as $tribute) {
                    $array = [
                        'catid' => ($tribute['id']),
                        'catname' => ($tribute['catname']),
                        'created' => date("D d M, Y: H", $tribute['time'])
                    ];

                    array_push($dataArray, $array);
                }
                return $dataArray;
            } else {

                $array = ["success" => false, "message" => "No category available", 'data' => null];
                $return = json_encode($array);
                echo "$return";
                exit();
            }
        }
    }

    //    Check if  catExists
    public function catExists($catname)
    {

        $sql = " SELECT catname from tblcategory where catname = :catname";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':catname', $catname);
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 0) {
                return  true;
            } else {
                return false;
            }
        }
    }

    public function deleteCategory($catid)
    {

        $sql = " DELETE  from tblcategory where id = :catid";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':catid', $catid);
        if ($stmt->execute()) {
            return  true;
        } else {

            return false;
        }
    }

    public function updateCategory($catname, $catId)
    {

        $sql = " UPDATE tblcategory SET catname = :catname WHERE ";
        $sql .= " id = :catid ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':catname', $catname);
        $stmt->bindParam(':catid', $catId);
        if ($stmt->execute()) {
            return  true;
        } else {

            return false;
        }
    }

    public function findCategory($catname)
    {

        $sql = " SELECT  catname FROM  tblcategory WHERE id = :catname ";
        $stmt = $this->connect()
        ->prepare($sql);
       $stmt->bindParam(':catname', $catname);
       if(!$stmt->execute()){
        return false;
       }else{
           $dbcatname = $stmt->fetchColumn();
           return $dbcatname;

       }
    }
}
