<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->isLoggedIn()) { redirectTo("login.php"); } ?>
<?php

// 1. the current page number ($currentPage)
$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

// 2. records per page ($perPage)
$perPage = 3;

// 3. total record count ($totalCount)
$totalCount = Photograph::countAll();


// Find all photos
// us pagination instead
//$photos = Photograph::findAll();

$pagination = new Pagination($page, $perPage, $totalCount);

// Instead of finding all records, just find the records
// for this page
$sql = "SELECT * FROM photographs ".
    "LIMIT {$perPage} ".
    "OFFSET {$pagination->offset()}";
$photos = Photograph::findBySql($sql);

// Need to add ?page=$page to all links we want to
// maintain the curremt page (or store $page in session)

?>
<?php includeLayoutTemplate('admin_header.php'); ?>
    <a href="index.php">&laquo; Back</a><br>
    <br>

    <h2>Photographs</h2>

<?php echo outputMessage($message)?>
    <table class="bordered">
        <tr>
            <th>Image</th>
            <th>Filename</th>
            <th>Caption</th>
            <th>Size</th>
            <th>Type</th>
            <th>Comments</th>
            <th>&nbsp:</th>
        </tr>
        <?php foreach($photos as $photo): ?>
            <tr>
                <td><img src="../<?php echo $photo->imagePath(); ?>" width="100" /></td>
                <td><?php echo $photo->filename; ?></td>
                <td><?php echo $photo->caption; ?></td>
                <td><?php echo $photo->sizeAsText(); ?></td>
                <td><?php echo $photo->type; ?></td>
                <td>
                    <a href="comments.php?id=<?php echo $photo->id; ?>">
                        <?php echo count($photo->comments()); ?>
                    </a>
                </td>
                <td><a href="deletePhoto.php?id=<?php echo $photo->id; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br />
    <a href="photoUpload.php">Upload a new photograph</a>

    <div id="pagination" style="clear: both">
        <?php
        if($pagination->totalPages() > 1)
        {
            $session->page($pagination->currentPage);
            if($pagination->hasPreviousPage())
            {
                echo " <a href=\"listPhotos.php?page=";
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
                    echo " <a href=\"listPhotos.php?page={$i}\">{$i}</a>";
                }
            }

            if($pagination->hasNextPage())
            {
                echo " <a href=\"listPhotos.php?page=";
                echo $pagination->nextPage();
                echo "\">Next &raquo;</a>";
            }
        }
        ?>
    </div>

<?php includeLayoutTemplate('admin_footer.php'); ?>