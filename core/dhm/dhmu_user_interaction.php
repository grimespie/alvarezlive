<?php

function DHMU_User_Interaction($data=array(), $where=array()) {
    global $_TABLE_USERS_INTERACTIONS, $_TABLE_USERS_INTERACTIONS_FIELDS;
    
    $Result = table_update($_TABLE_USERS_INTERACTIONS, $data, $where);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>