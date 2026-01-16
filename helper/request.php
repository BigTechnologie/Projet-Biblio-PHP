<?php 

function request($key, $default = null)
{
    return isset($_POST[$key]) && !empty($_POST[$key]) ? $_POST[$key] : $default;
}

function query($key, $default = null)
{
    if(array_key_exists($key, $_GET) && !empty($_GET[$key])) {
        return $_GET[$key];
    }

    return $default;
}


