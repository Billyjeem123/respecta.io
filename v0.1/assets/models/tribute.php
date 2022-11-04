<?php

/**
 *
 */
class Tribute extends db
{

    public function uploadTribute($usertoken, $filename, $context, $tribute_date, $catId)
    {

        $utility = new Utility();
        $datePosted = time();
        $tributeToken =  $utility->generateAlphaNumericOTP(12);
        // $tribute_dates = date($tribute_date);
        $sql = "INSERT INTO tbltribute (usertoken, filename, context, tribute_date, cat_id, date_posted, tributeToken)";
        $sql .= " VALUES (:usertoken, :filename, :context, :tribute_date, :catId, :datePosted, :tributeToken)";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':context', $context);
        $stmt->bindParam(':tribute_date', $tribute_date);
        $stmt->bindParam(':catId', $catId);
        $stmt->bindParam(':datePosted', $datePosted);
        $stmt->bindParam(':tributeToken', $tributeToken);
        // return "New records created successfully $name";
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }

    public function fetchTribute($usertoken)
    {
        $dataArray = array();
        $sql = " SELECT * FROM tbltribute ";
        $sql .= " ORDER BY id DESC ";
        $stmt =  $this->connect()->prepare($sql);
        if (!$stmt->execute()) {
            return false;
        } else {
            $count = $stmt->rowCount();
            if ($count > 0) {
                $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($properties as $tribute) {
                    $cat = new category();
                    $user = new Users();
                    $fname =  $user->fetchByToken($tribute['usertoken']);
                    $status = $this->likeStatus($usertoken, $tribute['id']);
                    $array = [
                        'postid' => ($tribute['id']),
                        'postedBy' => ($fname['fname']),
                        'aboutTribute' => ($tribute['context']),
                        'dueDate' => ($tribute['tribute_date']),
                        'catId' => $cat->findCategory($tribute['cat_id']),
                        'tributeToken' => ($tribute['tributeToken']),
                        'tributeImage' => ($tribute['filename']),
                        'likestatus' => ($status),
                        'created' => date("D d M, Y: H", $tribute['date_posted'])
                    ];

                    array_push($dataArray, $array);
                }
                return $dataArray;
            } else {

                $array = ["success" => false, "message" => "No feeds available", 'data' => null];
                $return = json_encode($array);
                echo "$return";
                exit();
            }
        }
    }

    public function fetchTributes()
    {
        $dataArray = array();
        $sql = " SELECT * FROM tbltribute ";
        $sql .= " ORDER BY id DESC ";
        $stmt =  $this->connect()->prepare($sql);
        if (!$stmt->execute()) {
            return false;
        } else {
            $count = $stmt->rowCount();
            if ($count > 0) {
                $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($properties as $tribute) {
                    $cat = new category();
                    $user = new Users();
                    $fname =  $user->fetchByToken($tribute['usertoken']);
                    $array = [
                        'postid' => ($tribute['id']),
                        'postedBy' => ($fname['fname']),
                        'aboutTribute' => ($tribute['context']),
                        // 'dueDate' => date("D d M, Y: H", $tribute['tribute_date']),
                        'catId' => $cat->findCategory($tribute['cat_id']),
                        'tributeToken' => ($tribute['tributeToken']),
                        'tributeImage' => ($tribute['filename']),
                        'created' => date("D d M, Y: H", $tribute['date_posted'])
                    ];

                    array_push($dataArray, $array);
                }
                return $dataArray;
            } else {

                $array = ["success" => false, "message" => "No feeds available", 'data' => null];
                $return = json_encode($array);
                echo "$return";
                exit();
            }
        }
    }

    public function likeStatus($usertoken, $postid)
    {

        $sql = " SELECT * FROM  tbl_like_post ";
        $sql .= " WHERE usertoken = '{$usertoken}' ";
        $sql .= " and  tributeid = '{$postid}' ";
        $stmt =  $this->connect()->prepare($sql);
        if ($stmt->execute()) {
            $count = $stmt->rowCount();
            if ($count == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function cmtlikeStatus($usertoken, $commentid)
    {

        $sql = " SELECT * FROM  like_comment ";
        $sql .= " WHERE usertoken = '{$usertoken}' ";
        $sql .= " and  commentid = '{$commentid}' ";
        $stmt =  $this->connect()->prepare($sql);
        if ($stmt->execute()) {
            $count = $stmt->rowCount();
            if ($count == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function fetchTributeByToken($tributeToken, $usertoken)
    {
        $dataArray = array();
        $sql = " SELECT * FROM tbltribute";
        $sql .= " WHERE tributeToken = :tributeToken";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':tributeToken', $tributeToken);
        if (!$stmt->execute()) {
            return false;
        } else {
            $count = $stmt->rowCount();
            if ($count > 0) {
                $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($properties as $tribute) {
                    $cat = new category();
                    $user = new Users();
                    $fname =  $user->fetchByToken($tribute['usertoken']);
                    $status = $this->likeStatus($usertoken, $tribute['id']);
                    $array = [
                        'postid' => ($tribute['id']),
                        'postedBy' => ($fname['fname']),
                        'aboutTribute' => ($tribute['context']),
                        'catId' => $cat->findCategory($tribute['cat_id']),
                        'tributeImage' => ($tribute['filename']),
                        'tributeToken' => ($tribute['tributeToken']),
                        'likestatus' => ($status),
                        'created' => date("D d M, Y: H", $tribute['date_posted'])
                    ];

                    array_push($dataArray, $array);
                }
                return $dataArray;
            } else {

                $array = ["success" => false, "message" => "No feeds available", 'data' => null];
                $return = json_encode($array);
                echo "$return";
                exit();
            }
        }
    }

    public function fetchTributeByUsertoken($usertoken)
    {
        $dataArray = array();
        $sql = " SELECT * FROM tbltribute";
        $sql .= " WHERE usertoken = :usertoken";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        if (!$stmt->execute()) {
            return false;
        } else {
            $count = $stmt->rowCount();
            if ($count > 0) {
                $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($properties as $tribute) {
                    $cat = new category();
                    $user = new Users();
                    $fname =  $user->fetchByToken($tribute['usertoken']);
                    $array = [
                        'postedBy' => ($fname['fname']),
                        'aboutTribute' => ($tribute['context']),
                        'tributeDate' => ($tribute['tribute_date']),
                        'catId' => $cat->findCategory($tribute['cat_id']),
                        'tributeImage' => ($tribute['filename']),
                        'tributeToken' => ($tribute['tributeToken']),
                        'created' => date("D d M, Y: H", $tribute['date_posted'])
                    ];

                    array_push($dataArray, $array);
                }
                return $dataArray;
            } else {

                $array = ["success" => false, "message" => "No feeds available", 'data' => null];
                $return = json_encode($array);
                echo "$return";
                exit();
            }
        }
    }

    public function createComment($usertoken, $theOwner, $postid, $comment, $filename, $filetype)
    {
        $time = time();

        $sql = " INSERT INTO  tblcomment(usertoken , theOwner, postid, comment,filename, filetype, date_posted) ";
        $sql .= " VALUES(:usertoken, :theOwner, :postid, :comment, :filename, :filetype, :datePosted) ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':theOwner', $theOwner);
        $stmt->bindParam(':postid', $postid);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':filetype', $filetype);
        $stmt->bindParam(':datePosted', $time);
        // return "New records created successfully $name";
        if ($stmt->execute()) {
            $user = new Users();
            $mail =  $user->fetchByToken($usertoken);
            $mailOwner =  $user->fetchByToken($theOwner);
            $context = "<a href=''>$mail[fname]  added a new comment on your Post</a>";
            $authNotifies = new authNotifies();
            if ($authNotifies->authNotify($usertoken, $theOwner, $context)) {
                $mailer = new Mailer();
                // $mailer->AuthComment($mailOwner['mail']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function fetchComment($postid, $usertoken)
    {
        $dataArray = array();
        $sql = " SELECT * FROM tblcomment";
        $sql .= " WHERE postid = :postid ";
        $sql .= " ORDER BY  id DESC ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':postid', $postid);
        if (!$stmt->execute()) {
            return false;
        } else {
            $count = $stmt->rowCount();
            if ($count > 0) {
                $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($properties as $tribute) {
                    $user = new Users();
                    $fname =  $user->fetchByToken($tribute['usertoken']);
                    $status = $this->cmtlikeStatus($usertoken, $tribute['id']);
                    $array = [
                        'commentid' => ($tribute['id']),
                        'postedBy' => ($fname['fname']),
                        'postid' => ($tribute['postid']),
                        'comment' => ($tribute['comment']),
                        'fileContent' => ($tribute['filename']),
                        'likes' => ($tribute['likes']),
                        'likeStatus' => ($status),
                        'datePosted' => date("D d M, Y: H", $tribute['date_posted'])
                    ];

                    array_push($dataArray, $array);
                }
                return $dataArray;
            } else {

                $array = ["success" => false, "message" => "No comment available", 'data' => null];
                $return = json_encode($array);
                echo "$return";
                exit();
            }
        }
    }

    public function createReply($usertoken, $theOwner, $postid, $reply, $commentid, $filename)
    {
        $time = time();

        $sql = " INSERT INTO  tblreply(usertoken , theOwner, postid, reply,commentid, filename, time) ";
        $sql .= " VALUES(:usertoken, :theOwner, :postid,  :reply, :commentid, :filename, :datePosted) ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':theOwner', $theOwner);
        $stmt->bindParam(':postid', $postid);
        $stmt->bindParam(':reply', $reply);
        $stmt->bindParam(':commentid', $commentid);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':datePosted', $time);
        // return "New records created successfully $name";
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function fetchReply($commentid)
    {
        $dataArray = array();
        $sql = " SELECT * FROM tblreply";
        $sql .= " WHERE commentid = :commentid ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':commentid', $commentid);
        if (!$stmt->execute()) {
            return false;
        } else {
            $count = $stmt->rowCount();
            if ($count > 0) {
                $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $user = new Users;
                foreach ($properties as $tribute) {
                    $mail =  $user->fetchByToken($tribute['usertoken']);
                    $array = [
                        'replyid' => ($tribute['id']),
                        'postedBy' => ($mail['fname']),
                        'theOwner' => ($tribute['theOwner']),
                        'postid' => ($tribute['postid']),
                        'reply' => ($tribute['reply']),
                        'commentid' => ($tribute['commentid']),
                        'fileContent' => ($tribute['filename']),
                        'likes' => ($tribute['likes']),
                        'datePosted' => date("D d M, Y: H", $tribute['time'])
                    ];

                    array_push($dataArray, $array);
                }
                return $dataArray;
            } else {

                $array = ["success" => false, "message" => "No comment available", 'data' => null];
                $return = json_encode($array);
                echo "$return";
                exit();
            }
        }
    }

    public function deletePost($usertoken, $postid)
    {
        $sql =  " DELETE FROM tbltribute WHERE usertoken = :usertoken ";
        $sql .=  " AND id= :postid ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':postid', $postid);
        if ($stmt->execute()) {
            if ($this->deleteCmtUnderneath($postid)) {
                $this->deleteReplyUnderneath($postid);
                $this->deletelikeUnderPost($postid);
            }
            return true;
        } else {
            return false;
        }
    }

    public function vetoDelete($postid)
    {
        $sql =  " DELETE FROM tbltribute WHERE id = :postid ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':postid', $postid);
        if ($stmt->execute()) {
            if ($this->deleteCmtUnderneath($postid)) {
                $this->deletelikeUnderPost($postid);
            }
            return true;
        } else {
            return false;
        }
    }

    public function deleteCmt($usertoken, $commentid)
    {
        $sql =  " DELETE FROM tblcomment WHERE usertoken = :usertoken ";
        $sql .=  " AND id= :commentid ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':commentid', $commentid);
        if ($stmt->execute()) {
            if ($this->deleteReplyUnderCmt($commentid)) {
                $this->deletelikeUnderCmt($commentid);
            }
            return true;
        } else {
            return false;
        }
    }

    public function deleteReplyUnderCmt($commentid)
    {
        $sql =  " DELETE FROM tblreply WHERE commentid = :commentid ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':commentid', $commentid);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }
    public function deletelikeUnderPost($postid)
    {
        $sql =  " DELETE FROM tbl_like_post WHERE tributeid = :tributeid ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':tributeid', $postid);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }

    public function deletelikeUnderCmt($commentid)
    {
        $sql =  " DELETE FROM like_comment WHERE commentid = :commentid ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':commentid', $commentid);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }

    public function deleteCmtUnderneath($postid)
    {
        $sql =  " DELETE FROM tblcomment WHERE postid = :postid ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':postid', $postid);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }
    public function deleteReplyUnderneath($postid)
    {
        $sql =  " DELETE FROM tblreply WHERE postid = :postid ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':postid', $postid);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }
    public function deleteReply($replyid, $usertoken)
    {
        $sql =  " DELETE FROM tblreply WHERE id = :replyid";
        $sql .= " AND usertoken = :usertoken ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':replyid', $replyid);
        $stmt->bindParam(':usertoken', $usertoken);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateComment($comment, $filename, $commentid, $usertoken)
    {

        $sql = " UPDATE tblcomment SET ";
        $sql .= "comment  = :comment, ";
        $sql .= "filename = :filename ";
        $sql .= " WHERE id = :commentid AND usertoken =:usertoken  ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':commentid', $commentid);
        $stmt->bindParam(':usertoken', $usertoken);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateTribute($aboutTribute, $filename,  $catId, $postid,  $usertoken)
    {

        $sql = " UPDATE tbltribute SET ";
        $sql .= "context  = :aboutTribute, ";
        $sql .= "filename = :filename, ";
        $sql .= "cat_id = :catId ";
        $sql .= " WHERE id = :postid AND usertoken =:usertoken  ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':aboutTribute', $aboutTribute);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':catId', $catId);
        $stmt->bindParam(':postid', $postid);
        $stmt->bindParam(':usertoken', $usertoken);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateReply($reply, $filename, $replyid, $usertoken)
    {

        $sql = " UPDATE tblreply SET ";
        $sql .= "reply  = :reply, ";
        $sql .= "filename = :filename ";
        $sql .= " WHERE id = :replyid AND usertoken =:usertoken  ";
        $stmt =  $this->connect()->prepare($sql);
        $stmt->bindParam(':reply', $reply);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':replyid', $replyid);
        $stmt->bindParam(':usertoken', $usertoken);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getfileType($data)
    {
        $encoded_string = $data;
        $imgdata = base64_decode($encoded_string);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
        return $mime_type;
    }
}
