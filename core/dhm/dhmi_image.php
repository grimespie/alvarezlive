<?php

function DHMI_Image($data=array()) {
    global $_TABLE_IMAGES, $_TABLE_IMAGES_FIELDS;
    
    $input_data = merge_input($data, $_TABLE_IMAGES_FIELDS);
    
    $input_data["upload_date"] = get_datetime();
    
    unset($input_data["image_id"]);
    
    $Result = table_insert($_TABLE_IMAGES, $input_data);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>