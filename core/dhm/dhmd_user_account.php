<?php

function DHMD_User_Account($data) {
    global $_TABLE_USERS_ACCOUNTS, $_TABLE_USERS_ACCOUNTS_FIELDS;
    
    $Result = table_delete($_TABLE_USERS_ACCOUNTS, $data);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>