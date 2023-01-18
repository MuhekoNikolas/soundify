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
        CREATE TABLE `soundify`.`users` (`username` TEXT NOT NULL , `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `pfp` TEXT NOT NULL DEFAULT 'https://smapp.tk/static/images/pfps/robot.jpg' , `profilePage` TEXT NOT NULL , `admin` BOOLEAN NOT NULL DEFAULT FALSE , `music` JSON NOT NULL DEFAULT '{\"songs\":[], \"playlists\":[]}' , `join_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`), UNIQUE (`username`)) ENGINE = InnoDB; 
      ";
    $db->query($sql);
  } 

  if($db->query("SHOW TABLES LIKE 'secrets'")->num_rows <= 0){
    $sql = "
        CREATE TABLE `soundify`.`secrets` (`hashed_password` TEXT NOT NULL , `user` JSON NOT NULL , `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , PRIMARY KEY (`id`)) ENGINE = InnoDB;
    ";
    $db->query($sql);
  } 

  return $db;
};
