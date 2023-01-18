


<?php
    //error_reporting(E_ERROR  | E_PARSE);

    function isLoggedIn($redirect=false){

        if(array_key_exists("soundifyToken", $_COOKIE)){

            $token = $_COOKIE["soundifyToken"];

            $res = $GLOBALS["mainDB"]->query("SELECT * FROM secrets WHERE hashed_password='$token' ")->fetch_all();
    
            if($res == NULL){
                return false;
            } else {
                $LoggedInUser = json_decode($res[0][1]);

                if($redirect == true){
                    redirect($LoggedInUser->profilePage);
                }
    
                return $LoggedInUser;
            }

        } else{
            return false;
        }


    }


    function login($username, $password){

        $obj = ["success"=>false, "message"=>""];
        
        

        $usernameValid = preg_match("/[^a-zA-Z0-9_]/i", $username) == 0 ? True : False;
        $passwordValid = preg_match("/[^a-zA-Z0-9_]/i", $password) == 0 ? True : False;


        if($usernameValid == False || $passwordValid == False ){
            $obj["message"] = "Usernames and Passwords can only contain alphanumeric letters and '_' .";
            return $obj;
        }

        $foundUser = getUser($username);

        if($foundUser == null){
            $obj["message"] = "No account found matching that username.";
        } else {
            $hashedUserPass = $foundUser[0];
            $userObject = json_decode("$foundUser[1]", true);
            $passwordsMatch = password_verify($password, $hashedUserPass);

            if($passwordsMatch == true){
                $obj["success"] = true;
                $obj["loggedInUser"] = $userObject;
                $obj["token"] = $hashedUserPass;
                $obj["message"] = "Logging in";
                return $obj;
            } else {
                $obj["message"]  =  "Incorrect Username or password";
                return $obj;
            }
        }

        return ["success"=>false, "message"=>$obj["message"]];


    }




    function signup($username, $password){
        
        $obj = ["success"=>false, "message"=>""];

        $UserExists = getUser($username) == NULL ? False : True;


        if($UserExists == True){
            $obj["message"] = "An account with this username already exists";
            return $obj;
        } else {
            $usernameValid = preg_match("/[^a-zA-Z0-9_]/i", $username) == 0 ? True : False;
            $passwordValid = preg_match("/[^a-zA-Z0-9_]/i", $password) == 0 ? True : False;

            if($usernameValid == False || $passwordValid == False ){
                $obj["message"] = "Usernames and Passwords can only contain alphanumeric letters and 'dashes (_)' .";
                return $obj;
            } 
            if(strlen($username) < 3 || strlen($username) > 12){
                $obj["message"] = "Username is either too short(<3) or too long(>12)!";
                return $obj;
            } 
            if(strlen($password) < 5){
                $obj["message"] = "Password is too short(<5)!";
                return $obj;
            } 

            $userToInsert = createUser($username, $password);
            if($userToInsert["success"]==true){           
                $obj["success"] = true;
                $obj["createdUser"] = $userToInsert["createdUser"];
                $obj["token"] = $userToInsert["token"];
                return $obj;
            } else {
                $obj["success"] = false;
            }
            //$usersTableQuery = query = 
            

            
        }    
    }


    function getUser($username){


        $sql = "SELECT * FROM secrets WHERE JSON_VALUE(user, '$.username') LIKE '$username'; ";
        $results = $GLOBALS["mainDB"]->query($sql);
        $foundProfiles = $results->fetch_all();
        if( $foundProfiles == NULL){
            //If no account matching that name exists.
            return NULL;
        } else {
            //If the account exists.
            return $foundProfiles[0];
        }
        
        
    };


    function createUser($username, $password){
        $obj = ["success"=>false, "message"=>""];

        $profilePage = "/artists/$username";

        $pfp = "https://smapp.tk/static/images/pfps/robot.jpg";

        $userTableQuery = "INSERT INTO users (username, profilePage, pfp) VALUES ('$username', '$profilePage', '$pfp');";
        $res = $GLOBALS["mainDB"]->query($userTableQuery);

        $IsUsercreated = $res == 1 ? True : False;

        
        if( $IsUsercreated == False){
            $obj["messsage"] = "An error occured";
            return $obj;
        } else {
            $res = $GLOBALS["mainDB"]->query("SELECT * FROM users WHERE username='$username';");
            $results = $res->fetch_all();

            if($results == null ){
                $obj["messsage"] = "An error occured";
                $GLOBALS["mainDB"]->query("DELETE FROM users WHERE username='$username';");
                return $obj;
            } else {
                $createdUser = $results[count($results)-1];

                $uUsername = $createdUser[0];
                $uId = $createdUser[1];
                $uPfp = $createdUser[2];
                $uPage = $createdUser[3];
                $uAdmin = $createdUser[4];
                $uMusic = $createdUser[5];
                $uJoinDate = $createdUser[6];

                $jsonCreatedUser = json_encode(array(
                    "username" => $uUsername,
                    "id" => $uId,
                    "pfp" => $uPfp,
                    "profilePage" => $uPage,
                    "admin" => $uAdmin,
                    "music" => preg_replace("/(\\)(\")/i", "(\\)(\\)(\")",$uMusic),
                    "join_date" => $uJoinDate
                ));

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 
                $secretsTableQuery = "INSERT into secrets (hashed_password, user) VALUES ('$hashedPassword', '$jsonCreatedUser')";

                $SecretAddded = $GLOBALS["mainDB"]->query($secretsTableQuery) == 1 ? True : False;

                if($SecretAddded == True){
                    $obj["createdUser"] = $createdUser;
                    $obj["token"] = $hashedPassword;
                    $obj["success"] = true;
                    $obj["message"] = "Account created";
                    return $obj;
                } else {
                    $GLOBALS["mainDB"]->query("DELETE FROM users WHERE username='$username';");
                    $obj["success"] = false;
                    $obj["message"] = "An error occured";
                    return $obj;
                }
            }

        }

    }

    function redirect($path){
        header("Location: $path");
        die();
    }

?>
