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

        case '/FinalProj/thesisadd': 
            require __DIR__ . './thesis_add.php';
            break;
    default:
        http_response_code(404);
        require __DIR__ . '/404.php';
}