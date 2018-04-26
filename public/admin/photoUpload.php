<?php
require_once("../../includes/initialize.php");

if(!$session->isLoggedIn())
{
    redirectTo("login.php");
}
?>
<?php
$maxFileSize = 1048576;   // expressed in bytes
                            //     10240 =  10 KB
                            //    102400 = 100 KB
                            //   1048576 =   1 MB
                            //  10485760 =  10 MB

if(isset($_POST['submit']))
{
    $photo = new Photograph();
    $photo->caption = $_POST['caption'];
    $photo->attachFile($_FILES['fileUpload']);
    if($photo->save())
    {
        // Success
        $session->message("Photograph uploaded successfully");
        redirectTo('listPhotos.php');
    }
    else
    {
        // Failure
        $message = join("<br>", $photo->errors);
    }
}

?>

<?php includeLayoutTemplate('admin_header.php'); ?>
<a href="listPhotos.php">&laquo; Back</a><br>
<br>


<h2>Photo Upload</h2>

<?php echo outputMessage($message)?>
<form action="photoUpload.php" enctype="multipart/form-data" method="post">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxFileSize?>">
    <p><input type="file" name="fileUpload"></p>
    <p>Caption: <input type="text" name="caption" value=""></p>
    <input type="submit" name="submit" value="Upload">
</form>


<?php includeLayoutTemplate('admin_footer.php'); ?>