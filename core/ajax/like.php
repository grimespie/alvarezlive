<?php
if(verify_nonce(get_account_id(), $_POST["nonce"])) {
    $Interaction  = DHMR_User_Interactions('user_id = ' . $_POST["user_id"] . ' and video_id = ' . $_POST["video_id"]);
    $Video        = DHMR_Video('video_id = ' . $_POST["video_id"]);
    $AddVideoLike = true;
    $VideoLikes   = $Video[0]->like_count;
    
    $AL_Video = new AL_Video($_POST["video_id"]);
    $AL_Video->init();
    
    if(count($Interaction) > 0) {
        $LikeInd = USER_INTERACT_YES;
        
        if($Interaction[0]->like_ind == USER_INTERACT_YES) {
            $LikeInd      = USER_INTERACT_NO;
            $AddVideoLike = false;
        }
        
        if($LikeInd == USER_INTERACT_YES) {
            user_alert_like_video($AL_Video->getVideoID(), $AL_Video->getUser()->getEmail());
        }
        
        $Status = DHMU_User_Interaction(array("like_ind" => $LikeInd), array("user_id" => $_POST["user_id"], "video_id" => $_POST["video_id"]));
    }
    else {
        $Status = DHMI_User_Interaction(array("user_id" => $_POST["user_id"], "video_id" => $_POST["video_id"], "like_ind" => USER_INTERACT_YES));
        
        user_alert_like_video($AL_Video->getVideoID(), $AL_Video->getUser()->getEmail());
    }
    
    if($AddVideoLike) {
        $VideoLikes++;
    }
    else {
        $VideoLikes--;
    }
    
    $Status = DHMU_Video(array("like_count" => $VideoLikes), array("video_id" => $_POST["video_id"]));
    
    if($AddVideoLike) {
        print(true);
    }
    else {
        print(false);
    }
}
?>