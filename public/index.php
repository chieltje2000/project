<?php require_once ("../includes/initialize.php"); ?>
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

<?php includeLayoutTemplate('header.php'); ?>

<?php foreach($photos as $photo): ?>
    <div style="float: left; margin-left: 20px">
        <a href="photo.php?id=<?php echo $photo->id; ?>">
            <img src="<?php echo $photo->imagePath(); ?>" width="200">
        </a>
        <p><?php echo $photo->caption; ?></p>
    </div>
<?php endforeach; ?>

<div id="pagination" style="clear: both">
    <?php
    if($pagination->totalPages() > 1)
    {
        $session->page($pagination->currentPage);
        if($pagination->hasPreviousPage())
        {
            echo " <a href=\"index.php?page=";
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
                echo " <a href=\"index.php?page={$i}\">{$i}</a>";
            }
        }

        if($pagination->hasNextPage())
        {
            echo " <a href=\"index.php?page=";
            echo $pagination->nextPage();
            echo "\">Next &raquo;</a>";
        }
    }
    ?>
</div>

<?php includeLayoutTemplate('footer.php'); ?>