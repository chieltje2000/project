<?php require_once ("../../includes/initialize.php"); ?>
<?php
if (!$session->isLoggedIn())
{
    redirectTo("login.php");
}

if(!$session->isSuperAdmin())
{
    redirectTo("index.php");
}
?>
<?php
// must hava an ID
if(empty($_GET['id']))
{
    $session->message("No user ID was provided");
    redirectTo('index.php');
}

$user = User::findById($_GET['id']);
if($user && $user->delete() && !$user->isSuperAdmin)
{
    $session->message("The user {$user->fullName()} was deleted");
    redirectTo('listUsers.php?page= '. $session->page());
}
else
{
    $session->message("The user could not be deleted");
    redirectTo('listUsers.php');
}

?>
<?php if(isset($database)){ $database->closeConnection(); } ?>
