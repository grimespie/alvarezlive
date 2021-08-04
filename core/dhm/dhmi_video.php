<?php

function DHMI_Video($data=array()) {
    global $_TABLE_VIDEOS, $_TABLE_VIDEOS_FIELDS;
    
    $input_data = merge_input($data, $_TABLE_VIDEOS_FIELDS);
    
    $input_data["upload_date"] = get_datetime();
    
    unset($input_data["video_id"]);
    
    $Result = table_insert($_TABLE_VIDEOS, $input_data);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>
