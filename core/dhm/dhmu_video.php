<?php

function DHMU_Video($data=array(), $where=array()) {
    global $_TABLE_VIDEOS, $_TABLE_VIDEOS_FIELDS;
    
    $data["last_updated_date"] = get_datetime();
    
    $Result = table_update($_TABLE_VIDEOS, $data, $where);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>