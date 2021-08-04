<?php

function DHMI_User($data=array()) {
    global $_TABLE_USERS, $_TABLE_USERS_FIELDS;
    
    $input_data = merge_input($data, $_TABLE_USERS_FIELDS);
    
    $input_data["registration_date"] = get_datetime();
    
    unset($input_data["user_id"]);
    
    $Result = table_insert($_TABLE_USERS, $input_data);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>
