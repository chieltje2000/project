<?php require_once ("../../includes/initialize.php"); ?>
<?php if (!$session->isLoggedIn()) { redirectTo("login.php"); } ?>
<?php
// must hava an ID
if(empty($_GET['id']))
{
    $session->message("No comment ID was provided");
    redirectTo('index.php');
}

$comment = Comment::findById($_GET['id']);
if($comment && $comment->delete())
{
    $session->message("The comment was deleted");
    redirectTo("comments.php?id={$comment->photograph_id}");
}
else
{
    $session->message("The comment could not be deleted");
    redirectTo('listPhotos.php');
}

?>
<?php if(isset($database)){ $database->closeConnection(); } ?>
