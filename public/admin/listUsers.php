<?php require_once("../../includes/initialize.php");
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

// 1. the current page number ($currentPage)
$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

// 2. records per page ($perPage)
$perPage = 3;

// 3. total record count ($totalCount)
$totalCount = User::countAll();


// Find all photos
// us pagination instead
//$photos = Photograph::findAll();

$pagination = new Pagination($page, $perPage, $totalCount);

// Instead of finding all records, just find the records
// for this page
$sql = "SELECT * FROM users ".
    "LIMIT {$perPage} ".
    "OFFSET {$pagination->offset()}";
$users = User::findBySql($sql)

// Need to add ?page=$page to all links we want to
// maintain the curremt page (or store $page in session)

?>
<?php includeLayoutTemplate('admin_header.php'); ?>
    <a href="index.php">&laquo; Back</a><br>
    <br>

    <h2>Users</h2>

<?php echo outputMessage($message)?>
    <table class="bordered">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>First name</th>
            <th>Last name</th>
            <th>Super admin</th>
            <th>delete</th>
        </tr>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo $user->id ?></td>
                <td><?php echo $user->username ?></td>
                <td><?php echo $user->first_name ?></td>
                <td><?php echo $user->last_name ?></td>
                <td><?php echo $user->super_admin ?></td>
                <td>
                    <?php if ($user->super_admin == 0): ?>
                        <a href="deleteUsers.php?id=<?php echo $user->id; ?>">Delete</a>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>
    </table>
    <br />
    <a href="addUser.php">Add a new user</a>

    <div id="pagination" style="clear: both">
        <?php
        if($pagination->totalPages() > 1)
        {
            $session->page($pagination->currentPage);
            if($pagination->hasPreviousPage())
            {
                echo " <a href=\"listUsers.php?page=";
                echo $pagination->previousPage();
                echo "\">&laquo; Previous</a>";
            }

            for($i=1; $i <= $pagination->totalPages(); $i++)
            {
                if($i == $page)
                {
                    echo " <span class=\"selected\">{$i}</span>";
                }
                else
                {
                    echo " <a href=\"listUsers.php?page={$i}\">{$i}</a>";
                }
            }

            if($pagination->hasNextPage())
            {
                echo " <a href=\"listUsers.php?page=";
                echo $pagination->nextPage();
                echo "\">Next &raquo;</a>";
            }
        }
        ?>
    </div>

<?php includeLayoutTemplate('admin_footer.php'); ?>