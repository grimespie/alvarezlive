<?php get_header(); ?>

    <div id="page-about">
        <div class="full-page" id="about-section-1">
            <div class="dt">
                <div class="dc">
                    <div class="container">
                        <div class="col-12 col-center">
                            <img class="alvarez-logo" src="<?php print(get_image("welcome_logo.png")); ?>" alt="Welcome to Alvarez Live" />
                            <h2>Your audience is waiting...</h2>
                            <h3>Did you know your Alvarez Guitar comes with a stage of its own?</h3>
                            <p>If you love making music videos that showcase your performance, guitar lessons or gear reviews, Alvarez Live can help you reach a wide base of guitar and music enthusiasts, thousands of people strong.</p>
                        </div>
                    </div>
                    <div class="about-down-arrow">
                        <a href="#about-section-2"><img src="<?php print(get_image("AL_DownArrow.png")); ?>" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="full-page" id="about-section-2">
            <div class="dt">
                <div class="dc">
                    <div class="container">
                        <div class="col-12 col-center">
                            <h2>Your content is powerful:</h2>
                            <h3>Alvarez Live helps it reach its full potential.</h3>
                            <div id="about-slider">
                                <div class="slide active" id="slide-1">
                                    <div class="slide-overlay">
                                        <div class="dt">
                                            <div class="dc">
                                                <img src="<?php print(get_image("AL_SignUp.png")); ?>" />
                                                <h4>Sign Up</h4>
                                                <p>Use your existing social accounts to join the Alvarez Live community.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <img src="<?php print(get_image("signup.png")); ?>" />
                                </div>
                                <div class="slide" id="slide-2">
                                    <div class="slide-overlay">
                                        <div class="dt">
                                            <div class="dc">
                                                <img src="<?php print(get_image("AL_Profile.png")); ?>" />
                                                <h4>Create a Profile</h4>
                                                <p>Enter your information into the fields to build your Alvarez Live profile page.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <img src="<?php print(get_image("createprofile.png")); ?>" />
                                </div>
                                <div class="slide" id="slide-3">
                                    <div class="slide-overlay">
                                        <div class="dt">
                                            <div class="dc">
                                                <img src="<?php print(get_image("AL_Upload.png")); ?>" />
                                                <h4>Upload Videos</h4>
                                                <p>Upload videos featuring your Alvarez Guitar or link to your youtube videos.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <img src="<?php print(get_image("uploadvideo.png")); ?>" />
                                </div>
                                <div class="slide" id="slide-4">
                                    <div class="slide-overlay">
                                        <div class="dt">
                                            <div class="dc">
                                                <img src="<?php print(get_image("AL_Share.png")); ?>" />
                                                <h4>Share</h4>
                                                <p>Share your video with friends and the Alvarez Live Community.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <img src="<?php print(get_image("share.png")); ?>" />
                                </div>
                            </div>
                            <div id="about-slider-thumbs">
                                <div class="slider-thumb active" data-slide="slide-1">1. Sign Up</div>
                                <div class="slider-thumb" data-slide="slide-2">2. Create a Profile</div>
                                <div class="slider-thumb" data-slide="slide-3">3. Upload Videos</div>
                                <div class="slider-thumb" data-slide="slide-4">4. Share</div>
                                <div class="clear"></div>
                            </div>
                            <p><strong>Alvarez is a leading guitar brand with a world-wide following.</strong> Every time you upload a video it has the chance to be shared by our community, and much more. Alvarez isn't only dedicated to making fine guitars. We are dedicated to helping the players that choose us and make great music reach as many new fans as possible.</p>
                        </div>
                    </div>
                    <div class="about-down-arrow">
                        <a href="#about-section-3"><img src="<?php print(get_image("AL_DownArrow.png")); ?>" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="full-page" id="about-section-3">
            <div class="dt">
                <div class="dc">
                    <div class="container">
                        <div class="col-12 col-center">
                            <h2>Artist promotion.</h2>
                            <h3>Every month.</h3>
                            <p>Uploading to Alvarez Live gives you the chance to become the Alvarez Live Artist of the Month, where your video will be shared on our social media feeds and blasted out to thousands of people via our monthly newsletter. Your video will also be presented to our friends online with promotional sites of their own, like music magazines, video aggregates and more.</p>
                            <?php
                            $AL_Admin = new AL_Admin();
                            ?>
                            <a href="<?php print($AL_Admin->getFeaturedArtist()->getProfileLink()); ?>" class="button">View our current featured artist</a>
                        </div>
                    </div>
                    <div class="about-down-arrow">
                        <a href="#about-section-4"><img src="<?php print(get_image("AL_DownArrow.png")); ?>" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="full-page" id="about-section-4">
            <div class="dt">
                <div class="dc">
                    <div class="container">
                        <div class="col-12 col-center">
                            <img class="alvarez-logo" src="<?php print(get_image("welcome_logo.png")); ?>" alt="Welcome to Alvarez Live" />
                            <h2>Step closer to success.</h2>
                            <?php
                            $CreateProfileID = "";
                            $UploadVideoID   = "";
                            
                            if(!is_logged_in()) {
                                $CreateProfileID = "home-login-button";
                                $UploadVideoID   = "home-upload-button";
                                ?>
                                
                                <a class="button" id="<?php print($CreateProfileID); ?>" href="">Create a profile and upload your videos</a>
                                
                            <?php
                            }
                            else {
                            ?>
                            
                                <a class="button" href="<?php print(get_link("upload")); ?>">Upload your videos</a>
                            
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>