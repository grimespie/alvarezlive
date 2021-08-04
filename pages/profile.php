<?php
$Username = get_requested_username();

$AL_User = new AL_User($Username);
$AL_User->init();

if(isset($_GET["featured_artist"])) {
    $CanFeature = false;
    
    if(admin_allow()) {
        $CanFeature = true;
    }
    
    if(!$CanFeature) {
        unset($_GET["featured_artist"]);
    }
}

if(isset($_GET["delete"])) {
    $CanDelete = false;
    
    if(is_logged_in()) {
        $UserID = get_user_id();
        
        if($AL_User->getUserID() == $UserID) {
            $CanDelete = true;
        }
    }
    
    if(!$CanDelete) {
        unset($_GET["delete"]);
    }
}

if(isset($_GET["delete_confirmed"])) {
    $CanDelete = false;
    
    if(is_logged_in()) {
        $UserID = get_user_id();
        
        if($AL_User->getUserID() == $UserID) {
            $CanDelete = true;
        }
    }
    
    if(!$CanDelete) {
        unset($_GET["delete_confirmed"]);
    }
}

if(isset($_GET["edit"])) {
    $CanEdit = false;
    
    if(is_logged_in()) {
        $UserID = get_user_id();
        
        if($AL_User->getUserID() == $UserID) {
            $CanEdit = true;
        }
    }
    
    if(!$CanEdit) {
        unset($_GET["edit"]);
    }
    else {
        $ForceEdit = true;
        print('<div id="edit-profile-action"></div>');
        
        include_once("pages/create-profile.php");
        exit();
    }
}
?>

<?php get_header(); ?>

    <div id="page-profile">
        <div id="profile-header">
            <div class="container">
                <div class="col-2 col-m-3 col-s-12">
                    <img id="profile-image" src="<?php print($AL_User->getPicture()); ?>" />
                </div>
                <div class="col-6 col-m-9 col-s-12">
                    <div id="main-profile-wrapper">
                        <h1><?php print($AL_User->getFullName()); ?></h1><br/>
                        <div class="profile-videos"><?php print($AL_User->getVideoLabel()); ?></div>
                        <div class="profile-stats">
                            <span><img src="<?php print(get_image("video/AL_Views_orange.png")); ?>" /> <?php print($AL_User->getVideoViewLabel()); ?></span>
                            <span><img src="<?php print(get_image("video/AL_Likes_orange.png")); ?>" /> <?php print($AL_User->getVideoLikeLabel()); ?></span>
                            <span><img src="<?php print(get_image("video/AL_Comments_orange.png")); ?>" /> <?php print($AL_User->getVideoCommentsLabel()); ?></span>
                        </div>
                        <div class="clear"></div>
                        <p class="bio"><?php print(nl2br($AL_User->getUser()->bio)); ?></p>
                    </div>
                </div>
                <div class="col-4 col-m-12">
                    <div id="profile-user-control">
                        <?php
                        if(is_logged_in()) {
                            $UserID = get_user_id();
                            
                            if($AL_User->getUserID() == $UserID) {
                            ?>
                            
                                <span><a class="button" href="?edit">Edit</a></span>
                                <span><a class="button" href="?delete">Delete</a></span>
                            
                            <?php
                            }
                            
                            if(admin_allow()) {
                            ?>
                            
                                <span><a class="button" href="?featured_artist">Make Featured Artist</a></span>
                            
                            <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="clear"></div>
                    <div id="profile-social-links">
                        <?php
                        if($AL_User->getUser()->facebook_url != "") {
                        ?>
                        
                            <div class="social-link">
                                <a href="<?php print($AL_User->getUser()->facebook_url); ?>" target="_blank"><img src="<?php print(get_image("social/AL_FB_orange.png")); ?>" alt="Facebook" /><?php print($AL_User->getUser()->facebook_url); ?><br/></a>
                            </div>
                        
                        <?php
                        }
                        
                        if($AL_User->getUser()->twitter_url != "") {
                        ?>
                        
                            <div class="social-link">
                                <a href="<?php print($AL_User->getUser()->twitter_url); ?>" target="_blank"><img src="<?php print(get_image("social/AL_Twitter_orange.png")); ?>" alt="Twitter" /><?php print($AL_User->getUser()->twitter_url); ?><br/></a>
                            </div>
                        
                        <?php
                        }
                        
                        if($AL_User->getUser()->youtube_url != "") {
                        ?>
                        
                            <div class="social-link">
                                <a href="<?php print($AL_User->getUser()->youtube_url); ?>" target="_blank"><img src="<?php print(get_image("social/AL_youtube_orange.png")); ?>" alt="YouTube" /><?php print($AL_User->getUser()->youtube_url); ?><br/></a>
                            </div>
                        
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        if(isset($_GET["featured_artist"])) {
            if(admin_allow()) {
                $Status = DHMU_Admin(array("featured_artist" => $AL_User->getUserID()), array(1 => 1));
                ?>
        
                <div class="container">
                    <div class="col-12">
                        <p>This profile is now the Featured Artist on Alvarez Live.</p>
                        <div class="confirmation">
                            <p><a href="<?php print(get_link("admin")); ?>">Go to Admin Dashboard</a></p>
                        </div>
                    </div>
                </div>
                    
            <?php
            }
        }
        else if(isset($_GET["delete"])) {
            if(is_logged_in()) {
                $UserID = get_user_id();
                
                if($AL_User->getUserID() == $UserID) {
                ?>
        
                    <div class="container">
                        <div class="col-12">
                            <p>Are you sure you want to delete your profile? (All videos that you have uploaded will also be deleted)</p>
                            <div class="confirmation">
                                <p><a href="?delete_confirmed">Yes</a> <a href="<?php print($AL_User->getProfileLink()); ?>">Cancel</a></p>
                            </div>
                        </div>
                    </div>
                    
                <?php
                }
            }
        }
        else if(isset($_GET["delete_confirmed"])) {
            if(is_logged_in()) {
                $UserID = get_user_id();
                
                if($AL_User->getUserID() == $UserID) {
                    $Status = DHMD_User('user_id = ' . $AL_User->getUserID());
                    $Status = DHMD_User_Account('user_id = ' . $AL_User->getUserID());
                    $Status = DHMD_Video('user_id = ' . $AL_User->getUserID());
                    
                    session_destroy();
                    ?>
        
                    <div class="container">
                        <div class="col-12">
                            <p>Your profile and videos have been deleted and you are now signed out.</p>
                            <div class="confirmation">
                                <p><a href="<?php print(get_home()); ?>">Go home</a></p>
                            </div>
                        </div>
                    </div>
                    
                <?php
                }
            }
        }
        else {
            display_videos("", $AL_User->getUser()->user_id, false, $AL_User->getUser()->default_sort);
        }
        ?>
        
    </div>

<?php get_footer(); ?>