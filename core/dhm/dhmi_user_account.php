<?php

function DHMI_User_Account($data=array()) {
    global $_TABLE_USERS_ACCOUNTS, $_TABLE_USERS_ACCOUNTS_FIELDS;
    
    $input_data = merge_input($data, $_TABLE_USERS_ACCOUNTS_FIELDS);
    
    unset($input_data["account_link_id"]);
    
    $Result = table_insert($_TABLE_USERS_ACCOUNTS, $input_data);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>