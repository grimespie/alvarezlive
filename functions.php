<?php

function get_header($file="") {
    if($file != "") {
        include_once("pages/templates/header-" . $file . ".php");
    }
    else {
        include_once("pages/templates/header.php");
    }
}

function get_footer() {
    include_once("pages/templates/footer.php");
}

function get_sidebar() {
    include_once("pages/templates/sidebar.php");
}

function get_home() {
    return(BASE_URL);
}

function get_image($filename) {
    return(IMG_BASENAME . $filename);
}

function get_asset($asset) {
    return(BASE_URL . $asset);
}

function get_template($template) {
    require("pages/templates/" . $template . ".php");
}

function get_link($page) {
    return(get_home() . $page . "/");
}

function get_ajax($call) {
    return(get_home() . "ajax/" . $call . "/");
}

function get_facebook_login_url() {
    global $API_Controller;

    $fb = $API_Controller->get_facebook_instance();

    $helper = $fb->getRedirectLoginHelper();
    
    $permissions = ["email"];
    
    $facebook_login_url = $helper->getLoginUrl(get_link("sign-in") . "?platform=facebook", $permissions);
    
    return($facebook_login_url);
}

function get_twitter_login_url() {
    global $API_Controller;
        
    $twitter = $API_Controller->get_twitter_instance();
    
    $request_token = $twitter->oauth("oauth/request_token", array("oauth_callback" => get_link("sign-in") . "?platform=twitter"));
    
    $_SESSION["twitter_oauth_token"]        = $request_token["oauth_token"];
    $_SESSION["twitter_oauth_token_secret"] = $request_token["oauth_token_secret"];
    
    $twitter_login_url = $twitter->url("oauth/authorize", array("oauth_token" => $request_token["oauth_token"]));
    
    return($twitter_login_url);
}

function get_google_login_url() {
    global $API_Controller;
        
    $google = $API_Controller->get_google_instance();
    
    $google->addScope("https://www.googleapis.com/auth/plus.login");
    $google->addScope("https://www.googleapis.com/auth/plus.me");
    $google->addScope("https://www.googleapis.com/auth/userinfo.email");
    $google->addScope("https://www.googleapis.com/auth/userinfo.profile");
    
    $google->setRedirectUri(get_link("sign-in") . "?platform=google");
    //$google->setRedirectUri("http://alvarezlive.paulandsam.co");  // TEMP
    
    $google_login_url = $google->createAuthUrl();
    
    return($google_login_url);
}

function get_logout_url() {
    /*
    if(isset($_SESSION['facebook_access_token'])) {
        global $API_Controller;
            
        $fb = $API_Controller->get_facebook_instance();
        
        $helper = $fb->getRedirectLoginHelper();
        
        $facebook_logout_url = $helper->getLogoutUrl($_SESSION['facebook_access_token'], get_link("logout"));
        
        return($facebook_logout_url);
    }
    */
    
    return(get_link("logout"));
}

