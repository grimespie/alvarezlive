<?php

if($_POST["comment"] != "") {
    $ReplyTo = 0;
    
    $AL_Video = new AL_Video($_POST["video_id"]);
    $AL_Video->init();
    
    if(isset($_POST["reply_to"])) {
        if($_POST["reply_to"] != "") {
            $ReplyTo = $_POST["reply_to"];
        }
    }

    
    $Status = DHMI_Comment(array("user_id" => $_POST["user_id"], "reply_to" => $ReplyTo, "video_id" => $_POST["video_id"], "comment" => $_POST["comment"]));

    user_alert_comment_video($AL_Video->getVideoID(), $AL_Video->getUser()->getEmail());
}

?>
