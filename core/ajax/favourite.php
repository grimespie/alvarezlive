<?php
if(verify_nonce(get_account_id(), $_POST["nonce"])) {
    $Interaction       = DHMR_User_Interactions('user_id = ' . $_POST["user_id"] . ' and video_id = ' . $_POST["video_id"]);
    $Video             = DHMR_Video('video_id = ' . $_POST["video_id"]);
    $AddVideoFavourite = true;
    $VideoFavourites   = $Video[0]->favourite_count;
    
    if(count($Interaction) > 0) {
        $FavouriteInd = USER_INTERACT_YES;
        
        if($Interaction[0]->favourite_ind == USER_INTERACT_YES) {
            $FavouriteInd      = USER_INTERACT_NO;
            $AddVideoFavourite = false;
        }
        
        $Status = DHMU_User_Interaction(array("favourite_ind" => $FavouriteInd), array("user_id" => $_POST["user_id"], "video_id" => $_POST["video_id"]));
    }
    else {
        $Status = DHMI_User_Interaction(array("user_id" => $_POST["user_id"], "video_id" => $_POST["video_id"], "favourite_ind" => USER_INTERACT_YES));
    }
    
    if($AddVideoFavourite) {
        $VideoFavourites++;
    }
    else {
        $VideoFavourites--;
    }
    
    $Status = DHMU_Video(array("favourite_count" => $VideoFavourites), array("video_id" => $_POST["video_id"]));
    
    if($AddVideoFavourite) {
        print(true);
    }
    else {
        print(false);
    }
}
?>