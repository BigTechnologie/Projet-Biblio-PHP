<?php 

require_once '../../helper/session.php';
require_once '../../helper/response.php';

if(!has_user_connect()) {
    $_SESSION[REDIRECT_URL] = $_SERVER['REQUEST_URI'];
    redirect('/bibliotheque/connexion.php');
}