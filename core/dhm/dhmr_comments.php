<?php

function DHMR_Comments($data) {
    global $_TABLE_COMMENTS, $_TABLE_COMMENTS_FIELDS;
    
    $Result = table_read($_TABLE_COMMENTS, $data);
    
    if(!$Result) {
        return(false);
    }
    
    return(prepare_results($Result, $_TABLE_COMMENTS_FIELDS));
}

?>