<?php

function DHMU_Admin($data=array(), $where=array()) {
    global $_TABLE_ADMIN, $_TABLE_ADMIN_FIELDS;
    
    $Result = table_update($_TABLE_ADMIN, $data, $where);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>