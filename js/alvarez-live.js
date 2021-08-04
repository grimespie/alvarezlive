jQuery(document).ready(function() {
    
    // Video sort
    jQuery("#sort-videos").change(function() {
        var sort = jQuery(this).val();
        
        window.location.href = "?sort=" + sort;
    });
    
    // Login popup
    function position_login() {
        var window_width  = jQuery(window).width();
        var window_height = jQuery(window).height();
        var popup_width   = jQuery("#login-content").width();
        var popup_height  = jQuery("#login-content").height();
        
        var top  = (window_height / 2) - (popup_height / 2);
        var left = (window_width / 2) - (popup_width / 2);
        
        jQuery("#login-content").css("top", top);
        jQuery("#login-content").css("left", left);
    }
    
    position_login();
    
    jQuery(window).resize(function() {
        position_login();
    });
    
    jQuery("#login-button").on("click", function() {
        jQuery("#login-popup").show();
        jQuery("#login-content").show();
    });
    
    jQuery("#home-login-button").on("click", function(event) {
        event.preventDefault();
        
        jQuery("#login-popup").show();
        jQuery("#login-content").show();
    });
    
    jQuery("#home-upload-button").on("click", function(event) {
        event.preventDefault();
        
        jQuery("#login-popup").show();
        jQuery("#login-content").show();
    });
    
    jQuery("#login-popup").on("click", function() {
        jQuery("#login-popup").hide();
        jQuery("#login-content").hide();
    });
    
    
    
    
    // Share popup
    function position_share() {
        var window_width  = jQuery(window).width();
        var window_height = jQuery(window).height();
        var popup_width   = jQuery("#share-content").width();
        var popup_height  = jQuery("#share-content").height();
        
        var top  = (window_height / 2) - (popup_height / 2);
        var left = (window_width / 2) - (popup_width / 2);
        
        jQuery("#share-content").css("top", top);
        jQuery("#share-content").css("left", left);
    }
    
    position_share();
    
    jQuery(window).resize(function() {
        position_share();
    });
    
    jQuery("#share-video").on("click", function() {
        jQuery("#share-popup").show();
        jQuery("#share-content").show();
    });
    
    jQuery("#share-popup").on("click", function() {
        jQuery("#share-popup").hide();
        jQuery("#share-content").hide();
    });
    
    

    jQuery(".video iframe, .video video").each(function() {
        jQuery(this).attr('data-aspectRatio', jQuery(this).height() / jQuery(this).width());
    });
        
    function video_resize() {
        jQuery(".video").each(function() {
            jQuery(this).children("iframe").hide();
            jQuery(this).children("video").hide();
            
            var newWidth = jQuery(this).width();
            
            jQuery(this).children("iframe").width(newWidth);
            jQuery(this).children("iframe").height(newWidth * jQuery(this).children("iframe").attr('data-aspectRatio'));
            
            jQuery(this).children("video").width(newWidth);
            jQuery(this).children("video").height(newWidth * jQuery(this).children("video").attr('data-aspectRatio'));
            
            jQuery(this).children("iframe").show();
            jQuery(this).children("video").show();
        });
    }
        
    setTimeout(function() {
        video_resize();
    }, 100);
    
    jQuery(window).resize(function() {
        video_resize();
    });
    
    
    // Check username exists
    var checkusername;
    
    function username_lookup() {
        if(jQuery("#edit-profile-action").length > 0) {
            if(jQuery("input#username").val() == jQuery("input#username").data("current")) {
                jQuery(".username-wrapper .fa-spinner").hide();
                jQuery(".username-wrapper .fa-check").show();
                jQuery(".username-wrapper .fa-times").hide();
                jQuery('input[type="submit"]').prop('disabled', false);
                return;
            }
        }
        
        jQuery.post(jQuery("input#username").data("usernamecheck"), {username: jQuery("input#username").val(), nonce: jQuery("input#nonce").val()}, function(exists) {
            if((exists) || (jQuery("input#username").val() == "")) {
                jQuery(".username-wrapper .fa-spinner").hide();
                jQuery(".username-wrapper .fa-check").hide();
                jQuery(".username-wrapper .fa-times").show();
                jQuery('input[type="submit"]').prop('disabled', true);
            }
            else {
                jQuery(".username-wrapper .fa-spinner").hide();
                jQuery(".username-wrapper .fa-check").show();
                jQuery(".username-wrapper .fa-times").hide();
                jQuery('input[type="submit"]').prop('disabled', false);
            }
        });
    }
    
    jQuery("input#username").keyup(function() {
        jQuery(".username-wrapper .fa-check").hide();
        jQuery(".username-wrapper .fa-times").hide();
        jQuery(".username-wrapper .fa-spinner").show();
        
        clearTimeout(checkusername);
        
        checkusername = setTimeout(function() {
            username_lookup();
        }, 1000);
    });
    
    if(jQuery("#page-create-profile").length > 0) {
        if(!(jQuery("#edit-profile-action").length > 0)) {
            username_lookup();
        }
        else {
            jQuery('input[type="submit"]').prop('disabled', false);
        }
    }
    
    
    
    // Check email exists
    var checkemail;
    
    function email_lookup() {
        if(jQuery("#edit-profile-action").length > 0) {
            if(jQuery("input#email").val() == jQuery("input#email").data("current")) {
                jQuery(".email-wrapper .fa-spinner").hide();
                jQuery(".email-wrapper .fa-check").show();
                jQuery(".email-wrapper .fa-times").hide();
                jQuery('input[type="submit"]').prop('disabled', false);
                return;
            }
        }
        
        jQuery.post(jQuery("input#email").data("emailcheck"), {email: jQuery("input#email").val(), nonce: jQuery("input#nonce").val()}, function(exists) {
            if((exists) || (jQuery("input#email").val() == "") || (jQuery("input#email").val().indexOf("@") === -1)) {
                jQuery(".email-wrapper .fa-spinner").hide();
                jQuery(".email-wrapper .fa-check").hide();
                jQuery(".email-wrapper .fa-times").show();
                jQuery('input[type="submit"]').prop('disabled', true);
            }
            else {
                jQuery(".email-wrapper .fa-spinner").hide();
                jQuery(".email-wrapper .fa-check").show();
                jQuery(".email-wrapper .fa-times").hide();
                jQuery('input[type="submit"]').prop('disabled', false);
            }
        });
    }
    
    jQuery("input#email").keyup(function() {
        jQuery(".email-wrapper .fa-check").hide();
        jQuery(".email-wrapper .fa-times").hide();
        jQuery(".email-wrapper .fa-spinner").show();
        
        clearTimeout(checkemail);
        
        checkemail = setTimeout(function() {
            email_lookup();
        }, 1000);
    });
    
    if(jQuery("#page-create-profile").length > 0) {
        if(!(jQuery("#edit-profile-action").length > 0)) {
            email_lookup();
        }
    }
    
    
    
    jQuery(".ajax-like").click(function() {
        jQuery.post(jQuery(this).data("ajaxcall"), {nonce: jQuery(this).data("nonce"), user_id: jQuery(this).data("userid"), video_id: jQuery(this).data("videoid")}, function(yes) {
            if(yes) {
                jQuery(".like-no").hide();
                jQuery(".like-yes").show();
            }
            else {
                jQuery(".like-no").show();
                jQuery(".like-yes").hide();
            }
        });
    });
    
    
    jQuery(".ajax-favourite").click(function() {
        jQuery.post(jQuery(this).data("ajaxcall"), {nonce: jQuery(this).data("nonce"), user_id: jQuery(this).data("userid"), video_id: jQuery(this).data("videoid")}, function(yes) {
            if(yes) {
                jQuery(".favourite-no").hide();
                jQuery(".favourite-yes").show();
            }
            else {
                jQuery(".favourite-no").show();
                jQuery(".favourite-yes").hide();
            }
        });
    });
    
    function get_video_comments() {
        jQuery.post(jQuery("#video-comments").data("ajaxcall"), {nonce: jQuery("#video-comments").data("nonce"), video_id: jQuery("#video-comments").data("videoid")}, function(comments) {
            jQuery("#video-comments").html(comments);
        });
    }
    
    if(jQuery("#video-comments").length > 0) {
        if(jQuery("#video-comments").data("ajaxcall").length > 0) {
            get_video_comments();
            
            jQuery("#page-video").on("submit", ".new-comment", function(event) {
                event.preventDefault();
                
                var form_id = jQuery(this).attr("id");
                
                var replyto_id = "";
                
                if(jQuery(this).find('input[name="reply_to"]').length > 0) {
                    replyto_id = jQuery(this).find('input[name="reply_to"]').val();
                }

                jQuery.post(jQuery("#" + form_id).data("ajaxcall"), {nonce: jQuery("#" + form_id).data("nonce"), reply_to: replyto_id, user_id: jQuery("#" + form_id).data("userid"), video_id: jQuery("#" + form_id).data("videoid"), comment: jQuery("#" + form_id).children("textarea").val()}, function(ok) {
                    jQuery("#" + form_id).children("textarea").val("");
                    
                    if(jQuery("#" + form_id).hasClass("reply-to")) {
                        jQuery("#" + form_id).hide();
                    }

console.log(ok);

                    get_video_comments();
                });
            });
        }
    }
    
    function increment_view_count() {
        jQuery.post(jQuery("#page-video").data("ajaxcall"), {nonce: jQuery("#page-video").data("nonce"), video_id: jQuery("#page-video").data("videoid")}, function(ok) {
        });
    }
    
    if(jQuery("#page-video").length > 0) {
        setTimeout(function() {
            increment_view_count();
        }, 10000);
    }
    
    /*
    function position_video_tiles() {
        jQuery(".video").each(function() {
            var height = jQuery(this).height();
            
            jQuery(this).find(".video-tile").css("top", height + "px");
        });
    }
    
    jQuery("#videos-full-width").imagesLoaded(function() {
        position_video_tiles();
        
        jQuery(window).resize(function() {
            position_video_tiles();
        });
    });
    */
    
    jQuery(".video").hover(function() {
        if(jQuery(window).width() > 1000) {
            jQuery(this).find(".video-tile-contents").animate({"opacity" : "1"}, 200);
        }
    }, function() {
        if(jQuery(window).width() > 1000) {
            jQuery(this).find(".video-tile-contents").animate({"opacity" : "0"}, 200);
        }
    });
    
    jQuery("#video-comments").on("click", ".comment-action", function() {
        if(jQuery(this).parent().find("form").css("display") == "none") {
            jQuery(this).parent().find("form").show();
        }
        else {
            jQuery(this).parent().find("form").hide();
        }
    });
    
    jQuery("#video-comments").on("click", ".cancel-button", function() {
        jQuery(this).parent().hide();
    });
    
    if(jQuery("#page-create-profile").length > 0) {
        var default_profile_pic = "";
        
        jQuery('.file-upload input[type="file"]').change(function(e) {
            default_profile_pic = jQuery(".file-upload-wrapper img").attr("src");
            
            var preview = document.querySelector('.file-upload-wrapper img');
            var file    = document.querySelector('.file-upload input[type=file]').files[0];
            var reader  = new FileReader();
            
            reader.onloadend = function () {
                preview.src = reader.result;
            }
            
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = default_profile_pic;
            }
        });
    }
    
    if(jQuery("#page-upload").length > 0) {
        jQuery(".file-upload.video input").change(function(e) {
            var file = document.querySelector('.file-upload.video input[type=file]').files[0];
            
            if(file) {
                jQuery(".upload-box.import-youtube").addClass("upload-disable");
                jQuery(".upload-box.video-src").addClass("upload-check");
            }
        })
        
        jQuery(".file-upload.thumbnail input").change(function(e) {
            var file = document.querySelector('.file-upload.thumbnail input[type=file]').files[0];
            
            if(file) {
                jQuery(".upload-box.thumbnail").addClass("upload-check");
            }
        })
    }
    
    jQuery(".upload-box.import-youtube").click(function() {
        if(!jQuery(this).hasClass("upload-disable")) {
            jQuery("#youtube-upload-popup").show();
            jQuery("#youtube-upload-content").show();
        }
    })
    
    jQuery("#youtube-upload-popup").click(function() {
        jQuery("#youtube-upload-popup").hide();
        jQuery("#youtube-upload-content").hide();
    })
    
    // Youtube popup
    function position_youtube() {
        var window_width  = jQuery(window).width();
        var window_height = jQuery(window).height();
        var popup_width   = jQuery("#youtube-upload-content").width();
        var popup_height  = jQuery("#youtube-upload-content").height();
        
        var top  = (window_height / 2) - (popup_height / 2);
        var left = (window_width / 2) - (popup_width / 2);
        
        jQuery("#youtube-upload-content").css("top", top);
        jQuery("#youtube-upload-content").css("left", left);
    }
    
    position_youtube();
    
    jQuery(window).resize(function() {
        position_youtube();
    });
    
    jQuery("#select-youtube").on("click", function() {
        if(jQuery("#popup-youtube-url").val() != "") {
            jQuery("#hidden-youtube-submit").attr("value", jQuery("#popup-youtube-url").val());
            jQuery("#youtube-upload-popup").hide();
            jQuery("#youtube-upload-content").hide();
            jQuery(".upload-box.video-src").addClass("upload-disable");
            jQuery(".upload-box.import-youtube").addClass("upload-check");
        }
    })
    
    jQuery("#choose-guitar").change(function() {
        if(jQuery(this).find("option:selected").val() == "custom") {
            jQuery("#custom-guitar").show();
        }
        else {
            jQuery("#custom-guitar").hide();
        }
    });
    
    if(jQuery("#page-about").length > 0) {
        function set_dt_size() {
            var height = jQuery(window).height() - jQuery("#header").height();
            
            jQuery(".full-page").each(function() {
                jQuery(this).css("height", height + "px");
            });
        }
        
        set_dt_size();
        jQuery(window).resize(function() { set_dt_size(); });
        
        jQuery(".slider-thumb").click(function() {
            var slide = jQuery(this).data("slide");
            
            jQuery(".slider-thumb").removeClass("active");
            jQuery(this).addClass("active");
            
            jQuery(".slide").removeClass("active");
            jQuery("#" + slide).addClass("active");
        });
    }
    
    if(jQuery(".videos-full-width").length > 0) {
        jQuery(".videos-full-width").imagesLoaded(function() {
            function resize_video() {
                var video_width = 0;
                
                jQuery(".videos-full-width .video").each(function() {
                    if(video_width == 0) {
                        video_width = jQuery(this).width();
                    }
                    
                    var width  = video_width;
                    var ratio  = 1.77777777778;
                    var height = width / ratio;
                    
                    jQuery(this).css("height", height + "px");
                    jQuery(this).find(".video-tile").css("height", (height + 5) + "px");
                    
                    if(jQuery(this).hasClass("youtube")) {
                        var yt_tn_ratio  = 1.49206349206;
                        var yt_tn_height = height * yt_tn_ratio;
                        var yt_tn_top    = ((yt_tn_height - height) / 2) * -1;
                        
                        jQuery(this).find(".video-thumbnail").css("height", yt_tn_height + "px");
                        jQuery(this).find(".video-thumbnail").css("top", yt_tn_top + "px");
                    }
                    else {
                        var tn_height = jQuery(this).find(".video-thumbnail").height();
                        var tn_width  = jQuery(this).find(".video-thumbnail").width();
                        var tn_ratio  = tn_width / tn_height;
                        
                        var new_tn_height = height + 40;
                        var new_tn_width  = new_tn_height * tn_ratio;
                        
                        if(new_tn_width <= width) {
                            new_tn_width  = width + 40;
                            new_tn_height = new_tn_width / tn_ratio;
                        }
                        
                        var tn_top  = ((new_tn_height - height) / 2) * -1;
                        var tn_left = ((new_tn_width - width) / 2) * -1;
                        
                        jQuery(this).find(".video-thumbnail").css("height", new_tn_height + "px");
                        jQuery(this).find(".video-thumbnail").css("top", tn_top + "px");
                        jQuery(this).find(".video-thumbnail").css("left", tn_left + "px");
                    }
                });
            }
            
            resize_video();
            jQuery(window).resize(function() { resize_video(); });
        });
    }
    
    jQuery(".about-down-arrow").click(function(event) {
        event.preventDefault();
        
        var link = jQuery(this).find("a").attr("href");
        
        var scroll_to = jQuery(link).offset().top - jQuery("#header").height();
        
        jQuery("html, body").animate({scrollTop:scroll_to}, 750);
    });
    
    function resize_header() {
        if(jQuery(window).width() <= 800) {
            var header_height = jQuery("#header").height();
            
            jQuery("#main").css("margin-top", header_height + "px");
        }
    }
    
    resize_header();
    
    jQuery(window).resize(function() { resize_header(); });
    
    
    jQuery("#menu-button").click(function() {
        if(jQuery(".header-navigation").css("display") == "none") {
            jQuery("#header").css("height", "initial");
            jQuery(".header-navigation").show();
        }
        else {
            jQuery("#header").css("height", "80px");
            jQuery(".header-navigation").hide();
        }
    });
    
    
    if(jQuery(".bio").length > 0) {
        if(jQuery(".bio").height() > 150) {
            jQuery(".bio").css("max-height", "150px");
            jQuery(".bio").css("overflow-y", "auto");
        }
    }
    
});
