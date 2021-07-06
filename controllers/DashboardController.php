<?php

namespace SimplePHPFramework\controllers;

require __DIR__ . "/../vendor/autoload.php";

use SimplePHPFramework\kernel\View;

class DashboardController
{
    private View $viewEngine;
    public function __construct()
    {
        // Initialize the viewEngine
        $this->viewEngine = new View;
    }

    public function index()
    {
        $this->viewEngine->render("dashboard/home/index.pug");
    }
}
