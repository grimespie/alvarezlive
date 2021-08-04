<?php get_header(); ?>

    <div id="page-videos">
        
        <?php
        global $title, $sort;
        
        $title = "Guitars";
        $sort  = true;
        
        get_template("videos-title");
        ?>
        
    </div>
    
    <?php display_videos(VID_CAT_GUITARS); ?>

<?php get_footer(); ?>