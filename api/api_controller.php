<?php

/******
 * 
 * Controller for social APIs
 * 
 */

include_once(__DIR__ . "/facebook/vendor/autoload.php");
include_once(__DIR__ . "/twitter/vendor/autoload.php");
include_once(__DIR__ . "/google/vendor/autoload.php");
include_once(__DIR__ . "/vimeo/vendor/autoload.php");
include_once(__DIR__ . "/wideimage/vendor/autoload.php");

use Abraham\TwitterOAuth\TwitterOAuth;

class API_Controller {
    
    private $facebook_app_id     = "1015612991784170";
    private $facebook_app_secret = "25ea091c5ba806c54278eb014b470d56";
    
    private $twitter_app_id      = "CzxMneSuJE7VIacYJ5gm1LTJv";
    private $twitter_app_secret  = "qOnUc4Cgd99euzlxKLnCw0hELOXfaMxvVGUdaH1kY6ttiraRwz";
    
    private $google_app_id       = "330357747552-q90qoiklfgme24msnl5o8eeqa9lh1n9v.apps.googleusercontent.com";
    private $google_app_secret   = "7e9BqMNp-_sNGRJFsueotoU6";
    
    private $vimeo_app_id        = "64575a46165ac90da2ca67f35b05a0a522cf2d62";
    private $vimeo_app_secret    = "MTwQmBtsG1WIiXm34Wntd3LFLgP7Ye2LaBC3WHrtRqHBnchJb1k0J3G2i86moFpWBhX4bUKCJ9xaRoJ/mTbkJVuAtlo7nti5t+TmzMIwuf9SWnikw+MOA7vRpOnl2xNu";
    private $vimeo_access_token  = "e906ccb62e33ce7833cd75eb86bc122c";
    
    public function __construct() {

    }
    
    public function is_logged_in() {
        if(isset($_SESSION['facebook_access_token'])) {
            return(true);
        }
        else if(isset($_SESSION['twitter_access_token'])) {
            return(true);
        }
        else if(isset($_SESSION['google_access_token'])) {
            return(true);
        }

        return(false);
    }
    
    public function get_logged_in_with() {
        if(isset($_SESSION['facebook_access_token'])) {
            return("facebook");
        }
        else if(isset($_SESSION['twitter_access_token'])) {
            return("twitter");
        }
        else if(isset($_SESSION['google_access_token'])) {
            return("google");
        }
        
        return(false);
    }
    
    public function get_account_id() {
        if(isset($_SESSION['facebook_access_token'])) {
            return($_SESSION['facebook_id']);
        }
        else if(isset($_SESSION['twitter_access_token'])) {
            return($_SESSION['twitter_id']);
        }
        else if(isset($_SESSION['google_access_token'])) {
            return($_SESSION['google_id']);
        }
        
        return(false);
    }
    
    public function get_facebook_instance() {
        $fb = new Facebook\Facebook([
            "app_id"     => $this->facebook_app_id,
            "app_secret" => $this->facebook_app_secret,
            'default_graph_version' => 'v2.5',
        ]);
        
        return($fb);
    }
    
    public function get_twitter_instance() {
        $twitter = new TwitterOAuth(
                                    $this->twitter_app_id,
                                    $this->twitter_app_secret, "https://alvarezlive.com"
        );
        
        return($twitter);
    }
    
    public function get_google_instance() {
        $google = new Google_Client();
        
        $google->setClientId($this->google_app_id);
        $google->setClientSecret($this->google_app_secret);
        
        return($google);
    }
    
    public function get_vimeo_instance() {
        $vimeo = new \Vimeo\Vimeo($this->vimeo_app_id, $this->vimeo_app_secret);

        $vimeo->setToken($this->vimeo_access_token);
        
        return($vimeo);
    }
    
    public function get_wideimage_instance() {
        $wideimage = new \WideImage\WideImage();
        
        return($wideimage);
    }
    
}

?>
