

<?php 
    //Home page
    any('/', (function(){
        return "/soundify/public/pages/main/home.php";
    })());


    //Favicon
    get('/favicon.ico', function(){
        $fileType = mime_content_type(__DIR__."/../../../favicon.ico");
        header("Content-Type: $fileType");
        readfile(__DIR__."/../../../favicon.ico");
        exit();
    });


    //Profile Pages
    get('/artists/$artistName', "/soundify/public/pages/main/artistPage.php");


    //Playlist Page
    get('/playlists/$playlistId', function($playlistId){
        $allPlaylists = json_decode(file_get_contents(__DIR__."/../../public/playLists.json"), true);

        if( array_key_exists($playlistId, $allPlaylists) == false){
            include(__DIR__."/../../public/pages/other/404.php");
            exit;
        }

        $pagePlaylist = $allPlaylists[$playlistId];
        include(__DIR__."/../../public/pages/main/playlistPage.php");
        exit;
    });


    //Getting song audios
    get('/songs/$songFolder/audios/audio.mp3', function($songFolder){
        $audioFile = __DIR__."/../../public/songs/$songFolder/audio.mp3";
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
    });



    //Creating Playlists route
    get('/artists/$artistName/playlists/new', function($artistName){
        $loggedInUser = isLoggedIn();
        if($loggedInUser == false){
            header("Location: /artists/$artistName");
            exit();
        } else if($loggedInUser->username != $artistName){
            header("Location: /artists/$artistName");
            exit();
        }

        include(__DIR__."/../../public/pages/other/createPlaylist.php");
        exit;
    });


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

        include(__DIR__."/../../public/pages/other/createSongs.php");
        exit;
    });



?>