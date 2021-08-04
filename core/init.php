<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);


if(preg_match("/^www\./", $_SERVER["HTTP_HOST"])) {
    $Url = "https://alvarezlive.com" . $_SERVER["REQUEST_URI"];
    
    header("Location: " . $Url);
    exit();
}

// Start Session
session_start();

// APIs
include_once("api/api_controller.php");

// Core Modules
include_once("core/config.php");
include_once("core/rewrite.php");
include_once("core/database.php");

// Tables
include_once("core/tables/admin.php");
include_once("core/tables/comments.php");
include_once("core/tables/images.php");
include_once("core/tables/users.php");
include_once("core/tables/users_accounts.php");
include_once("core/tables/users_interactions.php");
include_once("core/tables/videos.php");

// Data Handling Modules
include_once("core/dhm/dhmd_user.php");
include_once("core/dhm/dhmd_user_account.php");
include_once("core/dhm/dhmd_video.php");

include_once("core/dhm/dhmi_comment.php");
include_once("core/dhm/dhmi_image.php");
include_once("core/dhm/dhmi_user.php");
include_once("core/dhm/dhmi_user_account.php");
include_once("core/dhm/dhmi_user_interaction.php");
include_once("core/dhm/dhmi_video.php");

include_once("core/dhm/dhmr_admin.php");
include_once("core/dhm/dhmr_comments.php");
include_once("core/dhm/dhmr_image.php");
include_once("core/dhm/dhmr_user.php");
include_once("core/dhm/dhmr_user_account.php");
include_once("core/dhm/dhmr_user_comment_count.php");
include_once("core/dhm/dhmr_user_interactions.php");
include_once("core/dhm/dhmr_video.php");

include_once("core/dhm/dhmu_admin.php");
include_once("core/dhm/dhmu_user.php");
include_once("core/dhm/dhmu_user_interaction.php");
include_once("core/dhm/dhmu_video.php");

// Helper classes
include_once("core/classes/admin.php");
include_once("core/classes/ajax.php");
include_once("core/classes/meta.php");
include_once("core/classes/user.php");
include_once("core/classes/video.php");

// Setup Globals
$API_Controller = new API_Controller();

?>
