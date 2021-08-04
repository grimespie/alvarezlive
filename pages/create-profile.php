<?php

$Errors        = array();
$SocialProfile = "";
$AL_User = "";

if($ForceEdit) {
    $AL_User = new AL_User();
    
    $AL_User->setUserID(get_user_id());
    $AL_User->init();
}

if((!user_can_create_profile()) && (!$ForceEdit)) {
    header("Location: " . get_home());
    exit();
}

if(isset($_POST["nonce"])) {
    if(verify_nonce_field(get_account_id())) {
        $SocialProfile = get_social_profile();
        
        if($_POST["first_name"] == "") {
            $Errors["first_name"] = "You must supply your first name.";
        }
        
        if($_POST["last_name"] == "") {
            $Errors["last_name"] = "You must supply your last name.";
        }
        
        if($_POST["email"] == "") {
            $Errors["email"] = "You must supply your email address.";
        }
        
        if($_POST["username"] == "") {
            $Errors["username"] = "You must choose a username for your profile.";
        }
        else if(!preg_match("/^[A-Za-z0-9]+$/", $_POST["username"])) {
            $Errors["username"] = "Your username can only be alphanumeric.";
        }
        
        if($_POST["bio"] == "") {
            $Errors["bio"] = "Please enter your biography.";
        }
        
        if($_POST["bio"] == "") {
            $Errors["bio"] = "Please enter your biography.";
        }
        
        if(count($Errors) == 0) {
            $ImageID = "";
            
            if((isset($_FILES["profile_image"])) && ($_FILES["profile_image"]["error"] == 0)) {
                $ImageID = store_uploaded_image($_FILES["profile_image"], "profile");
            }
            else {
                $Extension = "";
                
                if(get_logged_in_with() == "facebook") {
                    $Extension = "jpg";
                }
                else if(get_logged_in_with() == "google") {
                    $Extension = "png";
                }
                
                $ImageID = get_social_image($SocialProfile->picture, $Extension);
            }
            
            $NewUser = $_POST;
            
            // Create user image
            $NewUser["profile_image_id"] = $ImageID;
            
            // Mailing List
            if($_POST["mailer"]) {
                $NewUser["mailer"] = MAILER_YES;
            }
            else {
                $NewUser["mailer"] = MAILER_NO;
            }
            
            if($ForceEdit) {
                unset($NewUser["nonce"]);
                unset($NewUser["number"]);
                
                $Status = DHMU_User($NewUser, array("user_id" => get_user_id()));
            }
            else {
                // Create entry on users table
                $Status = DHMI_User($NewUser);
                
                // Get the id for the entry created above
                $Return = DHMR_User('username = "' . $_POST["username"] . '"');
                
                // Create entry on users_accounts table
                $Status = DHMI_User_Account(array("user_id" => $Return[0]->user_id, "account_type" => get_logged_in_with(), "account_user_id" => get_account_id()));
            }
            
            load_user_account();
            
            go_to_profile();
        }
    }
}

