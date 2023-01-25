<?php
session_start();
function get($route, $path_to_include){
  if( $_SERVER['REQUEST_METHOD'] == 'GET' ){ route($route, $path_to_include); }  
}
function post($route, $path_to_include){
  if( $_SERVER['REQUEST_METHOD'] == 'POST' ){ route($route, $path_to_include); }    
}
function put($route, $path_to_include){
  if( $_SERVER['REQUEST_METHOD'] == 'PUT' ){ route($route, $path_to_include); }    
}
function patch($route, $path_to_include){
  if( $_SERVER['REQUEST_METHOD'] == 'PATCH' ){ route($route, $path_to_include); }    
}
function delete($route, $path_to_include){
  if( $_SERVER['REQUEST_METHOD'] == 'DELETE' ){ route($route, $path_to_include); }    
}
function any($route, $path_to_include){ route($route, $path_to_include); }
function route($route, $path_to_include){
  $callback = $path_to_include;
  if( !is_callable($callback) ){
    if(!strpos($path_to_include, '.php')){
      $path_to_include.='.php';
    }
  }    
  if($route == "/404"){
    include_once __DIR__."/$path_to_include";
    exit();
  }  
  $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
  $request_url = rtrim($request_url, '/');
  $request_url = strtok($request_url, '?');
  $route_parts = explode('/', $route);
  $request_url_parts = explode('/', $request_url);
  array_shift($route_parts);
  array_shift($request_url_parts);
  if( $route_parts[0] == '' && count($request_url_parts) == 0 ){
    // Callback function
    if( is_callable($callback) ){
      call_user_func_array($callback, []);
      exit();
    }
    include_once __DIR__."/$path_to_include";
    exit();
  }
  if( count($route_parts) != count($request_url_parts) ){ return; }  
  $parameters = [];
  for( $__i__ = 0; $__i__ < count($route_parts); $__i__++ ){
    $route_part = $route_parts[$__i__];
    if( preg_match("/^[$]/", $route_part) ){
      $route_part = ltrim($route_part, '$');
      array_push($parameters, $request_url_parts[$__i__]);
      $$route_part=$request_url_parts[$__i__];
    }
    else if( $route_parts[$__i__] != $request_url_parts[$__i__] ){
      return;
    } 
  }
  // Callback function
  if( is_callable($callback) ){
    call_user_func_array($callback, $parameters);
    exit();
  }    
  include_once __DIR__."/$path_to_include";
  exit();
}
function out($text){echo htmlspecialchars($text);}
function set_csrf(){
  if( ! isset($_SESSION["csrf"]) ){ $_SESSION["csrf"] = bin2hex(random_bytes(50)); }
  echo '<input type="hidden" name="csrf" value="'.$_SESSION["csrf"].'">';
}
function is_csrf_valid(){
  if( ! isset($_SESSION['csrf']) || ! isset($_POST['csrf'])){ return false; }
  if( $_SESSION['csrf'] != $_POST['csrf']){ return false; }
  return true;
}


function initDatabase(){
  $mainDb = new mysqli("localhost", $GLOBALS["CONFIG"]["DB"]["username"], $GLOBALS["CONFIG"]["DB"]["password"]);
  if($mainDb->query("select schema_name from information_schema.schemata where schema_name = '".$GLOBALS["CONFIG"]["DB"]["database"]."'")->num_rows <= 0){
    $mainDb->query("CREATE DATABASE `".$GLOBALS["CONFIG"]["DB"]["database"]."`");
  }

  $db = new mysqli("localhost", $GLOBALS["CONFIG"]["DB"]["username"], $GLOBALS["CONFIG"]["DB"]["password"], $GLOBALS["CONFIG"]["DB"]["database"]);

  if($db->connect_errno){
      return null;
      exit($db->connect_errno);
  } 

  if($db->query("SHOW TABLES LIKE 'users'")->num_rows <= 0){
    $sql = "
        CREATE TABLE `soundify`.`users` (`username` TEXT NOT NULL , `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `pfp` TEXT NOT NULL DEFAULT 'https://smapp.tk/static/images/pfps/robot.jpg' , `profilePage` TEXT NOT NULL , `admin` BOOLEAN NOT NULL DEFAULT FALSE ,  `join_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`), UNIQUE (`username`)) ENGINE = InnoDB; 
      ";
    $db->query($sql);
  } 

  if($db->query("SHOW TABLES LIKE 'secrets'")->num_rows <= 0){
    $sql = "
        CREATE TABLE `soundify`.`secrets` (`hashed_password` TEXT NOT NULL , `user_id` INT UNSIGNED NOT NULL , `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , PRIMARY KEY (`id`)) ENGINE = InnoDB;
    ";
    $db->query($sql);
  } 

  if($db->query("SHOW TABLES LIKE 'songs'")->num_rows <= 0){
    $sql = "
      CREATE TABLE `soundify`.`songs` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `user_id` INT UNSIGNED NOT NULL , `name` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
    ";
    $db->query($sql);
  } 

  // if($db->query("SHOW TABLES LIKE 'playlists'")->num_rows <= 0){
  //   $sql = "
  //     CREATE TABLE `soundify`.`playlists` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `user_id` INT UNSIGNED NOT NULL , `public_id` TEXT NOT NULL , `name` TEXT NOT NULL , PRIMARY KEY (`id`), UNIQUE (`public_id`)) ENGINE = InnoDB;
  //   ";
  //   $db->query($sql);
  // } 




  return $db;
};



function getDataLink($imagePath){
  if(file_exists($imagePath)){
      // Read image path, convert to base64 encoding
      $imageData = base64_encode(file_get_contents($imagePath));

      // Format the image SRC:  data:{mime};base64,{data};
      $src = 'data:'.mime_content_type($imagePath).';base64,'.$imageData;
      return $src;
  } else {
      return "";
  }
};

function isLoggedIn($redirect=false){

  if(!isset($GLOBALS["mainDb"])){
    $mainDB = initDatabase();
    if($mainDB==null){
        echo "An error occured while initialising the database, please make sure that you set up the app correctly";
        return false;
    } else {
        $GLOBALS["mainDB"] = $mainDB;
    }

  }

  if(array_key_exists("soundifyToken", $_COOKIE)){

      $token = $_COOKIE["soundifyToken"];

      $res = $GLOBALS["mainDB"]->query("SELECT * FROM secrets WHERE hashed_password='$token' ")->fetch_all();

      if($res == NULL){
          return false;
      } else {
          $LoggedInUserId = json_decode($res[0][1]);
          $LoggedInUser = getUser($LoggedInUserId);
          if($redirect == true){
              redirect($LoggedInUser->profilePage);
          }

          return $LoggedInUser;
      }

  } else{
      return false;
  }

}


function getUser($query){


  if(gettype($query) =="string"){
      $sql = "SELECT * FROM users WHERE username = '$query'; ";
  } else if(gettype($query) == "integer"){
      $sql = "SELECT * FROM users WHERE id = '$query'; ";
  } else {
      return NULL;
  }

  $results = $GLOBALS["mainDB"]->query($sql);
  $foundProfiles = $results->fetch_all();
  if( $foundProfiles == NULL){
      //If no account matching that name exists.
      return NULL;
  } else {
      //If the account exists.
      $foundUserArray = $foundProfiles[0];

      $foundUser = json_decode(json_encode(array("username"=>$foundUserArray[0], "id"=>$foundUserArray[1], "pfp"=>$foundUserArray[2], "profilePage"=>$foundUserArray[3], "admin"=>$foundUserArray[4], "join_date"=>$foundUserArray[5])));

      return $foundUser;
  }
  
  
};