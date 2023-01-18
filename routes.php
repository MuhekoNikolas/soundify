<?php

require_once __DIR__."/soundify/project/config.php";
require_once __DIR__.'/router.php';

//Home page
any('/', (function(){
    return "/soundify/public/pages/main/home.php";
})());

//Login page
any("/login", "/soundify/public/pages/main/login.php");

//Logout page
any('/logout', function(){
    setCookie("soundifyToken", "");
    header("Location: /login");
});

//Signup page
any("/signup", "/soundify/public/pages/main/signup.php");


//Profile Pages
get('/artists/$artistName', "/soundify/public/pages/main/artistPage.php");

//CSS and JS imports
include_once (__DIR__.'/soundify/resources/middlewares/scriptsAndCssRoutes.php');


//Getting images.
get('/images/$fileName', function($fileName){
    preg_replace("/\/(.)*/i", "", $fileName);
    $fileName = __DIR__."/soundify/public/images/$fileName";

    if(file_exists($fileName)){
        $fileContentType = mime_content_type($fileName);

        header("Content-Type: $fileContentType;");
        echo file_get_contents($fileName);
        exit();
    } else {
        http_response_code(404);
        echo "Not found";
        exit();
    }
});

//Getting song images
get('/songs/$songFolder/image.jpg', 
    function($songFolder){
        $songImage = __DIR__."/soundify/public/songs/$songFolder/image.jpg";
        if(file_exists($songImage)){
            $fileContentType = mime_content_type($songImage);
            header("Content-Type: $fileContentType");
            header("Content-Length: " . filesize($songImage));
            header("Cache-Control: no-cache, must-revalidate");
            readfile($songImage);
            exit();
        } else {
            http_response_code(404);
            echo "Not found";
            exit();
        }
    }
    
);

//Getting song audios
get('/songs/$songFolder/audios/audio.mp3', function($songFolder){
        $audioFile = __DIR__."/soundify/public/songs/$songFolder/audio.mp3";
        if(file_exists($audioFile)){
            $fileContentType = mime_content_type($audioFile);
            header("Content-Type: $fileContentType");
            header("Content-Length: " . filesize($audioFile));
            header("Cache-Control: no-cache, must-revalidate");
            readfile($audioFile);
            exit();
        } else {
            http_response_code(404);
            echo "Not found";
            exit();
        }
    }
    
);



any("/404", (function(){
    http_response_code(404);
    return "/soundify/public/pages/other/404.php";
    //eturn "./404.php";
})());
