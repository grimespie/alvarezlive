<?php

function DHMD_User($data) {
    global $_TABLE_USERS, $_TABLE_USERS_FIELDS;
    
    $Result = table_delete($_TABLE_USERS, $data);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>