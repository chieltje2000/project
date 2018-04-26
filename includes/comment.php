<?php
require_once (LIB_PATH.DS.'database.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Comment extends DatabaseObject
{
    protected static $tableName = "comments";
    protected static $dbFields = array('id', 'photograph_id', 'created', 'author', 'body');

    public $id;
    public $photograph_id;
    public $created;
    public $author;
    public $body;

    // "new" is a reserved word so we use "make" (or "build")
    public static function make($photoId, $author = "Anonymous", $body = "")
    {
        if (!empty($photoId) && !empty($author) && !empty($body))
        {
            $comment = new Comment();
            $comment->photograph_id = (int)$photoId;
            $comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
            $comment->author = $author;
            $comment->body = $body;
            return $comment;
        }
        else
        {
            return false;
        }
    }

    public static function findCommentsOn($photoId = 0)
    {
        global $database;
        $sql = "SELECT * FROM " . self::$tableName .
            " WHERE photograph_id=" . $database->escapeValue($photoId) .
            " ORDER BY created DESC";
        return self::findBySql($sql);

    }

    public function tryToSendNotification()
    {
        // PHP smtp version
        $mail = new PHPMailer();

        $mail->IsSMTP();
        $mail->Host = "smtp.live.com";
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = "phptesting1234@outlook.com";
        $mail->Password = "Testing1234";


        $mail->FromName = "Photo Gallery";
        $mail->From = "phptesting1234@outlook.com";
        $mail->AddAddress("jongetje5@hotmail.com", "Photo Gallery Admin");
        $mail->Subject = "New Photo Gallery Comment";
        $created = datetimeToText($this->created);
        $mail->Body = <<<EMAILBODY
        
        A new comment has been received in the Photo Gallery.
        
        At {$created}, {$this->author} wrote:

        {$this->body}
        
EMAILBODY;
        $result = $mail->Send();
        return $result;
    }

    public static function displayComments($comments, $isAdminPage = false)
    {
        echo '<div id="comments">';
        foreach ($comments as $comment)
        {
            echo '<div class="comment" style="margin-bottom: 2em; width: 200px; word-wrap: break-word;">
            <div class="author">
                <strong>' . htmlentities($comment->author) . ' wrote: </strong>
            </div>
            <div class="body">' .
                strip_tags($comment->body, '<strong><em><p>') .
                '</div>
            <div class="meta-info" style="font-size: 0.8em">'
                . datetimeToText($comment->created) .
                '</div>';

            if ($isAdminPage != false)
            {
                echo '<div class="actions" style="font-size: 0.8em">
                   <a href="deleteComment.php?id=' . $comment->id . '">Delete Comment</a>
                </div>';
            }
            echo '</div>';
        }

        if (empty($comments))
        {
            echo "No Comments";
        }
        echo '</div>';
    }

}
