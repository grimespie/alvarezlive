<?php

$Errors = array();

if(!user_can_upload_video()) {
    header("Location: " . get_link("create-profile"));
    exit();
}

if(isset($_POST["nonce"])) {
    if(verify_nonce_field(get_account_id())) {
        global $API_Controller;
    
        $vimeo = $API_Controller->get_vimeo_instance();
        
        if((isset($_FILES["video_file"])) && ($_FILES["video_file"]["error"] != 0)) {
            if($_POST["youtube_link"] == "") {
                $Errors["video_file"] = "Please select a video to upload or enter a YouTube URL.";
            }
        }
        
        //if((isset($_FILES["video_thumbnail"])) && ($_FILES["video_thumbnail"]["error"] != 0)) {
        //    $Errors["video_thumbnail"] = "Please select a thumbnail to use with the video.";
        //}
        
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
            $ImageID = "null";
            
            if((isset($_FILES["video_thumbnail"])) && ($_FILES["video_thumbnail"]["tmp_name"] != "")) {
                $ImageID = store_uploaded_image($_FILES["video_thumbnail"], "thumbnail");
            }
            
            $NewVideo = $_POST;
            
            // call vimeo & get vimeo id
            if($_POST["youtube_link"] == "") {
                $VideoID = upload_vimeo_video($_FILES["video_file"]["tmp_name"], true, $_POST["title"], $_POST["description"]);
                $NewVideo["vimeo_id"] = $VideoID;
            }
            else {
                $NewVideo["youtube_ind"]  = YT_IND_YES;
                $NewVideo["youtube_link"] = $_POST["youtube_link"];
            }
            
            // insert onto database with vimeo id
            $NewVideo["user_id"]            = $_SESSION["user_profile"]->user_id;
            $NewVideo["title"]              = $_POST["title"];
            $NewVideo["description"]        = $_POST["description"];
            $NewVideo["thumbnail_image_id"] = $ImageID;
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
            
            $Status = DHMI_Video($NewVideo);
            
            // Get video id
            $Return = array();
            
            if($_POST["youtube_link"] == "") {
                $Return = DHMR_Video('vimeo_id = "' . $VideoID . '"');
            }
            else {
                $Return = DHMR_Video('title = "' . $_POST["title"] . '" and youtube_link = "' . $_POST["youtube_link"] . '"');
            }
            
            // send admin email
            admin_alert_new_video($Return[0]->video_id);
            
            // redirect to video page
            go_to_video($Return[0]->video_id);
        }
    }
}

get_header(); ?>

    <div id="page-upload">
        <div class="container">
            <div class="col-12">
                <h1>Upload Video</h1>
                <div class="page-divide"></div>
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
        
        <form action="<?php print(get_link("upload")); ?>" method="post" enctype="multipart/form-data">
            <?php create_nonce_field(get_account_id()); ?>
            <div class="container">
                <div class="col-12">
                    <h2><span>Step 1: </span>Choose Video and Optional Thumbnail</h2>
                </div>
            </div>
            <div class="container">
                <div class="col-4">
                    <div class="upload-box video-src">
                        <img src="<?php print(get_image("video/AL_Choose.png")); ?>" />
                        <h3>Choose Video</h3>
                        <div class="upload-result">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            <div class="upload-result-text">Video Chosen - Ready for Step 2</div>
                        </div>
                    </div>
                    <div class="file-upload video">
                        <input type="file" name="video_file" class="upload" />
                    </div>
                    <div class="video-or">OR</div>
                </div>
                <div class="col-4">
                    <div class="upload-box import-youtube">
                        <img src="<?php print(get_image("video/AL_YoutubeUpload.png")); ?>" />
                        <h3>Import video from YouTube</h3>
                        <div class="upload-result">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            <div class="upload-result-text">Video Chosen - Ready for Step 2</div>
                        </div>
                    </div>
                    <input id="hidden-youtube-submit" type="hidden" name="youtube_link" value="" />
                    <div class="video-divide"></div>
                </div>
                <div class="col-4">
                    <div class="upload-box thumbnail">
                        <img src="<?php print(get_image("video/AL_Choose.png")); ?>" />
                        <h3>Optional Thumbnail (570px x 320px)</h3>
                        <div class="upload-result">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            <div class="upload-result-text">Thumbnail Chosen</div>
                        </div>
                    </div>
                    <div class="file-upload thumbnail">
                        <input type="file" name="video_thumbnail" class="upload" />
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="col-12">
                    <div class="page-divide"></div>
                    <h2><span>Step 2: </span>Let us know about your video</h2>
                </div>
            </div>
            
            <div class="container">
                <div class="col-6 col-m-12">
                    <label>Video Title*</label>
                    <input type="text" name="title" value="" placeholder="The title of your video..." />
                    <div class="clear"></div>
                    
                    <label>Video Description*</label>
                    <textarea name="description" placeholder="Video description..."></textarea>
                    <div class="clear"></div>
                </div>
                <div class="col-6 right-column col-m-12">
                    <select id="choose-guitar" name="guitar" placeholder="Choose the guitar being used...">
                        <option value="">-- Guitar --</option>
                        <?php
                        $Guitars = get_guitar_list();
                        
                        foreach($Guitars as $Guitar) {
                        ?>
                        
                            <option value='<?php print(json_encode($Guitar)); ?>'><?php print($Guitar->model); ?></option>
                        
                        <?php
                        }
                        ?>
                        
                        <option value='custom'>Other</option>
                    </select>
                    <label>Guitar used</label>
                    <div class="clear"></div>
                    
                    <div id="custom-guitar">
                        <input type="text" name="custom_guitar" value="" placeholder="Enter your guitar model..." />
                        <label>Your guitar model</label>
                        <div class="clear"></div>
                    </div>
                    
                    <select name="category" placeholder="Choose a category for this video...">
                        <option value="">-- Category -- </option>
                        <option value="<?php print(VID_CAT_PERFORMANCES); ?>">Performances</option>
                        <option value="<?php print(VID_CAT_EDUCATIONAL); ?>">Educational</option>
                        <option value="<?php print(VID_CAT_GUITARS); ?>">Guitars</option>
                    </select>
                    <label>Video Category*</label>
                    <div class="clear"></div>
                    
                    <textarea name="tags" placeholder="Choose tags for your video, separated by commas (e.g. acoustic, guitar, song)"></textarea>
                    <label>Tags</label>
                    <div class="clear"></div>
                </div>
            </div>
            
            <div class="container">
                <div class="col-12">
                    <div class="page-divide"></div>
                    <input class="button" type="submit" value="Submit Video" />
                </div>
            </div>
        </form>
    </div>

<?php get_footer(); ?>
