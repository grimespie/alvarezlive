<?php get_header(); ?>

    <div id="page-home">
        
        <div class="title-wrapper-h1">
            <div class="container">
                <div class="col-12 col-center">
                    <h1>Welcome to <span>Alvarez Live</span><img src="<?php print(get_image("welcome_logo.png")); ?>" alt="Welcome to Alvarez Live" /></h1>
                    
                    <div class="home-links">
                        
                        <?php
                        $CreateProfileID = "";
                        $UploadVideoID   = "";
                        
                        if(!is_logged_in()) {
                            $CreateProfileID = "home-login-button";
                            $UploadVideoID   = "home-upload-button";
                            ?>
                            
                            <span class="home-link"><a id="<?php print($CreateProfileID); ?>" href="">Create a Profile</a></span>
                            
                        <?php
                        }
                        else {
                        ?>
                        
                            <span class="home-link"><a href="<?php print(get_link("user/" . $_SESSION["user_profile"]->username)); ?>">Your Profile</a></span>
                        
                        <?php
                        }
                        ?>
                        
                        <span class="home-link"><a id="<?php print($UploadVideoID); ?>" href="<?php print(get_link("upload")); ?>">Upload Videos</a></span>
                        <span class="home-link"><a href="">Broadcast & Win!</a></span>
                    </div>
                    
                    <a href="<?php print(get_link("about")); ?>" class="button">Find out more</a>
                </div>
            </div>
        </div>
        
        <div id="home-top-section">
            <div class="videos-full-width">
                <?php
                $AL_Admin = new AL_Admin();
                
                display_video(new AL_Video($AL_Admin->getFeaturedSong()->getVideoID()), "", "Featured Song");    // Featured Song
                
                $FeaturedUserVideos = DHMR_Video('user_id = ' . $AL_Admin->getFeaturedArtist()->getUserID() . ' and status != ' . VID_STAT_NEW . ' and (youtube_ind = ' . YT_IND_YES . ' or (youtube_ind = ' . YT_IND_NO . ' and vimeo_ready = ' . VIMEO_READY_YES . ')) order by upload_date desc');
                
                display_video(new AL_Video($FeaturedUserVideos[0]->video_id), $AL_Admin->getFeaturedArtist()->getProfileLink(), "Featured Artist", $AL_Admin->getFeaturedArtist()->getFullName());  // Featured Artist
                
                display_video(new AL_Video($AL_Admin->getFeaturedLesson()->getVideoID()), "", "Featured Lesson");  // Featured Lesson
                ?>
                <div class="clear"></div>
            </div>
        </div>
        
        <div id="home-latest-videos">
            <div class="container">
                <div class="col-12 col-center">
                    <h2>Latest Videos</h2>
                </div>
            </div>
            
            <?php display_videos("", "", true); ?>
        </div>

    </div>

<?php get_footer(); ?>