function get_social_profile() {
    global $API_Controller;
    
    $profile = new stdClass();
    
    // Facebook
    if(isset($_SESSION['facebook_access_token'])) {
        $fb = $API_Controller->get_facebook_instance();
    
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
        
        $response = $fb->get("/me?fields=id,first_name,last_name,website,email,picture,bio,link");
        
        $user = $response->getGraphUser();
        
        $profile->facebook_id  = $user->getID();
        $profile->first_name   = $user->getFirstName();
        $profile->last_name    = $user->getLastName();
        $profile->email        = $user->getEmail();
        $profile->website_url  = $user->getField("website");
        $profile->bio          = $user->getField("bio");
        $profile->facebook_url = $user->getLink();
        //$profile->picture      = $user->getPicture()->getUrl();
        $profile->picture      = 'https://graph.facebook.com/' . get_account_id() . '/picture?access_token=' . $_SESSION['facebook_access_token'] . '&type=large';
        
        return($profile);
    }
    else if(isset($_SESSION['twitter_access_token'])) {
        $twitter = $API_Controller->get_twitter_instance();
        
        $twitter->setOauthToken($_SESSION["twitter_access_token"]["oauth_token"], $_SESSION["twitter_access_token"]["oauth_token_secret"]);
        
        $user = $twitter->get("account/verify_credentials", array("include_email" => true));
        
        $profile->twitter_id   = $user->id;
        $profile->first_name   = $user->name;
        $profile->last_name    = "";
        $profile->email        = ""; //$user->email;  // Have requested access
        $profile->website_url  = $user->entities->url->urls[0]->display_url;
        $profile->bio          = $user->description;
        $profile->twitter_url  = "https://www.twitter.com/" . $user->screen_name;
        $profile->picture      = preg_replace("/_normal/", "_400x400", $user->profile_image_url);
        
        return($profile);
    }
    else if(isset($_SESSION['google_access_token'])) {
        $google = $API_Controller->get_google_instance();
        
        $google->setAccessToken($_SESSION['google_access_token']);
        
        $plus = new Google_Service_Plus($google);
        $user = $plus->people->get("me");
        
        $Emails = $user->getEmails();
        
        $profile->google_id    = $user->getId();
        $profile->first_name   = $user->getName()->getGivenName();
        $profile->last_name    = $user->getName()->getFamilyName();
        $profile->email        = $Emails[0]->getValue();
        $profile->website_url  = $user->getDomain();
        $profile->bio          = $user->getAboutMe();
        $profile->google_url   = $user->getUrl();
        $profile->picture      = preg_replace("/sz=50/", "sz=250", $user->getImage()->getUrl());
        
        return($profile);
    }
    
    return(false);
}

// Used on the profile creation form
function display_profile_field_value($submitted_value, $social_value) {
    if(isset($submitted_value)) {
        print($submitted_value);
    }
    else if($social_value != "") {
        print($social_value);
    }
    else {
        print("");
    }
}

function load_user_account() {
    $UserAccount = DHMR_User_Account(get_logged_in_with(), get_account_id());
    
    if(count($UserAccount) == 0) {
        return(false);
    }
    
    $User = DHMR_User('user_id = "' . $UserAccount[0]->user_id . '"');
    
    $_SESSION["user_profile"] = $User[0];
    
    $_SESSION["user_profile"]->profile_picture = get_user_image($_SESSION["user_profile"]->profile_image_id);
    
    return(true);
}

function go_to_profile() {
    if(!isset($_SESSION["user_profile"])) {
        return(false);
    }
    
    header("Location: " . get_link("user/" . $_SESSION["user_profile"]->username));
    exit();
}

function go_to_video($video_id) {
    if($video_id == "") {
        return(false);
    }
    
    header("Location: " . get_link("video/" . $video_id));
    exit();
}

function is_logged_in() {
    global $API_Controller;
    
    return($API_Controller->is_logged_in());
}

function has_profile() {
    if(!is_logged_in()) {
        return(false);
    }

    $UserAccount = DHMR_User_Account(get_logged_in_with(), get_account_id());
    
    if(count($UserAccount) == 0) {
        return(false);
    }
    
    return(true);
}

function get_account_id() {
    global $API_Controller;
    
    return($API_Controller->get_account_id());
}

function get_user_id() {
    if(is_logged_in()) {
        $UserAccount = DHMR_User_Account(get_logged_in_with(), get_account_id());
    
        if(count($UserAccount) == 0) {
            return(false);
        }
        
        return($UserAccount[0]->user_id);
    }
}

function get_logged_in_with() {
    global $API_Controller;
    
    return($API_Controller->get_logged_in_with());
}

function create_nonce($id) {
    $nonce = hash("sha512", $id);
    
    return($nonce);
}

function verify_nonce($id, $nonce) {
    $test_nonce = create_nonce($id);
    
    if($test_nonce == $nonce) {
        return(true);
    }
    
    return(false);
}

function create_nonce_field($id) {
    print('<input id="nonce" type="hidden" name="nonce" value="' . create_nonce($id) . '" />');
}

