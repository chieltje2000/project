<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->isLoggedIn()) { redirectTo("login.php"); } ?>
<?php
if(empty($_GET['id']))
{
    $session->message("No photograph ID was provided");
    redirectTo('index.php');
}

$photo = Photograph::findById($_GET['id']);
if(!$photo)
{
    $session->message("The photo could not be located");
    redirectTo('index.php');
}

$comments = $photo->comments();

?>
<?php includeLayoutTemplate('admin_header.php'); ?>

<a href="listPhotos.php?page=<?php echo $session->page(); ?>">&laquo; Back</a><br>
<br>

<h2>Comments on <?php $photo->filename; ?></h2>

<?php echo outputMessage($message); ?>
<?php echo Comment::displayComments($comments, true); ?>


<?php includeLayoutTemplate('admin_footer.php'); ?>