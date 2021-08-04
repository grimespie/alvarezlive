<?php

class AL_Admin {
    
    private $admin = array();
    private $featured_song;
    private $featured_artist;
    private $featured_lesson;
    
    public function __construct() {
        $this->init();
    }
    
    private function init() {
        $this->admin = DHMR_Admin();

        $this->featured_song = new AL_Video($this->admin[0]->featured_song);
        $this->featured_song->init();

        $this->featured_artist = new AL_User();
        $this->featured_artist->setUserID($this->admin[0]->featured_artist);
        $this->featured_artist->init();
        
        $this->featured_lesson = new AL_Video($this->admin[0]->featured_lesson);
        $this->featured_lesson->init();
    }
    
    public function getFeaturedSong() {
        return($this->featured_song);
    }
    
    public function getFeaturedArtist() {
        return($this->featured_artist);
    }
    
    public function getFeaturedLesson() {
        return($this->featured_lesson);
    }
    
}

?>
