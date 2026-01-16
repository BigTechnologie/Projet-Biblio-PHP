<?php 

session_start();

require_once '../helper/response.php';

session_destroy();

redirect('/bibliotheque/index.php');