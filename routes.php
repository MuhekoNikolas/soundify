<?php

require_once __DIR__."/soundify/project/config.php";
require_once __DIR__.'/router.php';


//CSS and JS imports
include_once (__DIR__.'/soundify/resources/middlewares/scriptsAndCssRoutes.php');


//Including all the page routes
require_once __DIR__."/soundify/resources/routes/getRoutes.php";
require_once __DIR__."/soundify/resources/routes/anyRoutes.php";
require_once __DIR__."/soundify/resources/routes/apiRoutes.php";
require_once __DIR__."/soundify/resources/routes/postRoutes.php";




any("/404", (function(){
    http_response_code(404);
    return "/soundify/public/pages/other/404.php";
})());
