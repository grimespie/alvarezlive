<?php
get_header();

if(admin_allow()) {
    
    $AL_Admin = new AL_Admin();
    ?>

    <div id="page-admin">
        <div class="container">
            <div class="col-12">
                <h1>Admin</h1>
            </div>
        </div>
        <div class="container">
            <div class="col-4">
                <h2>Featured Song</h2>
                <?php
                print('<a href="' . $AL_Admin->getFeaturedSong()->getVideoLink() . '">' . $AL_Admin->getFeaturedSong()->getVideo()->title . '</a>');
                ?>
            </div>
            <div class="col-4">
                <h2>Featured Artist</h2>
                <?php
                print('<a href="' . $AL_Admin->getFeaturedArtist()->getProfileLink() . '">' . $AL_Admin->getFeaturedArtist()->getFullName() . '</a>');
                ?>
            </div>
            <div class="col-4">
                <h2>Featured Lesson</h2>
                <?php
                print('<a href="' . $AL_Admin->getFeaturedLesson()->getVideoLink() . '">' . $AL_Admin->getFeaturedLesson()->getVideo()->title . '</a>');
                ?>
            </div>
        </div>
        <div class="container">
            <div class="col-12">
                <h2>Videos for review</h2>
                <?php
                $Videos = DHMR_Video('status = ' . VID_STAT_NEW . ' order by upload_date asc');
                
                if(count($Videos) > 0) {
                    foreach($Videos as $Video) {
                        $VideoObj = new AL_Video($Video->video_id);
                        
                        $VideoObj->init();
                        
                        print('<a href="' . $VideoObj->getVideoLink() . '">' . $VideoObj->getVideo()->title . ' by ' . $VideoObj->getUser()->getFullName() . '</a>');
                    }
                }
                else {
                    print("<p>There are no new videos for review.</p>");
                }
                ?>
            </div>
        </div>
        <!--
        <div class="container">
            <div class="col-12">
                <h2>Videos flagged by users</h2>
                <?php
                $Videos = DHMR_Video('status = ' . VID_STAT_FLAGGED . ' order by upload_date asc');
                
                if(count($Videos) > 0) {
                    foreach($Videos as $Video) {
                        $VideoObj = new AL_Video($Video->video_id);
                        
                        $VideoObj->init();
                        
                        print('<a href="' . $VideoObj->getVideoLink() . '">' . $VideoObj->getVideo()->title . ' by ' . $VideoObj->getUser()->getFullName() . '</a>');
                    }
                }
                else {
                    print("<p>There are no flagged videos.</p>");
                }
                ?>
            </div>
        </div>
        -->
    </div>

<?php
}
else {
    display_admin_error();
}

get_footer();
?>