<?php 

function redirect($url) 
{
    header('Location: ' .$url, true, 302);
    exit();
}

