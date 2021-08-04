<?php

function page_init() {
    $RequestedPage = get_requested_page();
    $Page          = $RequestedPage;

    if($RequestedPage == "") {
        $Page = "home";
    }
    
    if($RequestedPage == "user") {
        $Page = "profile";
    }
    
    if(admin_request($Page)) {
        $RequestedAdmin = get_requested_admin();
        
        if($RequestedAdmin == "") {
            $RequestedAdmin = "dashboard";
        }
        
        $Page = "admin/" . $RequestedAdmin;
    }

    if(($Page == "404") || (!page_exists($Page))) {
        display_404();
    }
    else {
        display_page($Page);
    }
}

function admin_request($page) {
    if(preg_match("/^admin$/", $page)) {
        return(true);
    }
    
    return(false);
}

function get_requested_page() {
    if(preg_match("/^\/\?/", $_SERVER["REQUEST_URI"])) {
        return("");
    }
    
    if(($_SERVER["REQUEST_URI"] == "") || ($_SERVER["REQUEST_URI"] == "/")) {
        return("");
    }
    
    if(preg_match("/\/(.+?)\/|\/(.+?)$/", $_SERVER["REQUEST_URI"], $Matches)) {
        if($Matches[1] != "") {
            return($Matches[1]);
        }
        else {
            return($Matches[2]);
        }
    }
    
    return("404");
}

function page_exists($page) {
    if(file_exists("pages/" . $page . ".php")) {
        return(true);
    }
    
    return(false);
}

function display_page($page) {
    include_once("pages/" . $page . ".php");
}

function display_404() {
    header("HTTP/1.0 404 Not Found");
    
    display_page("404");
}

function display_admin_error() {
    display_page("admin/error");
}

function get_requested_username() {
    if(($_SERVER["REQUEST_URI"] == "") || ($_SERVER["REQUEST_URI"] == "/")) {
        return(false);
    }
    
    if(preg_match("/\/user\/(.+?)\/|\/user\/(.+?)$/", $_SERVER["REQUEST_URI"], $Matches)) {
        if($Matches[1] != "") {
            return($Matches[1]);
        }
        else {
            return($Matches[2]);
        }
    }
    
    return(false);
}

function get_requested_video() {
    if(($_SERVER["REQUEST_URI"] == "") || ($_SERVER["REQUEST_URI"] == "/")) {
        return(false);
    }
    
    if(preg_match("/\/video\/(.+?)\/|\/video\/(.+?)$/", $_SERVER["REQUEST_URI"], $Matches)) {
        if($Matches[1] != "") {
            return((int)$Matches[1]);
        }
        else {
            return((int)$Matches[2]);
        }
    }

    return(false);
}

function get_requested_call() {
    if(($_SERVER["REQUEST_URI"] == "") || ($_SERVER["REQUEST_URI"] == "/")) {
        return(false);
    }
    
    if(preg_match("/\/ajax\/(.+?)\/|\/ajax\/(.+?)$/", $_SERVER["REQUEST_URI"], $Matches)) {
        if($Matches[1] != "") {
            return($Matches[1]);
        }
        else {
            return($Matches[2]);
        }
    }

    return(false);
}

function get_requested_admin() {
    if(($_SERVER["REQUEST_URI"] == "") || ($_SERVER["REQUEST_URI"] == "/")) {
        return(false);
    }
    
    if(preg_match("/\/admin\/(.+?)\/|\/admin\/(.+?)$/", $_SERVER["REQUEST_URI"], $Matches)) {
        if($Matches[1] != "") {
            return($Matches[1]);
        }
        else {
            return($Matches[2]);
        }
    }
    
    return(false);
}

?>
