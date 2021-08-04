<?php

class AL_User {
    
    private $username    = "";
    private $user_id     = 0;
    private $user        = "";
    private $picture     = "";
    private $videos      = "";
    
    public function __construct($username="") {
        if((isset($username)) && ($username != "")) {
            $this->username = $username;
        }
    }
    
    public function setUserID($user_id) {
        $this->user_id = $user_id;
    }
    
    public function init($for_comments=false) {
        $DHMR_User = array();
        
        if((isset($this->username)) && ($this->username != "")) {
            $DHMR_User = DHMR_User('username = "' . $this->username . '"');
            
            $this->user_id = $DHMR_User[0]->user_id;
        }
        else if((isset($this->user_id)) && ($this->user_id != 0)) {
            $DHMR_User = DHMR_User('user_id = ' . $this->user_id);
        }
        
        if(count($DHMR_User) == 0) {
            if($for_comments) {
                return(false);
            }
            else {
                //display_404();
                //exit();
            }
        }
        
        $this->user    = $DHMR_User[0];
        $this->picture = get_user_image($this->user->profile_image_id);
        $this->videos  = DHMR_Video('user_id = ' . $this->user->user_id);
        
        return(true);
    }
    
    public function comment_init() {
        return($this->init(true));
    }
    
    public function getUserID() {
        return($this->user_id);
    }
    
    public function getUsername() {
        return($this->username);
    }
    
    public function getUser() {
        return($this->user);
    }
    
    public function getEmail() {
        return($this->getUser()->email);
    }
    
    public function getFullName() {
        return($this->getUser()->first_name . " " . $this->getUser()->last_name);
    }
    
    public function getPicture() {
        return($this->picture);
    }
    
    public function getVideos() {
        return($this->videos);
    }
    
    public function getVideoCount() {
        return(count($this->videos));
    }
    
    public function getVideoLabel() {
        $video_count = $this->getVideoCount();
        $video_text  = "Videos";
        
        if($video_count == 1) {
            $video_text  = "Video";
        }
        
        return($video_count . " " . $video_text);
    }
    
    public function getVideoViewCount() {
        $total = 0;
        
        foreach($this->videos as $video) {
            $total += $video->view_count;
        }
        
        return($total);
    }
    
    public function getVideoViewLabel() {
        return(number_format($this->getVideoViewCount()));
    }
    
    public function getVideoLikeCount() {
        $total = 0;
        
        foreach($this->videos as $video) {
            $total += $video->like_count;
        }
        
        return($total);
    }
    
    public function getVideoLikeLabel() {
        return(number_format($this->getVideoLikeCount()));
    }
    
    public function getProfileLink() {
        return(get_link("user/" . $this->getUser()->username));
    }
    
    public function getVideoCommentCount() {
        $Return = DHMR_User_Comment_Count($this->getUser()->user_id);
        
        return($Return[0]->total_comments);
    }
    
    public function getVideoCommentsLabel() {
        return(number_format($this->getVideoCommentCount()));
    }
    
}

?>
