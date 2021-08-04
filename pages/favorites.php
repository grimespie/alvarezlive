<?php

if(!user_can_interact_video()) {
    header("Location: " . get_link("create-profile"));
    exit();
}

get_header();
?>

    <div id="page-videos">
        
        <?php
        global $title;
        $title = "Your Favorite Videos";
        
        get_template("videos-title");
        ?>
        
    </div>
    
<?php
$Interactions = DHMR_User_Interactions("user_id = " . get_user_id());

$VideoIDs = array();

foreach($Interactions as $Interaction) {
    if($Interaction->favourite_ind == USER_INTERACT_YES) {
        $VideoIDs[$Interaction->video_id] = "";
    }
}

$UniqueKeys = array_keys($VideoIDs);

$Videos = DHMR_Video('video_id in (' . implode(", ", $UniqueKeys) . ') and status != ' . VID_STAT_NEW . ' and (youtube_ind = ' . YT_IND_YES . ' or (youtube_ind = ' . YT_IND_NO . ' and vimeo_ready = ' . VIMEO_READY_YES . ')) order by upload_date desc');

if((count($Interactions) == 0) || (count($Videos) == 0)) {
?>

    <div class="container">
        <div class="col-12">
            <p>You don't have any favorites yet. When viewing a video, click the heart icon to add to your favorites.</p>
        </div>
    </div>

<?php
}
else {
    print('<div class="videos-full-width">');
            
    foreach($Videos as $Video) {
        $Counter++;
        
        display_video(new AL_Video($Video->video_id));
        
        if($Counter >= 3) {
            print('<div class="clear"></div>');
            
            $Counter = 0;
        }
            
    }
    
    print('</div>');
}

get_footer();
?>
