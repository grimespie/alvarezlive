<?php

function DHMI_User_Interaction($data=array()) {
    global $_TABLE_USERS_INTERACTIONS, $_TABLE_USERS_INTERACTIONS_FIELDS;
    
    $input_data = merge_input($data, $_TABLE_USERS_INTERACTIONS_FIELDS);
    
    $Result = table_insert($_TABLE_USERS_INTERACTIONS, $input_data);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>
