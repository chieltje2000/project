<?php
require_once("../../includes/initialize.php");

if(!$session->isLoggedIn())
{
    redirectTo("login.php");
}
if(!$session->isSuperAdmin())
{
    redirectTo("index.php");
}
?>
<?php

if(isset($_POST['submit']))
{
    $user = new User();
    $user->username = $_POST['username'];
    $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user->first_name = $_POST['firstName'];
    $user->last_name = $_POST['lastName'];
    if(isset($_POST['superAdmin']))
    {
        $user->super_admin = 1;
    }
    else
    {
        $user->super_admin = 0;
    }

    $sql = "select * from users where username = '{$username}'";
    $result = User::findBySql($sql);

    if(empty($result) && $user->save())
    {
        // Success
        $session->message("User uploaded successfully");
        redirectTo('listUsers.php');
    }
    else
    {
        // Failure
        $message = "Something went wrong!!";
    }
}

?>

<?php includeLayoutTemplate('admin_header.php'); ?>
    <a href="listUsers.php">&laquo; Back</a><br>
    <br>


    <h2>Add User</h2>

<?php echo outputMessage($message)?>
    <form action="addUser.php" method="post">
        <p>Username: <input required type="text" name="username" value=""></p>
        <p>Password: <input required type="password" name="password" value=""></p>
        <p>First name: <input required type="text" name="firstName"></p>
        <p>Last name: <input required type="text" name="lastName"></p>
        <p>Super admin: <input type="checkbox" name="superAdmin" value="superAdmin"></p>
        <input type="submit" name="submit" value="Upload">
    </form>


<?php includeLayoutTemplate('admin_footer.php'); ?>