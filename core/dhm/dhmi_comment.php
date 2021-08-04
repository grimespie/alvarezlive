<?php

function DHMI_Comment($data=array()) {
    global $_TABLE_COMMENTS, $_TABLE_COMMENTS_FIELDS;
    
    $input_data = merge_input($data, $_TABLE_COMMENTS_FIELDS);
    
    $input_data["comment_date"] = get_datetime();
    
    unset($input_data["comment_id"]);

    $Result = table_insert($_TABLE_COMMENTS, $input_data);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>