function verify_nonce_field($id) {
    global $_POST;
    
    if(!isset($_POST["nonce"])) {
        return(false);
    }
    
    return(verify_nonce($id, $_POST["nonce"]));
}

function get_facebook_profile_picture() {
    if(isset($_SESSION['facebook_access_token'])) {
        return('https://graph.facebook.com/' . get_account_id() . '/picture?access_token=' . $_SESSION['facebook_access_token'] . '&type=large');
    }
}

function get_supported_images() {
    $supported_images = array(
        "gif",
        "jpg",
        "jpeg",
        "png"
    );
    
    return($supported_images);
}

function get_data_base() {
    return(__DIR__ . "/data/");
}

function get_profile_image_base() {
    return(get_data_base() . "profile/");
}

function get_thumbnail_image_base() {
    return(get_data_base() . "thumbnail/");
}

function gen_profile_image_filename($original_file, $type="", $folder="profile") {
    $extension = "";
    
    if($type != "") {
        $extension = $type;
    }
    else {
        if(preg_match("/.*\.(.+)$/", $original_file, $matches)) {
            $extension = $matches[1];
        }
    }
    
    $base = get_profile_image_base();
    
    if($folder == "thumbnail") {
        $base = get_thumbnail_image_base();
    }
    
    $file = $base . rand(10000000, 99999999) . "_" . get_account_id() . "_" . rand(10000000, 99999999) . "." . $extension;
    
    return($file);
}

function create_new_image($file) {
    $url = str_replace(__DIR__, "", $file);
    
    $Status = DHMI_Image(array("url" => $url));
    
    $Return = DHMR_Image('url = "' . $url . '"');
    
    return($Return[0]->image_id);
}

function get_social_image($url, $type="") {
    $file = gen_profile_image_filename($url, $type, "profile");
    
    file_put_contents($file, file_get_contents($url));
    
    $image_id = create_new_image($file);
    
    return($image_id);
}

function get_vimeo_image($url, $type="") {
    $file = gen_profile_image_filename($url, $type, "thumbnail");
    
    file_put_contents($file, file_get_contents($url));
    
    $image_id = create_new_image($file);
    
    return($image_id);
}

function store_uploaded_image($uploaded_file, $folder="profile") {
    global $API_Controller;
    
    $desired_width  = 200;
    $desired_height = 200;
    
    if($folder == "thumbnail") {
        $desired_width  = 567;
        $desired_height = 318;
    }
    
    $new_width      = $desired_width;
    $new_height     = $desired_height;
    
    $wideimage = $API_Controller->get_wideimage_instance();
    
    $file = gen_profile_image_filename($uploaded_file["name"], "", $folder);
    
    // resize image here
    list($width, $height, $type, $attr) = getimagesize($uploaded_file["tmp_name"]);
    
    if($width < $desired_width) {
        $new_height = ($width / $desired_width) * $desired_height;
        $new_width  = $width;
    }
    
    $crop_width  = $width;
    $crop_height = ($width / $new_width) * $new_height;
    
    $wideimage->load($uploaded_file["tmp_name"])->crop("center", "center", $crop_width, $crop_height)->resize($new_width, $new_height)->saveToFile($file);
    
    unlink($uploaded_file["tmp_name"]);
    
    $image_id = create_new_image($file);
    
    return($image_id);
}

function get_user_image($image_id) {
    $Return = DHMR_Image('image_id = "' . $image_id . '"');
    
    $url = get_home() . $Return[0]->url;
    
    return($url);
}

function get_youtube_videos() {
    global $API_Controller;
    
    $google = $API_Controller->get_google_instance();

    $google->setAccessToken($_SESSION['google_access_token']);
    
    $youtube = new Google_Service_YouTube($google);
    
    return($youtube->videos);
}

function user_can_create_profile() {
    if(!is_logged_in()) {
        return(false);
    }
    else if(has_profile()) {
        return(false);
    }
    
    return(true);
}

function user_can_upload_video() {
    if(!is_logged_in()) {
        return(false);
    }
    else if(!has_profile()) {
        return(false);
    }
    
    return(true);
}

