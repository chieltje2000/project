<?php
require_once("../../includes/initialize.php");

if($session->isLoggedIn())
{
    redirectTo("index.php");
}

// Remember to give your form's submit tag a name="submit" attribute!
if(isset($_POST["submit"]))
{
    // Form has been submitted
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Check database to see if username/password exist.

    $foundUser = User::authenticate($username, $password);
    if($foundUser)
    {
        $session->login($foundUser);
        logAction('Login', "{$foundUser->username} logged in.");
        redirectTo("index.php");
    }
    else
    {
        // username/password combo was not found in the database
        $message = "Username/password combination incorrect";
    }
}
else
{
    // Form has not been submitted
    $username = "";
    $password = "";
}
?>
<?php includeLayoutTemplate('admin_header.php') ?>
            <h2>Staff Login</h2>
            <?php echo outputMessage($message);?>

            <form action="login.php" method="post">
                <table>
                    <tr>
                        <td>Username:</td>
                        <td>
                            <input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td>
                            <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password)?>">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="submit" value="login">
                        </td>
                    </tr>
                </table>
            </form>
<?php includeLayoutTemplate('admin_footer.php') ?>