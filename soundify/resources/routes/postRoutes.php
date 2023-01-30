



<?php

    //Add a song to a playlist
    post('/api/playlists/$playlistId/songs/add', function($playlistId){
        $loggedInUser = isLoggedIn();
        $postData = json_decode(file_get_contents("php://input"));

        if($loggedInUser == false){
            http_response_code(403);
            $obj = array("success"=>false, "message"=>"Must be logged in.");
            header("Content-Type: application/json");
            echo json_encode($obj);
            exit();
            return;
        } 
        

        if(isset($postData->songInfo)){
            if(isset($postData->songInfo->id)){
                if(is_dir(__DIR__."/../../public/songs/".$postData->songInfo->id)){
                    $obj = array("success"=>false, "message"=>"");
                    $savedPlaylists = json_decode(file_get_contents(__DIR__."/../../public/playLists.json"));
                    if(isset($savedPlaylists->$playlistId)){                       
                        if(in_array($postData->songInfo->id,  $savedPlaylists->$playlistId->songs) == true){
                            $obj["success"] = false;
                            $obj["message"] = "Song is already in this playlist";
                            echo json_encode($obj);
                            exit; 
                        } else {
                            array_push($savedPlaylists->$playlistId->songs, $postData->songInfo->id);
                            file_put_contents(__DIR__."/../../public/playLists.json", json_encode($savedPlaylists, JSON_PRETTY_PRINT));
                            $obj["success"] = true;
                            $obj["message"] = "Song added to playlist";
                            $obj["addedTo"] = $savedPlaylists->$playlistId;
                            echo json_encode($obj);
                            exit; 
                        }
                    } else {
                        $obj["success"] = false;
                        $obj["message"] = "Invalid playlist Id";
                        echo json_encode($obj);
                        exit; 
                    }
                } else {
                    $obj = array("success"=>false, "message"=>"Invalid song info");
                    echo json_encode($obj);
                    exit;
                }
            } else {
                $obj = array("success"=>false, "message"=>"Invalid song info");
                echo json_encode($obj);
                exit;
            }
        } else {
            $obj = array("success"=>false, "message"=>"Invalid song info");
            echo json_encode($obj);
            exit;
        }
    });
    
    //Creating playlists Post route
    post('/api/artists/$artistName/playlists/new', function($artistName){
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
            $obj->message = "Playlist Name is empty";
            header("Content-Type: application/json");
            echo json_encode($obj);
            exit();
            return false;
        }

        if(strlen($postData->name) > 25){
            $obj->success = false;
            $obj->message = "Playlist Name's length must be less than 26 digits";
            header("Content-Type: application/json");
            echo json_encode($obj);
            exit();
            return false;
        }

        if(strlen($postData->image) < 1){
            $obj->success = false;
            $obj->message = "Playlist Image is empty";
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

        if(substr($postData->image, 0, 4) == "http"){
            $postData->image = getDataLink($postData->image);

            if(strlen($postData->image) <= 1){
                $obj->success = false;
                $obj->message = "Invalid image link.";
                header("Content-Type: application/json");
                echo json_encode($obj);
                exit();
                return false;
            }
        }

        if(preg_match("/image\/(.)+/i", mime_content_type($postData->image)) != true){
            $obj->success = false;
            $obj->message = "The attached playlist image file wasnt an image.";
            header("Content-Type: application/json");
            echo json_encode($obj);
            exit();
            return false;
        }

        $playlistId = uniqid("playlistId_");
        $playlistName = $postData->name;
        $artistId = $loggedInUser->id;

        $playlistInfoObj = [
            "name" => $playlistName,
            "id" => $playlistId,
            "user" => $loggedInUser->username,
            "user_id" => $artistId,
            "songs"=> array(),
            "image" => $postData->image,
        ];

        $savedPlaylists = json_decode(file_get_contents(__DIR__."/../../public/playLists.json"));
        $savedPlaylists->$playlistId = $playlistInfoObj;

        file_put_contents(__DIR__."/../../public/playLists.json", json_encode($savedPlaylists,JSON_PRETTY_PRINT));

        $obj->success = true;
        $obj->message = "Successfully created the playlist";
        $obj->createdPlaylist = $playlistInfoObj;
        echo json_encode($obj);
        exit;

    });





    //Creating songs Post route
    post('/api/artists/$artistName/songs/new', function($artistName){
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
            if(strlen($postData->image) <= 1){
                $obj->success = false;
                $obj->message = "Invalid image link.";
                header("Content-Type: application/json");
                echo json_encode($obj);
                exit();
                return false;
            }
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


        mkdir(__DIR__."/../../public/songs/$songFolder");
        
        file_put_contents(__DIR__."/../../public/songs/$songFolder/info.json", json_encode($songInfoObj, JSON_PRETTY_PRINT)); 
        file_put_contents(__DIR__."/../../public/songs/$songFolder/image.png",  file_get_contents($postData->image));
        file_put_contents(__DIR__."/../../public/songs/$songFolder/audio.mp3",  file_get_contents($postData->audio));

        $obj->success = true;
        $obj->message = "Song uploaded successfully";

        $obj->uploadedSong = $songInfoObj;

        echo json_encode($obj);
        exit;
    });



?>