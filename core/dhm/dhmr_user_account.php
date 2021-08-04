<?php

function DHMR_User_Account($type="", $account_id="") {
    global $_TABLE_USERS_ACCOUNTS, $_TABLE_USERS_ACCOUNTS_FIELDS;
    
    $data = 'account_type="' . $type . '" and account_user_id="' . $account_id . '"';
    
    $Result = table_read($_TABLE_USERS_ACCOUNTS, $data);
    
    if(!$Result) {
        return(false);
    }
    
    return(prepare_results($Result, $_TABLE_USERS_ACCOUNTS_FIELDS));
}

?>
