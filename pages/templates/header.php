<?php include_once("html-head.php"); ?>

    <body>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            
            ga('create', 'UA-51189997-7', 'auto');
            ga('send', 'pageview');
        </script>
        <div id="youtube-upload-popup">
        </div>
        <div id="youtube-upload-content">
            <h2>Enter Youtube URL</h2>
            <input id="popup-youtube-url" type="text" name="youtube_url" value="" />
            <div class="button" id="select-youtube">Select</div>
        </div>
        <div id="share-popup">
        </div>
        <div id="share-content">
            <h2>Share video with</h2>
            <div id="share-with">
                <a onclick="window.open('http://www.facebook.com/sharer.php?u=<?php print(get_current_url()); ?>', 'newwindow', 'width=500, height=250'); return false;" href="http://www.facebook.com/sharer.php?u=<?php print(get_current_url()); ?>" target="_blank"><img src="<?php print(get_image("social/AL_FB_orange.png")); ?>" /></a>
                <a onclick="window.open('http://twitter.com/share?url=<?php print(get_current_url()); ?>', 'newwindow', 'width=500, height=250'); return false;" href="http://twitter.com/share?url=<?php print(get_current_url()); ?>" target="_blank"><img src="<?php print(get_image("social/AL_Twitter_orange.png")); ?>" /></a>
                <a onclick="window.open('https://plus.google.com/share?url=<?php print(get_current_url()); ?>', 'newwindow', 'width=500, height=250'); return false;" href="https://plus.google.com/share?url=<?php print(get_current_url()); ?>" target="_blank"><img src="<?php print(get_image("social/AL_Gplus_orange.png")); ?>" /></a>
            </div>
        </div>
        <?php if((!is_logged_in()) && (!isset($_GET["platform"]))) { ?>
            <div id="login-popup">
            </div>
            <div id="login-content">
                <h2>Sign in with</h2>
                <div id="signin-with">
                    <a href="<?php print(get_facebook_login_url()); ?>"><img id="facebook-login" src="<?php print(get_image("social/AL_FB_orange.png")); ?>" /></a>
                    <!-- <a href="<?php //print(get_twitter_login_url()); ?>"><img id="twitter-login" src="<?php print(get_image("social/AL_Twitter_orange.png")); ?>" /></a> -->
                    <a href="<?php print(get_google_login_url()); ?>"><img id="google-login" src="<?php print(get_image("social/AL_Gplus_orange.png")); ?>" /></a>
                    <a href="<?php print(get_google_login_url()); ?>"><img id="youtube-login" src="<?php print(get_image("social/AL_youtube_orange.png")); ?>" /></a>
                </div>
            </div>
        <?php } ?>
        <div id="header">
            <div class="container">
                <div class="col-3 col-m-6">
                    <a href="<?php print(get_home()); ?>">
                        <!-- <img id="logo" src="<?php print(get_image("header_logo.png")); ?>" alt="Alvarez Live" /> -->
                        <img id="logo-beta" src="<?php print(get_image("header_logo_beta.png")); ?>" alt="Alvarez Live Beta" />
                    </a>
                </div>
                <div class="col-6 col-hide col-right col-m-show">
                    <i id="menu-button" class="fa fa-bars" aria-hidden="true"></i>
                </div>
                <div class="col-9 col-right col-m-12 col-m-center">
                    <div class="header-navigation">
                        <?php
                        if(admin_allow()) {
                        ?>
                        
                            <a href="<?php print(get_link("admin")); ?>"><div class="button">Admin</div></a>
                        
                        <?php
                        }
                        
                        if(is_logged_in()) {
                            if(has_profile()) {
                                ?>
                                
                                <a href="<?php print(get_link("favorites")); ?>"><div class="button">Favorites</div></a>
                                <a href="<?php print(get_link("upload")); ?>"><div class="button">Upload</div></a>
                                <a href="<?php print(get_logout_url()); ?>"><div class="button">Logout</div></a>
                                <a href="<?php print(get_link("user/" . $_SESSION["user_profile"]->username)); ?>">
                                    <div id="header-profile"><?php print($_SESSION["user_profile"]->first_name . " " . $_SESSION["user_profile"]->last_name); ?></div>
                                    <img id="header-picture" src="<?php print($_SESSION["user_profile"]->profile_picture); ?>" />
                                </a>
                                
                            <?php
                            }
                            else {
                            ?>
                            
                                <a href="<?php print(get_link("create-profile")); ?>"><div class="button">Create Profile</div></a>
                                <a href="<?php print(get_logout_url()); ?>"><div class="button">Logout</div></a>
                            
                            <?php
                            }
                        }
                        else {
                        ?>
                            <div id="login-button" class="button">Signup / Login</div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php get_sidebar(); ?>
        
        <div id="main">
