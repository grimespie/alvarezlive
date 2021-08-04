<?php get_header(); ?>

    <div id="page-videos">
        
        <?php
        global $title, $sort;
        
        $title = "Performances";
        $sort  = true;
        
        get_template("videos-title");
        ?>
        
    </div>
    
    <?php display_videos(VID_CAT_PERFORMANCES); ?>

<?php get_footer(); ?>