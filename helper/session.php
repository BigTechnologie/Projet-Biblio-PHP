<?php

session_start();

// Clé de session utilisée pour stocker les messages flash. Les messages flash sont des messages temporaires (succès, erreur, info) affichés à l'utilisateur après une action.
const FLASH = 'message_flash';

// Clé de session utilisée pour stocker les informations de l'utilisateur connecté. Elle permet de vérifier si un utilisateur est authentifié et de récupérer ses données depuis la session.
const USER = 'user';

// Clé de session utilisée pour stocker l'URL vers laquelle l'utilisateur doit être redirigé après une action spécifique (par exemple après une connexion réussie).
const REDIRECT_URL = 'redirect_url';

// Clé utilisée pour stocker un token de sécurité dans la session. Ce token est généralement utilisé pour prévenir les attaques CSRF (Cross-Site Request Forgery).
// ou sécuriser des formulaires et actions sensibles.
const TOKEN = 'token';

// Utilisée pour stocker des messages flash dans la session de l'application web. Les messages flash sont généralement utilisés pour afficher 
// des messages temporaires  à l'utilisateur après une action, tels que des notifications de succès, d'erreur ou d'autres informations importantes.
function create_message_flash($type, $message) 
{
    $_SESSION[FLASH][$type][] = $message;
}

// Utilisée pour afficher les messages flash stockés dans la session de l'application web. 
function display_message_flash($type) 
{
    if (!isset($_SESSION[FLASH])) { 
        return; 
    }

    $flashes = $_SESSION[FLASH]; 

    if (!isset($flashes[$type])) { 
        return;
    }

    $messages = $flashes[$type];

    unset($_SESSION[FLASH][$type]); 

    // La fonction construit un bloc HTML qui affiche les messages flash dans une boîte d'alerte. 
    $html = "<div class='alert alert-" . $type . "'>";
    foreach ($messages as $message) {
        $html .= "<p class='mb-0'>" . $message . "</p>";
    }
    $html .= "</div>";

    return $html; 
}

// utilisée pour vérifier si un utilisateur est connecté en examinant les données stockées dans la session
function has_user_connect(): bool     
{
    return array_key_exists(USER, $_SESSION) && !empty($_SESSION[USER]); 
}                                                                        

// utilisée pour récupérer les données de l'utilisateur connecté à partir de la session. 
function get_user_connect(): ?array   
{
    if (!has_user_connect()) {
        return null;
    }

    // renvoie les données d'utilisateur stockées dans la session en utilisant la clé USER.
    return $_SESSION[USER]; 

}




