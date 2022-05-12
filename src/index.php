<?php

use Phalcon\Mvc\Micro;
use Phalcon\Loader;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\Mvc\View\Simple;

define('BASE_PATH', '/');

require_once './vendor/autoload.php';

$loader = new Loader();
$loader->registerNamespaces(
    [
        'Api\Handler' => './handler',
        'Api\Middleware' => './components',
    ]
);

$loader->register();

$loader->registerDirs(
    [
        './controllers/',
    ]
);

// Register autoloader  
$loader->register();

// echo __DIR__ .'/handler';die;
$app = new Micro();

$app['view'] = function () {
    $view = new Simple();
    $view->setViewsDir('./views/');

    return $view;
};


$app->before(
    function () use ($app) {
        $mw  = explode('/', $_SERVER['REQUEST_URI']);
        if ($mw[1] == "registeruser" || $mw[1] == "acl") {
            if ($mw[2] == "validate" || $mw[2] == "buildacl" ) {
                return true;
            }
        } else {
            $key = $app->request->get('key');
            echo $key; die;
            // if ($key) {
            //     echo 'true2';
            //     die;
            //     return true;
            // } else {
            //     echo 'false';
            //     die;
            return false;
        }
    }
    // }
);
//     function () use ($app) {
//         if (false === $app['session']->get('auth')) {
//             $app['flashSession']->error("The user isn't authenticated");

//             $app['response']->redirect('/error');

//             // Return false stops the normal execution
//             return false;
//         }

//         return true;
//     }
// );

$products = new MicroCollection();
$products
    ->setHandler(new ProductController())
    ->setPrefix('/product')
    ->get('/search/{keyword}', 'search');

$app->mount($products);
$products = new MicroCollection();
$products
    ->setHandler(new ProductController())
    ->setPrefix('/product')
    ->get('/get', 'index')
    ->get('/get/{keyword}', 'search');

$app->mount($products);

$order = new MicroCollection();
$order
    ->setHandler(new OrderController())
    ->setPrefix('/order')
    ->get('/create/{p_name}/{category}/{price}/{quantity}/{token}', 'create')
    ->get('/update/{id}/{status}', 'update');

$app->mount($order);
$user = new MicroCollection();
$user
    ->setHandler(new RegisteruserController())
    ->setPrefix('/registeruser')
    ->get('/', 'index')
    ->get('/validate', 'validate');

$app->mount($user);
$login = new MicroCollection();
$login
    ->setHandler(new LoginController())
    ->setPrefix('/login')
    ->get('/', 'index')
    ->get('/auth', 'auth');

$app->mount($login);
$acl = new MicroCollection();
$acl
    ->setHandler(new AclController())
    ->setPrefix('/acl')
    ->get('/', 'index')
    ->get('/buildacl', 'buildacl');

$app->mount($acl);


$app->get(
    '/product/get',
    function () use ($app) {
        // app/views/invoices/view.phtml
        echo $app['view']
            ->render(
                '/product/get',
                []
            );
    }
);
$app->get(
    '/registeruser/index',
    function () use ($app) {
        // app/views/invoices/view.phtml
        echo $app['view']
            ->render(
                '/registeruser/index',
                []
            );
    }
);
$app->get(
    '/login/index',
    function () use ($app) {
        // app/views/invoices/view.phtml
        echo $app['view']
            ->render(
                '/login/index',
                []
            );
    }
);
// $app->get(
//     '/login/auth',
//     function () use ($app) {
//         // app/views/invoices/view.phtml
//         echo $app['view']
//             ->render(
//                 '/login/auth',
//                 []
//             );
//     }
// );
// $app->get(
//     '/registeruser/validate',
//     function () use ($app) {
//         // app/views/invoices/view.phtml
//         echo $app['view']
//             ->render(
//                 '/registeruser/validate',
//                 []
//             );
//     }
// );

// $product = new Api\Handler\Product();
// $order = new Api\Handler\Order();

// $app = new Micro();


// $app->get(
//     '/product/search/{keyword}',
//     [
//         $product,
//         'search'
//     ]
// );
// $app->get(
//     '/product/get/{per_page}/{page}',
//     [
//         $product,
//         'get'
//     ]
// );
// $app->post(
//     '/order/create/{product_name}/{price}',
//     [
//         $order,
//         'create'
//     ]
// );
// $app->put(
//     '/order/update/{keyword}',
//     [
//         $order,
//         'update'
//     ]
// );
$app->handle(
    $_SERVER["REQUEST_URI"]
);
