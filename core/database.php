<?php

function get_db_connection() {
    $Connection = mysqli_connect(SQL_SERVER, SQL_USER, SQL_PASSWORD, SQL_DATABASE);
    
    if(mysqli_connect_errno()) {
        return(false);
    }
    
    return($Connection);
}

function close_db_connection($connection) {
    $Result = mysqli_close($connection);
    
    return($Result);
}

function merge_input($data, $default) {
    $Keys = array_keys($default);
    
    foreach($Keys as $Key) {
        if(array_key_exists($Key, $data)) {
            $default[$Key] = $data[$Key];
        }
    }
    
    return($default);
}

function generate_update_clause($data=array()) {
    $Keys  = array_keys($data);
    $Set = "";
    
    foreach($Keys as $Key) {
        if($Set != "") {
            $Set .= ', ' . $Key . ' = ';
        }
        else {
            $Set .= $Key . ' = ';
        }
        
        if((gettype($data[$Key]) == "boolean") || (gettype($data[$Key]) == "integer") || ($data[$Key] == "null")) {
            $Set .= $data[$Key];
        }
        else {
            $Set .= '"' . $data[$Key] . '"';
        }
    }
    
    return($Set);
}

function generate_where_clause($data=array()) {
    $Keys  = array_keys($data);
    $Where = "";
    
    foreach($Keys as $Key) {
        if($Where != "") {
            $Where .= ' and ' . $Key . ' = ';
        }
        else {
            $Where .= $Key . ' = ';
        }
        
        if((gettype($data[$Key]) == "boolean") || (gettype($data[$Key]) == "integer") || ($data[$Key] == "null")) {
            $Where .= $data[$Key];
        }
        else {
            $Where .= '"' . $data[$Key] . '"';
        }
    }
    
    return($Where);
}

function generate_data_clause($data) {
    $Keys     = array_keys($data);
    $Columns  = '';
    $Values   = '';

    foreach($Keys as $Key) {
        if($Columns != "") {
            $Columns .= ', ' . $Key;
        }
        else {
            $Columns = '(' . $Key;
        }
        
        $Value = '';
        
        if((gettype($data[$Key]) == "boolean") || (gettype($data[$Key]) == "integer") || ($data[$Key] == "null")) {
            $Value = $data[$Key];
        }
        else {
            $Value = '"' . $data[$Key] . '"';
        }
        
        if($Values != "") {
            $Values .= ', ' . $Value;
        }
        else {
            $Values = '(' . $Value;
        }
    }
    
    $Columns .= ')';
    $Values .= ')';
    
    $Sql = $Columns . ' values ' . $Values;
    
    return($Sql);
}

function clean_input($data) {
    $data_keys = array_keys($data);
    
    foreach($data_keys as $key) {
        if((gettype($data[$key]) == "boolean") || (gettype($data[$key]) == "integer") || ($data[$Key] == "null")) {
            $data[$key] = $data[$key];
        }
        else {
            $data[$key] = preg_replace("/\'/", "\'", $data[$key]);
            $data[$key] = preg_replace('/"/', '\"', $data[$key]);
        }
    }
    
    return($data);
}

function table_update($table, $data, $where) {
    $Connection = get_db_connection();
    
    $data = clean_input($data);
    
    if(!$Connection) {
        return(false);
    }
    
    $Sql = 'update ' . $table . ' set ' . generate_update_clause($data) . ' where ' . generate_where_clause($where);
    
    $Result = mysqli_query($Connection, $Sql);
    
    if(mysqli_error($Connection)) {
        return(false);
    }
    
    close_db_connection($Connection);
    
    return(true);
}

function table_insert($table, $data) {
    $Connection = get_db_connection();
    
    $data = clean_input($data);

    if(!$Connection) {
        return(false);
    }
    
    $Sql = 'insert into ' . $table . ' ' . generate_data_clause($data);

    $Result = mysqli_query($Connection, $Sql);
    
    if(mysqli_error($Connection)) {
        return(false);
    }
    
    close_db_connection($Connection);
    
    return(true);
}

function table_read($table, $data) {
    $Connection = get_db_connection();
    
    if(!$Connection) {
        return(false);
    }
    
    $Sql = 'select * from ' . $table . ' where ' . $data;
    
    $Result = mysqli_query($Connection, $Sql);
    
    if(mysqli_error($Connection)) {
        return(false);
    }
    
    close_db_connection($Connection);
    
    return($Result);
}

function table_delete($table, $data) {
    $Connection = get_db_connection();
    
    $data = clean_input($data);
    
    if(!$Connection) {
        return(false);
    }
    
    $Sql = 'delete from ' . $table . ' where ' . $data;
    
    $Result = mysqli_query($Connection, $Sql);
    
    if(mysqli_error($Connection)) {
        return(false);
    }
    
    close_db_connection($Connection);
    
    return(true);
}

function get_datetime() {
    $datetime = date("Y-m-d H:i:s");
    
    return($datetime);
}

function prepare_results($results, $table_fields) {
    $return = array();
    
    while($row = mysqli_fetch_row($results)) {
        $RowObject = new stdClass();
        $Item      = 0;

        foreach(array_keys($table_fields) as $key) {
            $RowObject->{$key} = $row[$Item];
            
            $Item++;
        }
        
        array_push($return, $RowObject);
    }
    
    return($return);
}

?>
