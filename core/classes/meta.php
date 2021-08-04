<?php

class AL_Meta {
    
    public function __construct() {
        
    }
    
    public function displayPageMeta() {
        if($this->getPageType() == "home") {
            $PageTitle       = "Alvarez Live";
            $PageDescription = "Alvarez Live is the video sharing service for players of Alvarez guitars.";
            
            print('<title>' . $PageTitle . '</title>');
            $this->displayMeta("name", "description", $PageDescription);
            $this->displayMeta("rel", "canonical", get_home());
            $this->displayMeta("property", "fb:app_id", "1015612991784170");
            $this->displayMeta("property", "og:locale", "en_US");
            $this->displayMeta("property", "og:type", "website");
            $this->displayMeta("property", "og:title", $PageTitle);
            $this->displayMeta("property", "og:description", $PageDescription);
            $this->displayMeta("property", "og:url", get_home());
            $this->displayMeta("property", "og:site_name", "Alvarez Live");
            $this->displayMeta("property", "og:image", "");
            $this->displayMeta("name", "twitter:card", "summary");
            $this->displayMeta("name", "twitter:description", $PageDescription);
            $this->displayMeta("name", "twitter:title", $PageTitle);
            $this->displayMeta("name", "twitter:site", "@alvarez_guitars");
            $this->displayMeta("name", "twitter:image", "");
            $this->displayMeta("name", "twitter:creator", "@alvarez_guitars");
        }
        else if($this->getPageType() == "category") {
            $RequestedPage = get_requested_page();
            $PageTitle       = ucfirst($RequestedPage) . " - Alvarez Live";
            $PageDescription = "Alvarez Live is the video sharing service for players of Alvarez guitars.";
            
            print('<title>' . $PageTitle . '</title>');
            $this->displayMeta("name", "description", $PageDescription);
            $this->displayMeta("rel", "canonical", get_current_url());
            $this->displayMeta("property", "fb:app_id", "1015612991784170");
            $this->displayMeta("property", "og:locale", "en_US");
            $this->displayMeta("property", "og:type", "website");
            $this->displayMeta("property", "og:title", $PageTitle);
            $this->displayMeta("property", "og:description", $PageDescription);
            $this->displayMeta("property", "og:url", get_current_url());
            $this->displayMeta("property", "og:site_name", "Alvarez Live");
            $this->displayMeta("property", "og:image", "");
            $this->displayMeta("name", "twitter:card", "summary");
            $this->displayMeta("name", "twitter:description", $PageDescription);
            $this->displayMeta("name", "twitter:title", $PageTitle);
            $this->displayMeta("name", "twitter:site", "@alvarez_guitars");
            $this->displayMeta("name", "twitter:image", "");
            $this->displayMeta("name", "twitter:creator", "@alvarez_guitars");
        }
        else if($this->getPageType() == "profile") {
            $Username = get_requested_username();

            $AL_User = new AL_User($Username);
            $AL_User->init();
            
            $PageTitle       = $AL_User->getFullName() . " - Alvarez Live";
            $PageDescription = $AL_User->getUser()->bio;
            
            print('<title>' . $PageTitle . '</title>');
            $this->displayMeta("name", "description", $PageDescription);
            $this->displayMeta("rel", "canonical", get_current_url());
            $this->displayMeta("property", "fb:app_id", "1015612991784170");
            $this->displayMeta("property", "og:locale", "en_US");
            $this->displayMeta("property", "og:type", "website");
            $this->displayMeta("property", "og:title", $PageTitle);
            $this->displayMeta("property", "og:description", $PageDescription);
            $this->displayMeta("property", "og:url", get_current_url());
            $this->displayMeta("property", "og:site_name", "Alvarez Live");
            $this->displayMeta("property", "og:image", $AL_User->getPicture());
            $this->displayMeta("name", "twitter:card", "summary");
            $this->displayMeta("name", "twitter:description", $PageDescription);
            $this->displayMeta("name", "twitter:title", $PageTitle);
            $this->displayMeta("name", "twitter:site", "@alvarez_guitars");
            $this->displayMeta("name", "twitter:image", $AL_User->getPicture());
            $this->displayMeta("name", "twitter:creator", "@alvarez_guitars");
        }
        else if($this->getPageType() == "video") {
            $VideoID = get_requested_video();

            $AL_Video = new AL_Video($VideoID);
            $AL_Video->init();
            
            $PageTitle       = $AL_Video->getVideo()->title . " - Alvarez Live";
            $PageDescription = $AL_Video->getVideo()->description;
            
            print('<title>' . $PageTitle . '</title>');
            $this->displayMeta("name", "description", $PageDescription);
            $this->displayMeta("rel", "canonical", get_current_url());
            $this->displayMeta("property", "fb:app_id", "1015612991784170");
            $this->displayMeta("property", "og:locale", "en_US");
            $this->displayMeta("property", "og:type", "website");
            $this->displayMeta("property", "og:title", $PageTitle);
            $this->displayMeta("property", "og:description", $PageDescription);
            $this->displayMeta("property", "og:url", get_current_url());
            $this->displayMeta("property", "og:site_name", "Alvarez Live");
            $this->displayMeta("property", "og:image", $AL_Video->getPicture());
            $this->displayMeta("name", "twitter:card", "summary");
            $this->displayMeta("name", "twitter:description", $PageDescription);
            $this->displayMeta("name", "twitter:title", $PageTitle);
            $this->displayMeta("name", "twitter:site", "@alvarez_guitars");
            $this->displayMeta("name", "twitter:image", $AL_Video->getPicture());
            $this->displayMeta("name", "twitter:creator", "@alvarez_guitars");
        }
    }
    
    public function getPageType() {
        $RequestedPage = get_requested_page();
        
        if($RequestedPage == "") {
            return("home");
        }
        else if($RequestedPage == "user") {
            return("profile");
        }
        else if($RequestedPage == "video") {
            return("video");
        }
        else if(($RequestedPage == "performances") || ($RequestedPage == "educational") || ($RequestedPage == "guitars")) {
            return("category");
        }
        
        return("");
    }
    
    public function displayMetaName($name, $content) {
        print('<meta name="' . $name . '" content="' . $content . '" />');
    }
    
    public function displayMetaRel($rel, $href) {
        print('<link rel="' . $rel . '" href="' . $href . '" />');
    }
    
    public function displayMetaProperty($property, $content) {
        print('<meta property="' . $property . '" content="' . $content . '" />');
    }
    
    public function displayMeta($type, $key, $value) {
        if($type == "name") {
            $this->displayMetaName($key, $value);
        }
        else if($type == "rel") {
            $this->displayMetaRel($key, $value);
        }
        else if($type == "property") {
            $this->displayMetaProperty($key, $value);
        }
    }
    
}

?>