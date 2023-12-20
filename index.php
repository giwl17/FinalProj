<?php
$uri = parse_url( $_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/FinalProj/' =>   __DIR__ . '/thesislist.php',
    '/FinalProj/login' =>  __DIR__ . '/login.php',
    '/FinalProj/thesisadd' =>  __DIR__ . '/thesis_add.php',
    '/FinalProj/thesis' =>  __DIR__ . '/thesis.php',
    '/FinalProj/thesis_api' =>  __DIR__ . '/thesis_api.php',
    '/FinalProj/thesis_delete' =>  __DIR__ . '/thesis_delete.php',
    '/FinalProj/thesis_update' =>  __DIR__ . '/thesis_update.php',
    '/FinalProj/search' =>  __DIR__ . '/search.php',
    '/FinalProj/searchbar_db' =>  __DIR__ . '/searchbar_db.php',
    '/FinalProj/officeradd' =>  __DIR__ . '/officer_add.php',
    '/FinalProj/temporaryadd' =>  __DIR__ . '/temporary_add.php',
    '/FinalProj/teacheradd' =>  __DIR__ . '/teacher_add.php',
    '/FinalProj/studentadd' =>  __DIR__ . '/student_add.php',
    '/FinalProj/sendMailCreateAccount' =>  __DIR__ . '/sendMailCreateAccount.php',
];

function routeTo($uri, $routes) {
    if(array_key_exists($uri, $routes)) {
        require $routes[$uri];
    } else {
        abort();
    }
}

function abort($code = 404) {
    http_response_code($code);
    require '404.php';
    die();
}

routeTo($uri, $routes);

// switch($uri) {
//     case '':
//         case '/FinalProj/': 
//             require __DIR__ . '/thesislist.php';
//             break;
//     case '/FinalProj/login': 
//         require __DIR__ . './login.php';
//         break;

//         case '/FinalProj/thesisadd': 
//             require __DIR__ . './thesis_add.php';
//             break;
//         case '/FinalProj/thesis';
//             require __DIR__ . '/thesis.php';
//             break;
//     default:
//         http_response_code(404);
//         require __DIR__ . '/404.php';
// }