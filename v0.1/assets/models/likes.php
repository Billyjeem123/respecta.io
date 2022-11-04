<?php

/**
 * 
 */
class Likes extends db
{

    public function AuthTributeLikes($usertoken, $postid)
    {
        if ($this->verifyTributeLikes($usertoken, $postid) == true) {

            $this->removeTributeLikes($postid, $usertoken);

            $array = ['success' => false, 'message' => "Unliked", "data" => null];
            $return = json_encode($array);
            echo "$return";
            exit();
        } else {

            if ($this->likeTribute($usertoken, $postid)) {
                exit();
            }
        }
    }

    public function AuthCommenttLikes($usertoken, $commentid)
    {
        if ($this->verifyCmtLikes($usertoken, $commentid) == true) {

            $this->removeCommentLikes($commentid, $usertoken);

            $array = ['success' => false, 'message' => "Unliked", "data" => null];
            $return = json_encode($array);
            echo "$return";
            exit();
        } else {

            if ($this->likeComment($usertoken, $commentid)) {
                exit();
            }
        }
    }

    public function AuthReplyLikes($usertoken, $replyid)
    {
        if ($this->verifyReplyLikes($usertoken, $replyid) == true) {

            $this->removeReplyLikes($replyid, $usertoken);

            $array = ['success' => false, 'message' => "Unliked", "data" => null];
            $return = json_encode($array);
            echo "$return";
            exit();
        } else {

            if ($this->likeReply($usertoken, $replyid)) {
                exit();
            }
        }
    }

    public function verifyCmtLikes($usertoken, $commentid)
    {
        $sql = " SELECT * FROM  like_comment ";
        $sql .= " WHERE usertoken = :usertoken ";
        $sql .= " and  commentid = :commentid ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':commentid', $commentid);
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                return true;
            } else {
                return false;
            }
        } else {
        }
    }

    public function verifyReplyLikes($usertoken, $replyid)
    {
        $sql = " SELECT * FROM  like_reply ";
        $sql .= " WHERE usertoken = :usertoken ";
        $sql .= " and  replyid = :replyid ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':replyid', $replyid);
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                return true;
            } else {
                return false;
            }
        } else {
        }
    }

    public function verifyTributeLikes($usertoken, $postid)
    {
        $sql = " SELECT * FROM  tbl_like_post ";
        $sql .= " WHERE usertoken = :usertoken ";
        $sql .= " and  tributeid = :tributeid ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':tributeid', $postid);
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                return true;
            } else {
                return false;
            }
        } else {
        }
    }

    public function removeReplyLikes($replyid, $usertoken)
    {

        $sql = "  DELETE FROM  like_reply ";
        $sql .= " WHERE replyid = :replyid ";
        $sql .= " and  usertoken= :usertoken ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':replyid', $replyid);
        $stmt->bindParam(':usertoken', $usertoken);
        if ($stmt->execute()) {
            $this->minusReplyLikes($replyid);
        } else {
            return true;
        }
    }

    public function removeCommentLikes($commentid, $usertoken)
    {

        $sql = "  DELETE FROM  like_comment ";
        $sql .= " WHERE commentid = :commentid ";
        $sql .= " and  usertoken= :usertoken ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':commentid', $commentid);
        $stmt->bindParam(':usertoken', $usertoken);
        if ($stmt->execute()) {
            $this->minusCmtLikes($commentid);
        } else {
            return true;
        }
    }

    public function  minusReplyLikes($replyid)
    {

        $sql = " UPDATE tblreply  SET likes";
        $sql .= " = likes -1   WHERE  id = '$replyid'  ";
        $stmt = $this->connect()
            ->prepare($sql);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }

    public function  minusCmtLikes($commentid)
    {

        $sql = " UPDATE tblcomment  SET likes";
        $sql .= " = likes -1   WHERE  id = '$commentid'  ";
        $stmt = $this->connect()
            ->prepare($sql);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }

    public function likeTribute($usertoken, $postid)
    {

      $time = time();
      $user = new Users();
      $mail  =  $user->fetchByToken($usertoken);

        $sql = " INSERT INTO  tbl_like_post(usertoken, tributeid, time) ";
        $sql .= " VALUES(:usertoken, :tributeid, :time) ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':tributeid', $postid);
        $stmt->bindParam(':time', $time);
        if ($stmt->execute()) {
            $this->plusLikes($postid);
            // if($this->plusLikes($postid)){
            //     $mailer = new Mailer();
            //     $mailer->likesNotification($mail['mail'], $postid);
            //     // return true;
            // }

        } else {
            return false;;
        }
    }

    public function likeComment($usertoken, $commentid)
    {

      $time = time();
      $user = new Users();
      $mail  =  $user->fetchByToken($usertoken);

        $sql = " INSERT INTO  like_comment(usertoken, commentid, time) ";
        $sql .= " VALUES(:usertoken, :commentid, :time) ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':commentid', $commentid);
        $stmt->bindParam(':time', $time);
        if ($stmt->execute()) {
            $this->plusCmtLikes($commentid);
            // if($this->plusLikes($postid)){
            //     $mailer = new Mailer();
            //     $mailer->likesNotification($mail['mail'], $postid);
            //     // return true;
            // }

        } else {
            return false;;
        }
    }

    public function likeReply($usertoken, $replyid)
    {

      $time = time();
      $user = new Users();
      $mail  =  $user->fetchByToken($usertoken);

        $sql = " INSERT INTO  like_reply(usertoken, replyid, time) ";
        $sql .= " VALUES(:usertoken, :replyid, :time) ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':usertoken', $usertoken);
        $stmt->bindParam(':replyid', $replyid);
        $stmt->bindParam(':time', $time);
        if ($stmt->execute()) {
            $this->plusReplyLikes($replyid);
            // if($this->plusLikes($postid)){
            //     $mailer = new Mailer();
            //     $mailer->likesNotification($mail['mail'], $postid);
            //     // return true;
            // }

        } else {
            return false;;
        }
    }

    public function removeTributeLikes($postid, $usertoken)
    {

        $sql = "  DELETE FROM  tbl_like_post ";
        $sql .= " WHERE tributeid = :tributeid ";
        $sql .= " and  usertoken= :usertoken ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':tributeid', $postid);
        $stmt->bindParam(':usertoken', $usertoken);
        if ($stmt->execute()) {
            $this->minusLikes($postid);
        } else {
            return true;
        }
    }

    public function  minusLikes($postid)
    {

        $sql = " UPDATE tbltribute  SET likes";
        $sql .= " = likes -1   WHERE  id = '$postid'  ";
        $stmt = $this->connect()
            ->prepare($sql);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }

    public function  plusLikes($postid)
    {

        $sql = " UPDATE tbltribute  SET likes";
        $sql .= " = likes +1   WHERE  id = '$postid'  ";
        $stmt = $this->connect()
            ->prepare($sql);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }
    public function  plusCmtLikes($commetid)
    {

        $sql = " UPDATE tblcomment  SET likes";
        $sql .= " = likes +1   WHERE  id = '$commetid'  ";
        $stmt = $this->connect()
            ->prepare($sql);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }

    public function  plusReplyLikes($replyid)
    {

        $sql = " UPDATE tblreply  SET likes";
        $sql .= " = likes +1   WHERE  id = '$replyid'  ";
        $stmt = $this->connect()
            ->prepare($sql);
        if (!$stmt->execute()) {
            return false;
        } else {
            return true;
        }
    }

}
