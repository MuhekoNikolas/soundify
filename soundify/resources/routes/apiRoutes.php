
<?php 

    //Getting all playlists
    get('/api/playlists', function(){
        $playlistsFile = __DIR__."/../../public/playLists.json";
        
        header("Content-Type: application/json");
        echo json_encode(json_decode(file_get_contents($playlistsFile)), JSON_PRETTY_PRINT);
        exit;
    });

    //Getting playlist songs
    get('/api/playlists/$playlistId/songs', function($playlistId){
        $playlistsFile = __DIR__."/../../public/playLists.json";
        $allPagePlaylists = json_decode(file_get_contents($playlistsFile), true);
        if(key_exists($playlistId, $allPagePlaylists)){
            $pagePlaylist = $allPagePlaylists[$playlistId];
            $_x_ = array();
            foreach($pagePlaylist["songs"] as $playlistSongId){
                $path = __DIR__."/../../public/songs/$playlistSongId/";
    
                if(is_dir($path)){
                    $_x_[count($_x_)] = json_decode(file_get_contents("$path/info.json"));
                } else {
                    continue;
                }
            }
            echo json_encode($_x_);
            exit;
            
        } else {
            echo json_encode(array());
            exit;
        }
    });


        
    //Getting User Playlists
    get('/api/artists/$artistId/playlists', function($artistId){
        $playlistsFile = __DIR__."/../../public/playLists.json";

        if(file_exists($playlistsFile)){
            $allPlayLists = json_decode(file_get_contents($playlistsFile), true);
            if($allPlayLists != null && count($allPlayLists) >= 1){
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
                echo '[]';
                exit;
            }
        } else {
            file_put_contents($playlistsFile, json_encode(array(), JSON_PRETTY_PRINT));
            echo '[]';
            exit;
        }

        exit;
    });

    //Getting all page songs
    get("/api/songs", function(){
        isLoggedIn();

        $res = ($GLOBALS["mainDB"]->query("SELECT * FROM songs"));
        $results = $res->fetch_all();
        if($results==null){
            $results = array();
        };

        $_x_ = array();
        foreach($results as $foundSong){
            $foundSongName = $foundSong[2];
            $path = __DIR__."/../../public/songs/$foundSongName/";

            if(is_dir($path)){
                $_x_[count($_x_)] = json_decode(file_get_contents("$path/info.json"));
            } else {
                continue;
            }
        }

        header("Content-Type: application/json");
        echo json_encode($_x_, JSON_PRETTY_PRINT);
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

            $path = __DIR__."/../../public/songs/$foundSongName/";

            if(is_dir($path)){
                $_x_[count($_x_)] = json_decode(file_get_contents("$path/info.json"));
            } else {
                continue;
            }
        }

        header("Content-Type: application/json");
        echo json_encode($_x_, JSON_PRETTY_PRINT);
        exit();
    });

?>