<?php require_once ("../../includes/initialize.php"); ?>
<?php if (!$session->isLoggedIn()) { redirectTo("login.php"); } ?>
<?php
// must hava an ID
if(empty($_GET['id']))
{
    $session->message("No photograph ID was provided");
    redirectTo('index.php');
}

$photo = Photograph::findById($_GET['id']);
if($photo && $photo->destroy())
{
    $session->message("The photo {$photo->filename} was deleted");
    redirectTo('listPhotos.php?page= '. $session->page());
}
else
{
    $session->message("The photo could not be deleted");
    redirectTo('listPhotos.php');
}

?>
<?php if(isset($database)){ $database->closeConnection(); } ?>