get_header(); ?>

    <div id="page-create-profile">
        <div class="container">
            <div class="col-12">
                <?php
                if($ForceEdit) {
                    print('<h1>Update Profile</h1>');
                }
                else {
                    print('<h1>Create Profile</h1>');
                }
                ?>
                <div class="page-divide"></div>
            </div>
        </div>
        
        <?php
        if(count($Errors) > 0) {
        ?>
        
            <div class="container">
                <div class="col-12">
                    <ul class="errors">
                    
                    <?php
                    foreach($Errors as $Error) {
                    ?>
                    
                        <li><?php print($Error); ?></li>
                    
                    <?php
                    }
                    ?>
                    
                    </ul>
                </div>
            </div>
        
        <?php
        }
        ?>
        
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <?php
                create_nonce_field(get_account_id());
                
                $SocialProfile = get_social_profile();
                ?>
                <div class="col-6 col-m-12">
                    <label>First Name*</label>
                    <?php
                    if($ForceEdit) {
                    ?>
                    
                        <input type="text" name="first_name" value="<?php print($AL_User->getUser()->first_name); ?>" placeholder="Your first name..." />
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <input type="text" name="first_name" value="<?php display_profile_field_value($_POST["first_name"], $SocialProfile->first_name); ?>" placeholder="Your first name..." />
                    
                    <?php
                    }
                    ?>
                    <div class="clear"></div>
                    
                    <label>Last Name*</label>
                    <?php
                    if($ForceEdit) {
                    ?>
                    
                        <input type="text" name="last_name" value="<?php print($AL_User->getUser()->last_name); ?>" placeholder="Your last name..." />
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <input type="text" name="last_name" value="<?php display_profile_field_value($_POST["last_name"], $SocialProfile->last_name); ?>" placeholder="Your last name..." />
                    
                    <?php
                    }
                    ?>
                    <div class="clear"></div>
                    
                    <div class="email-wrapper">
                        <label>Email*</label>
                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                        <i class="fa fa-check"></i>
                        <i class="fa fa-times"></i>
                        <?php
                        if($ForceEdit) {
                        ?>
                        
                            <input data-emailcheck="<?php print(get_ajax("email")); ?>" id="email" type="text" name="email" value="<?php print($AL_User->getUser()->email); ?>" data-current="<?php print($AL_User->getUser()->email); ?>" placeholder="Your email..." />
                        
                        <?php
                        }
                        else {
                        ?>
                        
                            <input data-emailcheck="<?php print(get_ajax("email")); ?>" id="email" type="text" name="email" value="<?php display_profile_field_value($_POST["email"], $SocialProfile->email); ?>" placeholder="Your email..." />
                        
                        <?php
                        }
                        ?>
                        <div class="clear"></div>
                    </div>
                    
                    <div class="username-wrapper">
                        <label>Username*</label>
                        <i class="fa fa-spinner fa-spin fa-fw"></i>
                        <i class="fa fa-check"></i>
                        <i class="fa fa-times"></i>
                        <?php
                        if($ForceEdit) {
                        ?>
                        
                            <input data-usernamecheck="<?php print(get_ajax("username")); ?>" id="username" type="text" name="username" value="<?php print($AL_User->getUser()->username); ?>" data-current="<?php print($AL_User->getUser()->username); ?>" placeholder="Choose a username..." />
                        
                        <?php
                        }
                        else {
                        ?>
                        
                            <input data-usernamecheck="<?php print(get_ajax("username")); ?>" id="username" type="text" name="username" value="<?php print($_POST["username"]); ?>" placeholder="Choose a username..." />
                        
                        <?php
                        }
                        ?>
                        <div class="clear"></div>
                    </div>
                    
                    <label>Phone Number</label>
                    <?php
                    if($ForceEdit) {
                    ?>
                    
                        <input type="text" name="number" value="<?php print($AL_User->getUser()->phone); ?>" placeholder="Your phone number..." />
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <input type="text" name="number" value="<?php print($_POST["number"]); ?>" placeholder="Your phone number..." />
                    
                    <?php
                    }
                    ?>
                    <div class="clear"></div>
                    
                    <label>Bio*</label>
                    <?php
                    if($ForceEdit) {
                    ?>
                    
                        <textarea name="bio" placeholder="Your phone number..."><?php print($AL_User->getUser()->bio); ?></textarea>
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <textarea name="bio" placeholder="Your phone number..."><?php display_profile_field_value($_POST["bio"], $SocialProfile->bio); ?></textarea>
                    
                    <?php
                    }
                    ?>
                    <div class="clear"></div>
                    
                    <label>Website</label>
                    <?php
                    if($ForceEdit) {
                    ?>
                    
                        <input type="text" name="website_url" value="<?php print($AL_User->getUser()->website_url); ?>" placeholder="Your website url..." />
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <input type="text" name="website_url" value="<?php display_profile_field_value($_POST["website_url"], $SocialProfile->website_url); ?>" placeholder="Your website url..." />
                    
                    <?php
                    }
                    ?>
                    <div class="clear"></div>
                </div>
                <div class="col-6 right-column col-m-12">
                    <?php
                    if($ForceEdit) {
                    ?>
                    
                        <input <?php if($AL_User->getUser()->mailer) { print('checked'); } ?> type="checkbox" name="mailer" />
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <input <?php if($_POST["mailer"]) { print('checked'); } ?> type="checkbox" name="mailer" />
                    
                    <?php
                    }
                    ?>
                    <label>Mailing List Member</label>
                    <div class="clear"></div>
                    
                    <?php
                    if($ForceEdit) {
                    ?>
                    
                        <input type="text" name="facebook_url" value="<?php print($AL_User->getUser()->facebook_url); ?>" placeholder="Your Facebook profile url..." />
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <input type="text" name="facebook_url" value="<?php display_profile_field_value($_POST["facebook_url"], $SocialProfile->facebook_url); ?>" placeholder="Your Facebook profile url..." />
                    
                    <?php
                    }
                    ?>
                    <label>Facebook URL</label>
                    <div class="clear"></div>
                    
                    <?php
                    if($ForceEdit) {
                    ?>
                    
                        <input type="text" name="twitter_url" value="<?php print($AL_User->getUser()->twitter_url); ?>" placeholder="Your Twitter profile url..." />
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <input type="text" name="twitter_url" value="<?php display_profile_field_value($_POST["twitter_url"], $SocialProfile->twitter_url); ?>" placeholder="Your Twitter profile url..." />
                    
                    <?php
                    }
                    ?>
                    <label>Twitter URL</label>
                    <div class="clear"></div>
                    
                    <?php
                    if($ForceEdit) {
                    ?>
                    
                        <input type="text" name="youtube_url" value="<?php print($AL_User->getUser()->youtube_url); ?>" placeholder="Your Youtube profile url..." />
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <input type="text" name="youtube_url" value="<?php display_profile_field_value($_POST["youtube_url"], $SocialProfile->youtube_url); ?>" placeholder="Your Youtube profile url..." />
                    
                    <?php
                    }
                    ?>
                    <label>Youtube URL</label>
                    <div class="clear"></div>
                    
                    <?php
                    if($ForceEdit) {
                    ?>
                    
                        <select name="default_sort" placeholder="Choose the default video sorting method...">
                            <option value="newest" <?php if($AL_User->getUser()->default_sort == "newest") { print('selected'); } ?>>Date added (newest)</option>
                            <option value="oldest" <?php if($AL_User->getUser()->default_sort == "oldest") { print('selected'); } ?>>Date added (oldest)</option>
                            <option value="most-popular" <?php if($AL_User->getUser()->default_sort == "most-popular") { print('selected'); } ?>>Most Popular</option>
                        </select>
                    
                    <?php
                    }
                    else {
                    ?>
                    
                        <select name="default_sort" placeholder="Choose the default video sorting method...">
                            <option value="newest" <?php if($_POST["default_sort"] == "newest") { print('selected'); } ?>>Date added (newest)</option>
                            <option value="oldest" <?php if($_POST["default_sort"] == "oldest") { print('selected'); } ?>>Date added (oldest)</option>
                            <option value="most-popular" <?php if($_POST["default_sort"] == "most-popular") { print('selected'); } ?>>Most Popular</option>
                        </select>
                    
                    <?php
                    }
                    ?>
                    <label>Video Display Sort</label>
                    <div class="clear"></div>
                    
                    <div class="file-upload-wrapper">
                        <canvas id="canvas" width="160px" height="160px"></canvas>
                        <?php
                        if($SocialProfile->picture != "") {
                        ?>
                            
                            <img src="<?php print($SocialProfile->picture); ?>" />
                                
                        <?php
                        }
                        else {
                        ?>
                        
                            <img src="<?php print(get_image("profile/AL_Profile_Grey.png")); ?>" />
                            
                        <?php
                        }
                        ?>
                        
                        <div class="file-upload">
                            <span class="button">Choose new image</span>
                            <input type="file" name="profile_image" class="upload" />
                        </div>
                    </div>
                    <label>Profile Image</label>
                    <div class="clear"></div>
                    
                    <div class="submit-wrapper">
                        <?php
                        if($ForceEdit) {
                        ?>
                        
                            <input class="button" type="submit" value="Save Changes" disabled="true" />
                        
                        <?php
                        }
                        else {
                        ?>
                        
                            <input class="button" type="submit" value="Create Profile" disabled="true" />
                        
                        <?php
                        }
                        ?>
                        <div class="clear"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php get_footer(); ?>
