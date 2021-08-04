<?php
global $title, $sort;
?>

<div id="videos-title">
    <div class="container">
        <div class="col-6 col-s-12 col-s-center">
            <h1><?php print($title); ?></h1>
        </div>
        <div class="col-6 col-right col-s-12 col-s-center">
            <?php
            if($sort) {
            ?>
            
                <select id="sort-videos">
                    <option value="newest" <?php if($_GET["sort"] == "newest") { print('selected'); } ?>>Date added (newest)</option>
                    <option value="oldest" <?php if($_GET["sort"] == "oldest") { print('selected'); } ?>>Date added (oldest)</option>
                    <option value="most-popular" <?php if($_GET["sort"] == "most-popular") { print('selected'); } ?>>Most Popular</option>
                </select>
            
            <?php
            }
            ?>
        </div>
    </div>
</div>