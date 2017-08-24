<?php
$router = new Router();

/*
  Front Router
*/
$router->get('/', function () {
  Front::index();
});

/*
  Apps
*/
$router->match('GET|POST','/(.*)', function($slug) {
  Front::index($slug);
});


/*
  Default: 404
*/
$router->set404(function() {
  Rizoa::notfound();
});

$router->run();
