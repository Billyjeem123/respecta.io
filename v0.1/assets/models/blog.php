<?php

/**
 *
 */
class Blog extends db
{


    public function craeteBlog($title, $context, $image, $usertoken)

    {
        $utility = new Utility();
        $postToken =  $utility->generateAlphaNumericOTP(10);
        $time = time();
        $ip = $_SERVER['REMOTE_ADDR'];
        $sql = "INSERT INTO tbl_blog (title, context, date, image, usertoken, postToken )";
        $sql .= " VALUES (:title, :context, :date, :image, :usertoken, :postToken)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':context', $context);
        $stmt->bindParam(':date', $time);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':postToken', $postToken);
        if (!$stmt->execute()) {
            // code...
            return false;
        } else {
            return true;
        }
    }

    public function fetchBlog()
    {
        $dataArray = array();
        $sql = " SELECT * FROM tbl_blog ";
        $sql .= " ORDER BY id DESC ";
        $stmt =  $this->connect()->prepare($sql);
        if (!$stmt->execute()) {
            return false;
        } else {
            $count = $stmt->rowCount();
            if ($count > 0) {
                $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($properties as $tribute) {
                    $user = new Users();
                    $fname =  $user->fetchByToken($tribute['usertoken']);
                    $array = [
                        'blogid' => ($tribute['id']),
                        'postedBy' => ($fname['fname']),
                        'aboutBlog' => ($tribute['context']),
                        'created' => date("D d M, Y: H", $tribute['date']),
                        'blogImage' => ($tribute['image']),
                        'postToken' => ($tribute['postToken'])

                    ];

                    array_push($dataArray, $array);
                }
                return $dataArray;
            } else {

                $array = ["success" => false, "message" => "No Posts available", 'data' => null];
                $return = json_encode($array);
                echo "$return";
                exit();
            }
        }
    }

    public function updateBlog($title, $context, $image, $blogid){

        $sql = " UPDATE tbl_blog SET ";
        $sql .= "title  =  :title, ";
        $sql .= "context = :context, ";
        $sql .= " image = :image ";
        $sql .= " WHERE id = :blogid ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':context', $context);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':blogid', $blogid);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }

    public function fetchBlogByToken($postToken)
    {
        $dataArray = array();
        $sql = " SELECT * FROM tbl_blog ";
        $sql .= " WHERE postToken  = :postToken";
        $stmt =  $this->connect()->prepare($sql);
          $stmt->bindParam(':postToken', $postToken);
        if (!$stmt->execute()) {
            return false;
        } else {
            $count = $stmt->rowCount();
            if ($count > 0) {
                $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($properties as $tribute) {
                    $user = new Users();
                    $fname =  $user->fetchByToken($tribute['usertoken']);
                    $array = [
                        'blogid' => ($tribute['id']),
                        'postedBy' => ($fname['fname']),
                        'aboutBlog' => ($tribute['context']),
                        'created' => date("D d M, Y: H", $tribute['date']),
                        'blogImage' => ($tribute['image']),
                        'postToken' => ($tribute['postToken'])

                    ];

                    array_push($dataArray, $array);
                }
                return $dataArray;
            } else {

                $array = ["success" => false, "message" => "No Posts available", 'data' => null];
                $return = json_encode($array);
                echo "$return";
                exit();
            }
        }
    }

    public function deleteBlog($blogid)
    {

        $sql = " DELETE  from tbl_blog WHERE id = :blogid";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':blogid', $blogid);
        if ($stmt->execute()) {
            return  true;
        } else {

            return false;
        }
    }

}