function user_can_interact_video() {
    if(!is_logged_in()) {
        return(false);
    }
    else if(!has_profile()) {
        return(false);
    }
    
    return(true);
}

function get_guitar_list() {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://www.alvarezguitars.com/guitar-list.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    $GuitarList = curl_exec($ch);

    curl_close($ch);
    
    return(json_decode($GuitarList));
}

function get_vimeo_video($video_id) {
    global $API_Controller;
    
    $vimeo = $API_Controller->get_vimeo_instance();
    
    return($vimeo->request('/me' . $video_id));
}

function upload_vimeo_video($video_file, $upgrade_to_1080=false, $title="", $description="") {
    global $API_Controller;
    
    $vimeo = $API_Controller->get_vimeo_instance();
    
    $response = $vimeo->upload($video_file, $upgrade_to_1080);
    
    $vimeo->request($response["headers"]["Location"], array("name" => $title, "description" => $description), "PATCH");
    
    return($response["headers"]["Location"]);
}

function display_vimeo_iframe($vimeo_id) {
    $VimeoID =  preg_replace("/videos/", "video", $vimeo_id);
        
    print('<iframe src="https://player.vimeo.com' . $VimeoID . '?autoplay=1&byline=0&portrait=0&color=a64c23" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
}

function display_youtube_iframe($link) {
    $embed_link = "";
    
    if(preg_match("/watch\?v=(.*)/", $link, $matches)) {
        $embed_link = "https://www.youtube.com/embed/" . $matches[1];
    }
    else if(preg_match("/youtu\.be\/(.*)/", $link, $matches)) {
        $embed_link = "https://www.youtube.com/embed/" . $matches[1];
    }
    
    print('<iframe width="500" height="281" src="' . $embed_link . '?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>');
}

function get_comments($video_id) {
    $Comments = array_reverse(DHMR_Comments('video_id = ' . $video_id));
    
    if(count($Comments) > 0) {
        $SortedComments = array();
        
        foreach($Comments as $Comment) {
            $CommentID = "comment-" . $Comment->comment_id;
            
            if($Comment->reply_to != 0) {
                $ReplyToID = "comment-" . $Comment->reply_to;
                
                if(!isset($SortedComments[$ReplyToID]->replies)) {
                    $SortedComments[$ReplyToID]->replies = array();
                }
                
                array_push($SortedComments[$ReplyToID]->replies, $Comment);
            }
            else {
                $SortedComments[$CommentID]->comment = $Comment;
            }
        }
        ?>
    
        <div class="container">
            <div class="col-12">
                
                <?php
                foreach($SortedComments as $ID => $Comment) {
                    $User = new AL_User();
                    $User->setUserID($Comment->comment->user_id);
                    
                    if(!$User->comment_init()) {
                        continue;
                    }
                    ?>
                
                    <div class="video-comment">
                        <div class="comment-left">
                            <a href="<?php print($User->getProfileLink()); ?>"><img class="user-picture" src="<?php print($User->getPicture()); ?>" /></a>
                        </div>
                        <div class="comment-right">
                            <a href="<?php print($User->getProfileLink()); ?>"><h3><?php print($User->getFullName()); ?> <span><?php print(date("F jS Y @ g:i", strtotime($Comment->comment->comment_date)) . "EDT"); ?></span></h3></a>
                            <p><?php print($Comment->comment->comment); ?></p>
                            <div class="comment-action">Reply</div>
                            <form action="" method="post" id="<?php print($ID); ?>" class="new-comment reply-to" data-ajaxcall="<?php print(get_ajax("comment")); ?>" data-nonce="<?php print(create_nonce(get_account_id())); ?>" data-userid="<?php print($_SESSION["user_profile"]->user_id); ?>" data-videoid="<?php print($video_id); ?>">
                                <input type="hidden" name="reply_to" value="<?php print($Comment->comment->comment_id); ?>" />
                                <textarea placeholder="Add a comment..."></textarea><br/>
                                <div class="cancel-button">Cancel</div>
                                <input type="submit" value="REPLY" />
                            </form>
                            <div class="comment-replies">
                                <?php
                                if(count($Comment->replies) > 0) {
                                    foreach($Comment->replies as $CommentReply) {
                                        $User = new AL_User();
                                        $User->setUserID($CommentReply->user_id);
                                        
                                        if(!$User->comment_init()) {
                                            continue;
                                        }
                                        ?>
                                        
                                        <div class="comment-reply">
                                            <div class="comment-left">
                                                <a href="<?php print($User->getProfileLink()); ?>"><img class="user-picture" src="<?php print($User->getPicture()); ?>" /></a>
                                            </div>
                                            <div class="comment-right">
                                                <a href="<?php print($User->getProfileLink()); ?>"><h3><?php print($User->getFullName()); ?> <span><?php print(date("F jS Y @ g:i", strtotime($CommentReply->comment_date)) . "EDT"); ?></span></h3></a>
                                                <p><?php print($CommentReply->comment); ?></p>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        
                                    <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                
                <?php
                }
                ?>
                
            </div>
        </div>

    <?php
    }
}

function display_videos($category="", $user_id="", $latest=false, $default_sort="") {
    $Videos       = array();
    $SortCriteria = " order by upload_date desc";
    
    if(isset($_GET["sort"])) {
        if($_GET["sort"] == "newest") {
            $SortCriteria = " order by upload_date desc";
        }
        else if($_GET["sort"] == "oldest") {
            $SortCriteria = " order by upload_date asc";
        }
        if($_GET["sort"] == "most-popular") {
            $SortCriteria = " order by view_count desc";
        }
    }
    else if($default_sort != "") {
        if($default_sort == "newest") {
            $SortCriteria = " order by upload_date desc";
        }
        else if($default_sort == "oldest") {
            $SortCriteria = " order by upload_date asc";
        }
        if($default_sort == "most-popular") {
            $SortCriteria = " order by view_count desc";
        }
    }
    
    if($latest) {
        $Videos = DHMR_Video('status != ' . VID_STAT_NEW . ' and (youtube_ind = ' . YT_IND_YES . ' or (youtube_ind = ' . YT_IND_NO . ' and vimeo_ready = ' . VIMEO_READY_YES . ')) ' . $SortCriteria);
    }
    else {
        if($user_id != "") {
            if(is_logged_in()) {
                if(get_user_id() == $user_id) {
                    $Videos = DHMR_Video('user_id = ' . $user_id . $SortCriteria);
                }
                else {
                    $Videos = DHMR_Video('user_id = ' . $user_id . ' and status != ' . VID_STAT_NEW . $SortCriteria);
                }
            }
            else {
                $Videos = DHMR_Video('user_id = ' . $user_id . ' and status != ' . VID_STAT_NEW . $SortCriteria);
            }
        }
        else {
            $Videos = DHMR_Video('status != ' . VID_STAT_NEW . ' and category = "' . $category . '" and (youtube_ind = ' . YT_IND_YES . ' or (youtube_ind = ' . YT_IND_NO . ' and vimeo_ready = ' . VIMEO_READY_YES . '))' . $SortCriteria);
        }
    }
    
    $Counter = 0;
    
    print('<div class="videos-full-width">');
            
    foreach($Videos as $Video) {
        $Counter++;
        
        display_video(new AL_Video($Video->video_id));
        
        if($Counter >= 3) {
            //print('<div class="clear"></div>');
            
            $Counter = 0;
        }
            
    }
    
    print('</div>');
}

function display_video($video, $override_url="", $featured="", $featured_name="") {
    $video->init();
    
    $link   = get_link("video/" . $video->getVideo()->video_id);
    
    if($override_url != "") {
        $link = $override_url;
    }
    ?>

    <div class="video <?php if($video->isYouTubeThumbnail()) { print('youtube'); } ?>">
        <!-- <img class="video-thumbnail" src="<?php print(get_user_image($video->getVideo()->thumbnail_image_id)); ?>" /> -->
        <img class="video-thumbnail" src="<?php print($video->getPicture()); ?>" />
        <a href="<?php print($link); ?>">
            <div class="video-tile">
                <div class="video-tile-contents">
                    <?php
                    if($featured != "") {
                    ?>
                    
                        <div class="artist"><?php print($featured); ?></div>
                        <div class="song">
                            <?php
                            if($featured_name != "") {
                                print($featured_name);
                            }
                            else {
                            ?>
                                &ldquo;<?php print($video->getVideo()->title); ?>&rdquo;
                            <?php
                            }
                            ?>
                        </div>
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <div class="artist"><?php print($video->getUser()->getFullName()); ?></div>
                        <div class="song">&ldquo;<?php print($video->getVideo()->title); ?>&rdquo;</div>
                        <img src="<?php print(get_image("video/AL_PlayButton.png")); ?>" />
                        
                    <?php
                    }
                    ?>
                </div>
            </div>
        </a>
    </div>

<?php
}

function get_current_url() {
    return(get_home() . $_SERVER["REQUEST_URI"]);
}

function admin_allow() {
    if(is_logged_in()) {
        $UserID = get_user_id();
        
        if($UserID == 2) {
            return(true);  // Alvarez Guitars
        }
        else if($UserID == 3) {
            return(true);  // AcousticLabs
        }
        else if($UserID == 10) {
            return(true);  // Paul Gillespie
        }
    }
    
    return(false);
}

function send_mail($to, $subject, $message, $headers = '', $attachments = array()) {
    if(is_array($to)) {
        $to = implode(",", $to);
    }
    
    if(is_array($headers)) {
        $headers = "MIME-Version: 1.0" . "\r\n" . implode("\r\n", $headers);
    }
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, CRMER_ENDPOINT); 
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "key=" . CRMER_APIKEY . "&to=" . $to . "&subject=" . $subject . "&message=" . urlencode($message) . "&headers=" . $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($ch);
    
    curl_close($ch);
}

function admin_alert_new_video($video_id) {
    $to      = "scriptknight@hotmail.com";
    $subject = "New Video to Approve";
    $message = '<p>There is a new video to approve: <a target="_blank" href="' . get_link("video/" . $video_id) . '">' . get_link("video/" . $video_id) . '</a></p>';
    $headers = array('From: Alvarez Live <webmaster@alvarezlive.com>', 'Content-Type: text/html; charset=UTF-8');
            
    send_mail($to, $subject, $message, $headers);
}

function user_alert_approved_video($video_id, $email) {
    $to      = $email;
    $subject = "Your video has been approved";
    $message = '<p>Congratulations! Your video has been approved: <a target="_blank" href="' . get_link("video/" . $video_id) . '">' . get_link("video/" . $video_id) . '</a></p>';
    $headers = array('From: Alvarez Live <webmaster@alvarezlive.com>', 'Content-Type: text/html; charset=UTF-8');
            
    send_mail($to, $subject, $message, $headers);
}

function user_alert_like_video($video_id, $email) {
    $to      = $email;
    $subject = "Someone just liked your video";
    $message = '<p>Wahoo! Someone just liked your video: <a target="_blank" href="' . get_link("video/" . $video_id) . '">' . get_link("video/" . $video_id) . '</a></p>';
    $headers = array('From: Alvarez Live <webmaster@alvarezlive.com>', 'Content-Type: text/html; charset=UTF-8');
            
    send_mail($to, $subject, $message, $headers);
}

function user_alert_comment_video($video_id, $email) {
    $to      = $email;
    $subject = "Someone just commented on your video";
    $message = '<p>Someone just commented on your video: <a target="_blank" href="' . get_link("video/" . $video_id) . '">' . get_link("video/" . $video_id) . '</a></p>';
    $headers = array('From: Alvarez Live <webmaster@alvarezlive.com>', 'Content-Type: text/html; charset=UTF-8');
            
    send_mail($to, $subject, $message, $headers);
}

?>
