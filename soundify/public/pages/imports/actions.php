


<?php
    //error_reporting(E_ERROR  | E_PARSE);



    function getUserSecrets($id){
        $res =  $GLOBALS["mainDB"]->query("SELECT * FROM secrets where user_id='$id'");
        $res = $res->fetch_all();
        if($res == null){
            return null;
        } else {
            return json_decode(json_encode($res[0]));
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
            $foundUserSecrets = getUserSecrets($foundUser->id);

            $hashedUserPass = $foundUserSecrets[0];

            $userObject = $foundUser;

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
                $uJoinDate = $createdUser[5];



                $createdUserId = $uId;

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 
                $secretsTableQuery = "INSERT into secrets (hashed_password, user_id) VALUES ('$hashedPassword', '$createdUserId')";

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
