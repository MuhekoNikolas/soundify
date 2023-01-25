<?php

require_once __DIR__."/soundify/project/config.php";
require_once __DIR__.'/router.php';

//Home page
any('/', (function(){
    return "/soundify/public/pages/main/home.php";
})());
//Favicon
get('/favicon.ico', function(){
    $fileType = mime_content_type(__DIR__."/favicon.ico");
    header("Content-Type: $fileType");
    readfile(__DIR__."/favicon.ico");
    exit();
});

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


//Getting song audios
get('/songs/$songFolder/audios/audio.mp3', function($songFolder){
        $audioFile = __DIR__."/soundify/public/songs/$songFolder/audio.mp3";
        if(file_exists($audioFile)){
            $fileContentType = mime_content_type($audioFile);
            header("Content-Type: $fileContentType");
            header("Content-Length: " . filesize($audioFile));
            header("Accept-Ranges: bytes");
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


//Creating songs route
get('/artists/$artistName/songs/new', function($artistName){
    $loggedInUser = isLoggedIn();
    if($loggedInUser == false){
        header("Location: /artists/$artistName");
        exit();
    } else if($loggedInUser->username != $artistName){
        header("Location: /artists/$artistName");
        exit();
    }

    include(__DIR__."/soundify/public/pages/other/createSongs.php");
});


//Creating songs Post route
post('/artists/$artistName/songs/new', function($artistName){
    $loggedInUser = isLoggedIn();
    $postData = json_decode(file_get_contents("php://input"));

    if($loggedInUser == false){
        http_response_code(403);
        $obj = array("success"=>false, "message"=>"Must be logged in.");
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return;

    } else if($loggedInUser->username != $artistName){
        http_response_code(403);
        $obj = array("success"=>false, "message"=>"Access denied.");
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return;
    } 

    $obj = json_decode(json_encode(["success"=>false, "message"=>""]), false);

    if(strlen($postData->name) < 1){
        $obj->success = false;
        $obj->message = "Song Name is empty";
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return false;
    }

    if(strlen($postData->name) > 20){
        $obj->success = false;
        $obj->message = "Song Name's length must be less than 21 digits";
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return false;
    }

    if(strlen($postData->image) < 1){
        $obj->success = false;
        $obj->message = "Song Image is empty";
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return false;
    }


    
    if(strlen($postData->audio) < 1){
        $obj->success = false;
        $obj->message = "Song Audio is empty";
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return false;
    }

    if(substr($postData->image, 0, 4) != "http" && substr($postData->image, 0, 4) != "data"){
        $obj->success = false;
        $obj->message = "Invalid image link.";
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return false;
    }

    if(substr($postData->audio, 0, 4) != "http" && substr($postData->audio, 0, 4) != "data"){
        $obj->success = false;
        $obj->message = "Invalid Audio link.";
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return false;
    }


    if(substr($postData->image, 0, 4) == "http"){
        $postData->image = getDataLink($postData->image);
    }

    if(preg_match("/image\/(.)+/i", mime_content_type($postData->image)) != true){
        $obj->success = false;
        $obj->message = "The attached song image file wasnt an image.";
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return false;
    }

    if(preg_match("/audio\/(mp3|wav|mpeg|mp4)/i", mime_content_type($postData->audio)) != true){
        $obj->success = false;
        $obj->message = "The attached song audio file isnt supported.";
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return false;
    }


    $songFolder = uniqid("songId_");
    $songName = $postData->name;
    $artistId = $loggedInUser->id;

    $songInfoObj = [
        "name" => $songName,
        "id" => $songFolder,
        "artist" => $loggedInUser->username,
        "image" => $postData->image,
        "audio" => "/songs/$songFolder/audios/audio.mp3",
        "folderName" => $songFolder
    ];

    $res = $GLOBALS["mainDB"]->query("INSERT INTO songs (user_id, name) VALUES('$artistId', '$songFolder')");

    if($res != 1){
        $obj->success = "false";
        $obj->message = "An error occured";
        header("Content-Type: application/json");
        echo json_encode($obj);
        exit();
        return false;
    }


    mkdir(__DIR__."/soundify/public/songs/$songFolder");
    
    file_put_contents(__DIR__."/soundify/public/songs/$songFolder/info.json", json_encode($songInfoObj, JSON_PRETTY_PRINT)); 
    file_put_contents(__DIR__."/soundify/public/songs/$songFolder/image.png",  file_get_contents($postData->image));
    file_put_contents(__DIR__."/soundify/public/songs/$songFolder/audio.mp3",  file_get_contents($postData->audio));

    $obj->success = true;
    $obj->message = "Song uploaded successfully";

    $obj->uploadedSong = $songInfoObj;

    echo json_encode($obj);
    exit;
});



//Getting user songs
get('/api/artists/$artistId/songs', function($artistId){
    $loggedInUser = isLoggedIn();
    //Getting user songs
    $sql = "SELECT id,user_id,name FROM songs WHERE user_id='$artistId';";
    $results = $GLOBALS["mainDB"]->query($sql);
    $foundSongs = $results->fetch_all() or array();

    $_x_ = array();
    foreach($foundSongs as $foundSong){
        $foundSongName = $foundSong[2];

        $path = __DIR__."/soundify/public/songs/$foundSongName/";

        if(is_dir($path)){
            $_x_[count($_x_)] = json_decode(file_get_contents("$path/info.json"));
        } else {
            continue;
        }
        $songInfo = "";
    }

    header("Content-Type: application/json");
    echo json_encode($_x_);
    exit();
});

//Getting all playlists
get('/api/playlists', function(){
    $playlistsFile = __DIR__."/soundify/public/playlists.json";
    
    header("Content-Type: application/json");
    echo json_encode(json_decode(file_get_contents($playlistsFile)), JSON_PRETTY_PRINT);
    exit;
});


//Getting User Playlists
get('/api/artists/$artistId/playlists', function($artistId){
    $playlistsFile = __DIR__."/soundify/public/playlists.json";

    if(file_exists($playlistsFile)){
        $allPlayLists = json_decode(file_get_contents($playlistsFile), true);
        if($allPlayLists != null){
            header("Content-Type: application/json");
            $data = array();
            foreach($allPlayLists as $objKey=>$objVal){
                if((string)($objVal["user_id"]) == $artistId){
                    $data[count($data)] = $objVal;
                }
            };

            //Ferdig

            echo json_encode($data, JSON_PRETTY_PRINT);
            exit;

        } else {
            http_response_code(400);
            echo '{"success":"false", "message":"An error occured"}';
            exit;
        }
    } else {
        file_put_contents($playlistsFile, json_encode(array(), JSON_PRETTY_PRINT));
        echo '{"success":"true", "data":"{}"}';
        exit;
    }

    exit;
});



any("/404", (function(){
    http_response_code(404);
    return "/soundify/public/pages/other/404.php";
})());
