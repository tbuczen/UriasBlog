<?php
//YAGNI, KISS, DRY principles
require_once "config.php";

$fc = new FrontController();
$fc->handleRequest();

/*
 * FLOW
 *
 * FrontController->handleRequest
 * RoutingController->routeToPage
 *
 * Routing handles Controller name, controller action and needed params
 *
 * [Given]Controller->[given]Action([givenParams])
 *
 * Collect Data from [given]Action
 *
 * ViewController->render()
 *
 */