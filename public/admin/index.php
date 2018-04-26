<?php
require_once("../../includes/initialize.php");

if(!$session->isLoggedIn())
{
    redirectTo("login.php");
}
?>

<?php includeLayoutTemplate('admin_header.php'); ?>
        <h2>Menu</h2>

<?php echo outputMessage($message) ?>
        <ul>
            <li><a href="listPhotos.php">List Photos</a> </li>
            <?php if($session->isSuperAdmin()):  ?>
            <li><a href="listUsers.php">List users</a></li>
            <?php endif; ?>
            <li><a href="logfile.php">View Log file</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
<?php includeLayoutTemplate('admin_footer.php'); ?>