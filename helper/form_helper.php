<?php 
require_once 'session.php';
function form_errors($fieldName, $errors): string 
{
    $html = "";

    if(isset($errors[$fieldName])) {
        $html = "<div class='text-danger'>";
        foreach ($errors[$fieldName] as $error) {
            $html .= sprintf("<p>%s</p>", $error);
        }
        $html .= "</div>";
    }
    return $html;
}

function csrf_token(): string 
{
    $token = bin2hex(random_bytes(35));

    $_SESSION[TOKEN] = $token;

    return $token;
}



