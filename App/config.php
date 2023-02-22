<?php

namespace App;

use App\Container\DependencyContainer;
use src\controllers\HomePageController;

$array = [];

$array['HomePageController'] = new HomePageController();

$DIcontainer = new DependencyContainer($array);


