<?php

class AL_Video {
    
    private $video_id    = 0;
    private $video       = "";
    private $vimeo       = "";
    private $user        = "";
    private $interaction = "";
    private $picture     = "";
    private $youtube_thumbnail = false;
    
    private $vimeo_processing  = "Your video is being processed.";
    private $vimeo_transcoding = "We are currently transcoding your video.";
    
    public function __construct($video_id) {
        $this->video_id = $video_id;
    }
    
    public function init() {
        $DHMR_Video = DHMR_Video('video_id = ' . $this->video_id);

        if(count($DHMR_Video) == 0) {
           // display_404();
           // exit();
        }
        
        $this->video = $DHMR_Video[0];
        
        if(($this->is_vimeo()) && (!$this->isVimeoReady())) {
            $this->vimeo = get_vimeo_video($this->video->vimeo_id);
        }
        
        $this->user = new AL_User();
        $this->user->setUserID($this->video->user_id);
        $this->user->init();
        
        if(user_can_interact_video()) {
            $this->interaction = DHMR_User_Interactions('user_id = ' . $_SESSION["user_profile"]->user_id . ' and video_id = ' . $this->video_id);
        }
        
        if($this->video->thumbnail_image_id != 0) {
            $this->picture = get_user_image($this->video->thumbnail_image_id);
        }
        else {
            if(!$this->is_vimeo()) {
                if(preg_match("/watch\?v=(.*)/", $this->video->youtube_link, $matches)) {
                    $this->picture = "http://img.youtube.com/vi/" . $matches[1] . "/hqdefault.jpg";
                    
                    $this->youtube_thumbnail = true;
                }
                else if(preg_match("/youtu\.be\/(.*)/", $this->video->youtube_link, $matches)) {
                    $this->picture = "http://img.youtube.com/vi/" . $matches[1] . "/hqdefault.jpg";
                    
                    $this->youtube_thumbnail = true;
                }
            }
        }
    }
    
    public function isYouTubeThumbnail() {
        return($this->youtube_thumbnail);
    }
    
    public function getVideoID() {
        return($this->video_id);
    }
    
    public function getPicture() {
        return($this->picture);
    }
    
    public function getUserInteraction() {
        return($this->interaction[0]);
    }
    
    public function is_vimeo() {
        if($this->video->vimeo_id != "") {
            return(true);
        }
        
        return(false);
    }
    
    public function isVimeoReady() {
        if($this->video->vimeo_ready == VIMEO_READY_YES) {
            return(true);
        }
        
        return(false);
    }
    
    public function display_status($status) {
    ?>
    
        <div class="video-status-box">
            <div class="video-status"><?php print($status); ?></div>
        </div>
    
    <?php
    }
    
    public function getVimeoStatus() {
        if(!$this->isVimeoReady()) {
            if($this->vimeo["body"]["status"] == "uploading") {
                return("uploading");
            }
            else if($this->vimeo["body"]["status"] == "transcoding") {
                return("transcoding");
            }
            else if($this->vimeo["body"]["status"] == "available") {
                return("available");
            }
        }
        
        return("available");
    }
    
    public function get_video_embed() {
        if($this->is_vimeo()) {
            if(!$this->isVimeoReady()) {
                if($this->getVimeoStatus() == "uploading") {
                    $this->display_status($this->vimeo_processing);
                }
                else if($this->getVimeoStatus() == "transcoding") {
                    $this->display_status($this->vimeo_transcoding);
                }
                else if($this->getVimeoStatus() == "available") {
                    $Status = DHMU_Video(array("vimeo_ready" => (int)VIMEO_READY_YES), array("video_id" => $this->video_id));

                    display_vimeo_iframe($this->video->vimeo_id);
                    
                    $this->check_for_thumbnail();
                }
            }
            else {
                display_vimeo_iframe($this->video->vimeo_id);
                
                $this->check_for_thumbnail();
            }
        }
        else {
            // Must be a youtube link
            display_youtube_iframe($this->video->youtube_link);
        }
    }
    
    public function check_for_thumbnail() {
        if($this->is_vimeo()) {
            if($this->video->thumbnail_image_id == 0) {
                $ThisVimeo = get_vimeo_video($this->video->vimeo_id);
            
                if($ThisVimeo["body"]["pictures"]["uri"] != null) {
                    if(preg_match("/\/([0-9]*?)$/", $ThisVimeo["body"]["pictures"]["uri"], $matches)) {
                        $ThumbnailLink = "https://i.vimeocdn.com/video/" . $matches[1] . "_640.jpg";
                        
                        $ThumbnailID = get_vimeo_image($ThumbnailLink, "jpg");
                        
                        $Status = DHMU_Video(array("thumbnail_image_id" => (int)$ThumbnailID), array("video_id" => $this->video_id));
                    }
                }
            }
        }
    }
    
    public function getVideo() {
        return($this->video);
    }
    
    public function getUser() {
        return($this->user);
    }
    
    public function getVideoViewCount() {
        return(number_format($this->video->view_count));
    }
    
    public function getVideoLikeCount() {
        return(number_format($this->video->like_count));
    }
    
    public function getVideoFavouriteCount() {
        return(number_format($this->video->favourite_count));
    }
    
    public function getVideoCommentCount() {
        $Return = DHMR_Comments('video_id = ' . $this->video_id);
        
        return(number_format(count($Return)));
    }
    
    public function isGuitar() {
        if($this->video->guitar != "") {
            return(true);
        }
        
        return(false);
    }
    
    public function isCurrentModel() {
        if(preg_match("/\{/", $this->video->guitar)) {
            return(true);
        }
        
        return(false);
    }
    
    public function getGuitarModel() {
        if($this->isCurrentModel()) {
            $Guitar = json_decode($this->video->guitar);
            
            return($Guitar->model);
        }
        else {
            return($this->video->guitar);
        }
    }
    
    public function getGuitarLink() {
        $Guitar = json_decode($this->video->guitar);
        
        return($Guitar->url);
    }
    
    public function getCategory() {
        if($this->video->category == VID_CAT_PERFORMANCES) {
            return("Performance");
        }
        else if($this->video->category == VID_CAT_EDUCATIONAL) {
            return("Educational");
        }
        else if($this->video->category == VID_CAT_GUITARS) {
            return("Guitars");
        }
    }
    
    public function getVideoLink() {
        return(get_link("video/" . $this->video_id));
    }
    
    public function needsApproval() {
        if($this->video->status == VID_STAT_NEW) {
            return(true);
        }
        
        return(false);
    }

}

?>
