<?php

class AL_Ajax {
    
    private $call = "";
    
    public function __construct($call) {
        $this->call = $call;
    }
    
    public function init() {
        $call_file = dirname(dirname(__DIR__)) . "/core/ajax/" . $this->call . ".php";
        
        if(!file_exists($call_file)) {
            display_404();
            exit();
        }
        
        if(!isset($_POST["nonce"])) {
            display_404();
            exit();
        }
        
        if(verify_nonce(get_account_id(), $_POST["nonce"])) {
            include_once($call_file);
        }
    }
    
}

?>