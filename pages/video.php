<?php
$VideoID = get_requested_video();

$AL_Video = new AL_Video($VideoID);
$AL_Video->init();

$VideoReady = true;
$Errors = array();

if(($AL_Video->is_vimeo()) && ($AL_Video->getVimeoStatus() != "available")) {
    $VideoReady = false;
}

if(isset($_GET["test"])) {
    $VideoReady = false;
}

if(isset($_POST["nonce"])) {
    if(verify_nonce_field(get_account_id())) {
        if($_POST["title"] == "") {
            $Errors["title"] = "Please enter a title for the video.";
        }
        
        if($_POST["description"] == "") {
            $Errors["description"] = "Please enter a description for the video.";
        }
        
        if($_POST["category"] == "") {
            $Errors["category"] = "Please select a category.";
        }
        
        if(count($Errors) == 0) {
            $NewVideo["title"]              = $_POST["title"];
            $NewVideo["description"]        = $_POST["description"];
            $NewVideo["category"]           = $_POST["category"];
            
            if($_POST["guitar"] != "") {
                if($_POST["guitar"] == "custom") {
                    if($_POST["custom_guitar"] != "") {
                        $NewVideo["guitar"] = $_POST["custom_guitar"];
                    }
                }
                else {
                    $NewVideo["guitar"] = $_POST["guitar"];
                }
            }
            
            $Status = DHMU_Video($NewVideo, array("video_id" => $AL_Video->getVideoID()));
            
            go_to_video($AL_Video->getVideoID());
        }
    }
}

if(isset($_GET["featured_song"])) {
    $CanFeature = false;
    
    if(admin_allow()) {
        $CanFeature = true;
    }
    
    if(!$CanFeature) {
        unset($_GET["featured_song"]);
    }
}

if(isset($_GET["featured_lesson"])) {
    $CanFeature = false;
    
    if(admin_allow()) {
        $CanFeature = true;
    }
    
    if(!$CanFeature) {
        unset($_GET["featured_lesson"]);
    }
}

if(isset($_GET["approve"])) {
    $CanApprove = false;
    
    if(admin_allow()) {
        if($AL_Video->needsApproval()) {
            $CanApprove = true;
        }
    }
    
    if(!$CanApprove) {
        unset($_GET["approve"]);
    }
}

if(isset($_GET["delete"])) {
    $CanDelete = false;
    
    if(admin_allow()) {
        $CanDelete = true;
    }
    else if(is_logged_in()) {
        $UserID = get_user_id();
        
        if($AL_Video->getUser()->getUserID() == $UserID) {
            $CanDelete = true;
        }
    }
    
    if(!$CanDelete) {
        unset($_GET["delete"]);
    }
}

