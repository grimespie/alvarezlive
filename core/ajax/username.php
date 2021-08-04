<?php
if(verify_nonce(get_account_id(), $_POST["nonce"])) {
    $Return = DHMR_User('username = "' . $_POST["username"] . '"');
    
    if(count($Return) > 0) {
        print(true);
    }
    else {
        print(false);
    }
}
?>
