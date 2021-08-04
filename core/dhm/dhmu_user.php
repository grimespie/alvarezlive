<?php

function DHMU_User($data=array(), $where=array()) {
    global $_TABLE_USERS, $_TABLE_USERS_FIELDS;
    
    $Result = table_update($_TABLE_USERS, $data, $where);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>