if(isset($_GET["delete_confirmed"])) {
    $CanDelete = false;
    
    if(admin_allow()) {
        $CanDelete = true;
    }
    if(is_logged_in()) {
        $UserID = get_user_id();
        
        if($AL_Video->getUser()->getUserID() == $UserID) {
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
        
        if($AL_Video->getUser()->getUserID() == $UserID) {
            $CanEdit = true;
        }
    }
    
    if(!$CanEdit) {
        unset($_GET["edit"]);
    }
}
?>


<?php get_header(); ?>

    <div id="page-video" class="<?php if(!$VideoReady) { print('page-upload'); } ?>" data-videoid="<?php print($VideoID); ?>" data-ajaxcall="<?php print(get_ajax("view")); ?>" data-nonce="<?php print(create_nonce(get_account_id())); ?>">
        <div id="video-title">
            <div class="container">
                <div class="col-12">
                    <?php
                    if(!$VideoReady) {
                    ?>
                    
                        <h1>Upload Video</h1>
                        <div class="page-divide"></div>
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <div id="video-user-control">
                            
                            <?php
                            if(is_logged_in()) {
                                $UserID = get_user_id();
                                
                                if(admin_allow()) {
                                ?>
                                
                                    <span><a class="button" href="?delete">Delete</a></span>
                                
                                <?php
                                }
                                else if($AL_Video->getUser()->getUserID() == $UserID) {
                                ?>
                                
                                    <span><a class="button" href="?edit">Edit</a></span>
                                    <span><a class="button" href="?delete">Delete</a></span>
                                
                                <?php
                                }
                                
                                if(admin_allow()) {
                                    if($AL_Video->needsApproval()) {
                                    ?>
                                    
                                        <span><a class="button" href="?approve">Approve</a></span>
                                    
                                    <?php
                                    }
                                    
                                    if($AL_Video->getVideo()->category == VID_CAT_PERFORMANCES) {
                                    ?>
                                    
                                        <span><a class="button" href="?featured_song">Make Featured Song</a></span>
                                    
                                    <?php
                                    }
                                    else if($AL_Video->getVideo()->category == VID_CAT_EDUCATIONAL) {
                                    ?>
                                    
                                        <span><a class="button" href="?featured_lesson">Make Featured Lesson</a></span>
                                    
                                    <?php
                                    }
                                }
                            }
                            ?>
                        
                        </div>
                    
                        <h1><?php print($AL_Video->getVideo()->title); ?></h1>
                        <h2><?php print($AL_Video->getCategory()); ?></h2>
                        
                        <?php
                        if(is_logged_in()) {
                            $UserID = get_user_id();
                            
                            if($AL_Video->getUser()->getUserID() == $UserID) {
                                if($AL_Video->needsApproval()) {
                                ?>
                                
                                    <div class="info-alert">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        <p>Congratulations. Your video is now shareable from your Alvarez Live page and an email will notify you when it is on the Alvarez Live feed.</p>
                                    </div>
                                
                                <?php
                                }
                            }
                        }
                        ?>
                        
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <?php
        if(count($Errors) > 0) {
        ?>
        
            <div class="container">
                <div class="col-12">
                    <ul class="errors">
                    
                    <?php
                    foreach($Errors as $Error) {
                    ?>
                    
                        <li><?php print($Error); ?></li>
                    
                    <?php
                    }
                    ?>
                    
                    </ul>
                </div>
            </div>
        
        <?php
        }
        ?>
        
        <?php
        if(isset($_GET["featured_song"])) {
            if(admin_allow()) {
                $Status = DHMU_Admin(array("featured_song" => $AL_Video->getVideoID()), array(1 => 1));
                ?>
        
                <div class="container">
                    <div class="col-12">
                        <p>This video is now the Featured Song on Alvarez Live.</p>
                        <div class="confirmation">
                            <p><a href="<?php print(get_link("admin")); ?>">Go to Admin Dashboard</a></p>
                        </div>
                    </div>
                </div>
                    
            <?php
            }
        }
        else if(isset($_GET["featured_lesson"])) {
            if(admin_allow()) {
                $Status = DHMU_Admin(array("featured_lesson" => $AL_Video->getVideoID()), array(1 => 1));
                ?>
        
                <div class="container">
                    <div class="col-12">
                        <p>This video is now the Featured Lesson on Alvarez Live.</p>
                        <div class="confirmation">
                            <p><a href="<?php print(get_link("admin")); ?>">Go to Admin Dashboard</a></p>
                        </div>
                    </div>
                </div>
                    
            <?php
            }
        }
        else if(isset($_GET["approve"])) {
            if(admin_allow()) {
                $Status = DHMU_Video(array("status" => VID_STAT_APPROVED), array("video_id" => $AL_Video->getVideoID()));
                
                user_alert_approved_video($AL_Video->getVideoID(), $AL_Video->getUser()->getEmail());
                ?>
        
                <div class="container">
                    <div class="col-12">
                        <p>The video has now been approved.</p>
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
                
                if(($AL_Video->getUser()->getUserID() == $UserID) || (admin_allow())) {
                ?>
        
                    <div class="container">
                        <div class="col-12">
                            <p>Are you sure you want to delete this video?</p>
                            <div class="confirmation">
                                <p><a href="?delete_confirmed">Yes</a> <a href="<?php print($AL_Video->getVideoLink()); ?>">Cancel</a></p>
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
                
                if(($AL_Video->getUser()->getUserID() == $UserID) || (admin_allow())) {
                    $Status = DHMD_Video('video_id = ' . $AL_Video->getVideoID());
                    ?>
        
                    <div class="container">
                        <div class="col-12">
                            <p>Video deleted.</p>
                            <div class="confirmation">
                                <p><a href="<?php print(get_link('user/' . $_SESSION['user_profile']->username)); ?>">Go to profile</a></p>
                            </div>
                        </div>
                    </div>
                    
                <?php
                }
            }
        }
        else if(isset($_GET["edit"])) {
            if(is_logged_in()) {
                $UserID = get_user_id();
                
                if($AL_Video->getUser()->getUserID() == $UserID) {
                ?>
        
                    <div id="page-upload">
                        <form action="" method="post" enctype="multipart/form-data">
                            <?php create_nonce_field(get_account_id()); ?>
                            <div class="container">
                                <div class="col-12">
                                    <h2>Edit your video information</h2>
                                </div>
                            </div>
                            
                            <div class="container">
                                <div class="col-6">
                                    <label>Video Title*</label>
                                    <input type="text" name="title" value="<?php print($AL_Video->getVideo()->title); ?>" placeholder="The title of your video..." />
                                    <div class="clear"></div>
                                    
                                    <label>Video Description*</label>
                                    <textarea name="description" placeholder="Video description..."><?php print($AL_Video->getVideo()->description); ?></textarea>
                                    <div class="clear"></div>
                                </div>
                                <div class="col-6 right-column">
                                    <select id="choose-guitar" name="guitar" placeholder="Choose the guitar being used...">
                                        <option value="">-- Guitar --</option>
                                        <?php
                                        $Guitars = get_guitar_list();
                                        $Custom  = true;
                                        
                                        foreach($Guitars as $Guitar) {
                                        ?>
                                        
                                            <option <?php if($AL_Video->getGuitarModel() == $Guitar->model) { $Custom = false; print('selected'); } ?> value='<?php print(json_encode($Guitar)); ?>'><?php print($Guitar->model); ?></option>
                                        
                                        <?php
                                        }
                                        ?>
                                        
                                        <option <?php if($Custom) { print('selected'); } ?> value='custom'>Other</option>
                                    </select>
                                    <label>Guitar used</label>
                                    <div class="clear"></div>
                                    
                                    <div id="custom-guitar" <?php if($Custom) { print('style="display: block;"'); } ?>>
                                        <input type="text" name="custom_guitar" value="" placeholder="Enter your guitar model..." />
                                        <label>Your guitar model</label>
                                        <div class="clear"></div>
                                    </div>
                                    
                                    <select name="category" placeholder="Choose a category for this video...">
                                        <option value="">-- Category -- </option>
                                        <option <?php if($AL_Video->getVideo()->category == VID_CAT_PERFORMANCES) { print('selected'); } ?> value="<?php print(VID_CAT_PERFORMANCES); ?>">Performances</option>
                                        <option <?php if($AL_Video->getVideo()->category == VID_CAT_EDUCATIONAL) { print('selected'); } ?> value="<?php print(VID_CAT_EDUCATIONAL); ?>">Educational</option>
                                        <option <?php if($AL_Video->getVideo()->category == VID_CAT_GUITARS) { print('selected'); } ?> value="<?php print(VID_CAT_GUITARS); ?>">Guitars</option>
                                    </select>
                                    <label>Video Category*</label>
                                    <div class="clear"></div>
                                    
                                    <textarea name="tags" placeholder="Choose tags for your video, separated by commas..."><?php print($AL_Video->getVideo()->tags); ?></textarea>
                                    <label>Tags</label>
                                    <div class="clear"></div>
                                </div>
                            </div>
                
                            <div class="container">
                                <div class="col-12">
                                    <div class="page-divide"></div>
                                    <input class="button" type="submit" value="Save Changes" />
                                </div>
                            </div>
                        </form>
                    </div>
                    
                <?php
                }
            }
        }
        else if($VideoReady) {
        ?>
        
            <div class="container">
                <div class="col-12">
                    <div class="video">
                        <?php $AL_Video->get_video_embed(); ?>
                    </div>
                </div>
                <div class="col-12">
                    <div id="video-details">
                        <a href="<?php print($AL_Video->getUser()->getProfileLink()); ?>">
                            <div class="person-wrapper">
                                <img id="profile-image" src="<?php print($AL_Video->getUser()->getPicture()); ?>" />
                                <div class="person">
                                    <?php print($AL_Video->getUser()->getFullName()); ?>
                                </div>
                            </div>
                        </a>
                        <div class="views">
                            <img src="<?php print(get_image("video/AL_Views_orange.png")); ?>" /> <?php print($AL_Video->getVideoViewCount()); ?>
                        </div>
                        <div class="likes">
                            <img src="<?php print(get_image("video/AL_Likes_orange.png")); ?>" /> <?php print($AL_Video->getVideoLikeCount()); ?>
                        </div>
                        <div class="comments">
                            <img src="<?php print(get_image("video/AL_Comments_orange.png")); ?>" /> <?php print($AL_Video->getVideoCommentCount()); ?>
                        </div>
                        
                        <?php
                        if($AL_Video->isGuitar()) {
                            if($AL_Video->isCurrentModel()) {
                            ?>
                        
                            <div class="model">
                                <a target="_blank" href="<?php print($AL_Video->getGuitarLink()); ?>">
                                    <img src="<?php print(get_image("video/AL_Guitar_orange.png")); ?>" /><?php print($AL_Video->getGuitarModel()); ?>
                                </a>
                            </div>
                            
                            <?php
                            }
                            else {
                            ?>
                            
                            <div class="model">
                                <img src="<?php print(get_image("video/AL_Guitar_orange.png")); ?>" /><?php print($AL_Video->getGuitarModel()); ?>
                            </div>
                            
                            <?php
                            }
                        }
                        ?>
                        
                        
                        <?php
                        if(user_can_interact_video()) {
                        ?>
                        
                            <div class="action clickable">
                                <img id="share-video" src="<?php print(get_image("video/AL_Share_grey.png")); ?>" />
                            </div>
                            <div class="action clickable ajax-favourite" data-ajaxcall="<?php print(get_ajax("favourite")); ?>" data-nonce="<?php print(create_nonce(get_account_id())); ?>" data-userid="<?php print($_SESSION["user_profile"]->user_id); ?>" data-videoid="<?php print($VideoID); ?>">
                                <img style="<?php if((int)$AL_Video->getUserInteraction()->favourite_ind == USER_INTERACT_YES) { print('display: none;'); } ?>" class="favourite-no"  src="<?php print(get_image("video/AL_Favourite_grey.png")); ?>" />
                                <img style="<?php if((int)$AL_Video->getUserInteraction()->favourite_ind == USER_INTERACT_YES) { print('display: block;'); } ?>" class="favourite-yes" src="<?php print(get_image("video/AL_Favourite_orange.png")); ?>" />
                            </div>
                            <div class="action clickable ajax-like" data-ajaxcall="<?php print(get_ajax("like")); ?>" data-nonce="<?php print(create_nonce(get_account_id())); ?>" data-userid="<?php print($_SESSION["user_profile"]->user_id); ?>" data-videoid="<?php print($VideoID); ?>">
                                <img style="<?php if((int)$AL_Video->getUserInteraction()->like_ind == USER_INTERACT_YES) { print('display: none;'); } ?>" class="like-no" src="<?php print(get_image("video/AL_Likes_grey.png")); ?>" />
                                <img style="<?php if((int)$AL_Video->getUserInteraction()->like_ind == USER_INTERACT_YES) { print('display: block;'); } ?>" class="like-yes" src="<?php print(get_image("video/AL_Likes_orange.png")); ?>" />
                            </div>
                        
                        <?php
                        }
                        else {
                        ?>
                            
                            <div class="action clickable">
                                <img src="<?php print(get_image("video/AL_Share_grey.png")); ?>" />
                            </div>
                            <div class="action">
                                <img src="<?php print(get_image("video/AL_Favourite_grey.png")); ?>" />
                            </div>
                            <div class="action">
                                <img src="<?php print(get_image("video/AL_Likes_grey.png")); ?>" />
                            </div>
                            
                        <?php
                        }
                        ?>
                            
                        <div class="clear"></div>
                        <div class="divide"></div>
                        <div class="description">
                            <p><?php print($AL_Video->getVideo()->description); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
            if(user_can_interact_video()) {
                $Comments     = DHMR_Comments('video_id = ' . $VideoID);
                $CommentLabel = "";
                
                if(count($Comments) == 1) {
                    $CommentLabel = count($Comments) . " comment";
                }
                else {
                    $CommentLabel = count($Comments) . " comments";
                }
                ?>
                
                <div class="container">
                    <div class="col-12">
                        <div id="post-comment">
                            <div class="comment-left">
                                <img src="<?php print(get_image("video/AL_Comments_orange.png")); ?>" />
                            </div>
                            <div class="comment-right">
                                <p><?php print($CommentLabel); ?></p>
                                <form action="" method="post" id="new-comment" class="new-comment" data-ajaxcall="<?php print(get_ajax("comment")); ?>" data-nonce="<?php print(create_nonce(get_account_id())); ?>" data-userid="<?php print($_SESSION["user_profile"]->user_id); ?>" data-videoid="<?php print($VideoID); ?>">
                                    <textarea placeholder="Add a comment..."></textarea><br/>
                                    <input type="submit" value="POST COMMENT" />
                                </form>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            
                <div id="video-comments" data-ajaxcall="<?php print(get_ajax("comments")); ?>" data-nonce="<?php print(create_nonce(get_account_id())); ?>" data-videoid="<?php print($VideoID); ?>"></div>
            
            <?php
            }
            else {
            ?>
            
                <div id="video-comments">
                    <?php get_comments($VideoID); ?>
                </div>
            
            <?php
            }
        }
        else {
        ?>
        
            <div id="page-upload">
                <div class="container">
                    <div class="col-12">
                        <h2><span>Step 3: </span>Video Processing</h2>
                    </div>
                </div>
                <div class="container">
                    <div class="col-6">
                        <div class="video-percent">
                            <?php
                            if($AL_Video->getVimeoStatus() == "uploading") {
                                print("34%");
                            }
                            else if($AL_Video->getVimeoStatus() == "transcoding") {
                                print("65%");
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3>Almost There!</h3>
                        <?php
                        if($AL_Video->getVimeoStatus() == "uploading") {
                            print('<p>Your video is currently uploading. Once uploaded we will transcode the video for display.</p>');
                        }
                        else if($AL_Video->getVimeoStatus() == "transcoding") {
                            print('<p>Your video is uploaded and currently being transcoded for display.</p>');
                        }
                        else {
                            // test
                            print('<p>Your video is uploaded and currently being transcoded for display.</p>');
                        }
                        ?>
                        <div class="page-divide"></div>
                        <p>This is your video link:</p>
                        <p class="share-link"><a href="<?php print($AL_Video->getVideoLink()); ?>"><?php print($AL_Video->getVideoLink()); ?></a></p>
                        <p class="share-video">Share your video: 
                            <a href=""><img src="<?php print(get_image("social/AL_FB_orange.png")); ?>" alt="Facebook" /></a>
                            <a href=""><img src="<?php print(get_image("social/AL_Twitter_orange.png")); ?>" alt="Twitter" /></a>
                        </p>
                    </div>
                </div>
                <div class="container">
                    <div class="col-12">
                        <div class="page-divide"></div>
                    </div>
                </div>
            </div>
        
        <?php
        }
        ?>
        
    </div>

<?php get_footer(); ?>
