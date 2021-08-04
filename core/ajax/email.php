<?php
if(verify_nonce(get_account_id(), $_POST["nonce"])) {
    $Return = DHMR_User('email = "' . $_POST["email"] . '"');
    
    if(count($Return) > 0) {
        print(true);
    }
    else {
        print(false);
    }
}
?>