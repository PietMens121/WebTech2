<?php

namespace src\routes;

use App\Routing\Router;

//Example route
// Router::newRoute('/', 'Home', 'HomePageController', 'get');

Router::newRoute('get', '/', 'Home', 'HomePageController', 'showView');
