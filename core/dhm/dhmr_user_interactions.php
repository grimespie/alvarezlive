<?php

function DHMR_User_Interactions($data) {
    global $_TABLE_USERS_INTERACTIONS, $_TABLE_USERS_INTERACTIONS_FIELDS;
    
    $Result = table_read($_TABLE_USERS_INTERACTIONS, $data);
    
    if(!$Result) {
        return(false);
    }
    
    return(prepare_results($Result, $_TABLE_USERS_INTERACTIONS_FIELDS));
}

?>