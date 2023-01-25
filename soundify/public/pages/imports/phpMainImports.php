

<?php
    include_once(__DIR__."/../imports/actions.php");

    $userLoggedIn = isLoggedIn();
    
    $pageUrl = $_SERVER["REQUEST_URI"];
    if($pageUrl[strlen($pageUrl)-1]=="/"){
        $pageUrl = substr($pageUrl, 0, strlen($pageUrl)-1);
    }

    $backgroundImages = ["homeSkyBallpng.png","loginGirl.png","profileHeadphone.png","signupBoy.png"];
    $choosenBackground = $backgroundImages[mt_rand(0, count($backgroundImages)-1)];
    $backgroundImagePath = __DIR__."/../../images/$choosenBackground";

    $pageBackgroundImage = getDataLink($backgroundImagePath);
?>