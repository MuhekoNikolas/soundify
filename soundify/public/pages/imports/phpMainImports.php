

<?php
    include_once(__DIR__."/../imports/actions.php");

    $mainDB = initDatabase();
    if($mainDB==null){
        echo "An error occured while initialising the database, please make sure that you set up the app correctly";
        return;
    } else {
        $GLOBALS["mainDB"] = $mainDB;
    }

    $userLoggedIn = isLoggedIn();
    
    $pageUrl = $_SERVER["REQUEST_URI"];

    $backgroundImages = ["homeSkyBallpng.png","loginGirl.png","profileHeadphone.png","signupBoy.png"];
    $pageBackgroundImage = $backgroundImages[mt_rand(0, count($backgroundImages)-1)];
?>