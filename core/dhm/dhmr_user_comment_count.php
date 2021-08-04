<?php

function DHMR_User_Comment_Count($user_id) {
    global $_TABLE_VIDEOS, $_TABLE_COMMENTS;
    
    $Connection = get_db_connection();
    
    if(!$Connection) {
        return(false);
    }
    
    $Sql = 'select count(*) as total_comments from ' . $_TABLE_VIDEOS . ' v, ' . $_TABLE_COMMENTS . ' c where v.user_id = ' . $user_id . ' and c.video_id = v.video_id';
    
    $Result = mysqli_query($Connection, $Sql);
    
    if(mysqli_error($Connection)) {
        return(false);
    }
    
    close_db_connection($Connection);
    
    if(!$Result) {
        return(false);
    }
    
    return(prepare_results($Result, array("total_comments" => 0)));
    
    return($Result);
}

?>
