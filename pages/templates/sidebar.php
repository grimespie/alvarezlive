<div id="sidebar">
    <div class="container">
        <div id="filters">
            <div class="sidebar-section">
                <h2>Performances</h2>
                <?php
                $AL_Admin = new AL_Admin();
                ?>
                
                <div class="sidebar-link"><a href="<?php print($AL_Admin->getFeaturedSong()->getVideoLink()); ?>">Featured Song</a></div>
                <div class="sidebar-link"><a href="<?php print($AL_Admin->getFeaturedArtist()->getProfileLink()); ?>">Featured Artist</a></div>
                <div class="sidebar-link"><a href="<?php print(get_link("performances")); ?>">All Performances</a></div>
            </div>
            <div class="sidebar-section">
                <h2>Lessons</h2>

                <div class="sidebar-link"><a href="<?php print($AL_Admin->getFeaturedLesson()->getVideoLink()); ?>">Featured Lesson</a></div>
                <div class="sidebar-link"><a href="http://alvarezguitars.com/alvarez-all-stars/" target="_blank">Alvarez All Stars</a></div>
                <div class="sidebar-link"><a href="<?php print(get_link("educational")); ?>">All Lessons</a></div>
            </div>
            <div class="sidebar-section">
                <h2>Guitars</h2>
                
                <div class="sidebar-link"><a href="<?php print(get_link("guitars")); ?>">Reviews and Stories</a></div>
            </div>
            
            <h2><a href="<?php print(get_link("about")); ?>">What is Alvarez Live?</a></h2>
        </div>
        
        <div id="footer">
            <a class="footer-link" href="http://alvarezguitars.com/privacy/" target="_blank">Privacy Policy</a>
            <img id="footer-logo" src="<?php print(get_image("footer_logo.png")); ?>" alt="Alvarez Live" />
            <div id="social-icons">
                <a href="https://www.facebook.com/AlvarezGuitars" target="_blank"><img src="<?php print(get_image("social/AL_FB_orange.png")); ?>" alt="Facebook" /></a>
                <a href="https://twitter.com/alvarez_guitars" target="_blank"><img src="<?php print(get_image("social/AL_Twitter_orange.png")); ?>" alt="Twitter" /></a>
                <a href="http://www.youtube.com/user/AlvarezGuitars1965" target="_blank"><img src="<?php print(get_image("social/AL_youtube_orange.png")); ?>" alt="YouTube" /></a>
            </div>
        </div>
    </div>
</div>
