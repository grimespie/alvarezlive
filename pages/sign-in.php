<?php
global $API_Controller;

if(isset($_GET["platform"])) {
    if($_GET["platform"] == "facebook") {
        $fb = $API_Controller->get_facebook_instance();
        
        $helper = $fb->getRedirectLoginHelper();
        
        try {
            $accessToken = $helper->getAccessToken(get_link("sign-in") . "?platform=facebook");
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        
        if(isset($accessToken)) {
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            $_SESSION['facebook_id']           = "";
        
            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            
            $response = $fb->get("/me?fields=id");
            
            $user = $response->getGraphUser();
            
            $_SESSION['facebook_id'] = $user->getID();
            
            $UserAccount = DHMR_User_Account("facebook", $user->getID());
            
            if(count($UserAccount) == 0) {
                header("Location: " . get_link("create-profile"));
                exit();
            }
            else {
                load_user_account();
                
                go_to_profile();
            }
            
            header("Location: " . get_home());
            exit();
        } elseif ($helper->getError()) {
            // The user denied the request
            exit;
        }
    }
    else if($_GET["platform"] == "twitter") {
        $twitter = $API_Controller->get_twitter_instance();
        
        $twitter->setOauthToken($_SESSION["twitter_oauth_token"], $_SESSION["twitter_oauth_token_secret"]);
        
        $access_token = $twitter->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST["oauth_verifier"]));
        
        $_SESSION['twitter_access_token'] = $access_token;
        
        $twitter->setOauthToken($_SESSION["twitter_access_token"]["oauth_token"], $_SESSION["twitter_access_token"]["oauth_token_secret"]);
        
        $user = $twitter->get("account/verify_credentials");
        
        $_SESSION['twitter_id'] = $user->id;
        
        $UserAccount = DHMR_User_Account("twitter", $user->id);
            
        if(count($UserAccount) == 0) {
            header("Location: " . get_link("create-profile"));
            exit();
        }
        else {
            load_user_account();
            
            go_to_profile();
        }
        
        header("Location: " . get_home());
        exit();
    }
    else if($_GET["platform"] == "google") {
        $google = $API_Controller->get_google_instance();
        
        $google->setRedirectUri("https://alvarezlive.com/sign-in/?platform=google"); // TEMP
        $google->authenticate($_GET["code"]);
        
        $access_token = $google->getAccessToken();
        
        $_SESSION['google_access_token'] = $access_token;
        
        $google->setAccessToken($_SESSION['google_access_token']);
        
        $plus = new Google_Service_Plus($google);
        $user = $plus->people->get("me");
        
        $_SESSION['google_id'] = $user->id;
        
        $UserAccount = DHMR_User_Account("google", $user->id);
            
        if(count($UserAccount) == 0) {
            header("Location: " . get_link("create-profile"));
            exit();
        }
        else {
            load_user_account();
            
            go_to_profile();
        }
        
        header("Location: " . get_home());
        exit();
    }
}
?>
