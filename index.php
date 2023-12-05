<?php
$uri = $_SERVER['REQUEST_URI'];

switch($uri) {
    case '':
        case '/FinalProj/': 
            require __DIR__ . '/thesislist.php';
            break;
    case '/FinalProj/login': 
        require __DIR__ . './login.php';
        break;
}