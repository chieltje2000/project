<?php require_once ("../includes/initialize.php"); ?>
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

if(isset($_POST['submit']))
{
    $author = trim($_POST['author']);
    $body = trim($_POST['body']);

    $newComment = Comment::make($photo->id, $author, $body);
    if($newComment && $newComment->save())
    {
        // comment saved
        // No message needed; seeing the comment is proof enough

        // Semd email
        $newComment->tryToSendNotification();

        // Important! You could just let the page render from here
        // But the if the page is reloaded, the form will try
        // to resubmit the comment. So redirect instead:
        redirectTo("photo.php?id={$photo->id}");
    }
    else
    {
        // Failed
        $message = "There was an error that prevented the comment from being saved";
    }
}
else
{
    $author = "";
    $body = "";
}
$comments = $photo->comments();
?>
<?php includeLayoutTemplate('header.php'); ?>

<a href="index.php?page=<?php echo $session->page(); ?>">&laquo; Back</a><br>
<br>

<div style="margin-left: 20px;">
    <img src="<?php echo $photo->imagePath(); ?>">
    <p><?php echo $photo->caption; ?></p>
</div>

<?php echo Comment::displayComments($comments, false); ?>

<div id="comment-form">
    <h3>New Comment</h3>
    <?php echo outputMessage($message); ?>
    <form action="photo.php?id=<?php echo $photo->id; ?>" method="post">
        <table>
            <tr>
                <td>Your name:</td>
                <td><input type="text" name="author" value="<?php echo $author; ?>"></td>
            </tr>
            <tr>
                <td>Your comment:</td>
                <td><textarea name="body" cols="40" rows="8"><?php echo $body; ?></textarea></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="submit" value="Submit Comment"></td>
            </tr>
        </table>
    </form>
</div>

<?php includeLayoutTemplate('footer.php'); ?>