<?php
get_header();
?>

    <div id="page-videos">
        
        <?php
        global $title, $sort;
        
        $title = "Educational";
        $sort  = true;
        
        get_template("videos-title");
        ?>
        
    </div>
    
    <?php display_videos(VID_CAT_EDUCATIONAL); ?>

<?php get_footer(); ?>
