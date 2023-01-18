

<?php

    include_once(__DIR__."/../imports/phpMainImports.php");



?>

<style>
    .topBar{
        position:fixed;
        top:0px;
        left:var(--sideBarSize);

        width: calc( 100% - var(--sideBarSize));
        height:60px;

        z-index:4000;

        display:flex;
        align-items:center;

        background:none;
    }

    .topBar::before{
        position:absolute;
        content:"";

        width:100%;
        height:100%;

        filter:blur(40px);

        background:var(--c1);
    }

    .topBar *{
        text-decoration:none;
    }

    .topBarSiteName{
        position:relative;
        left:20px;

        float:right;

        width:auto; 
        height:30px;

        display:flex;
        justify-content:center;
        align-items:center;

        font-size:2em;

        background:none;

    }

    .topBarSiteName h3{
        max-height:inherit;
    }

    .topBarUserProfile{
        position:relative;
        left:calc( 100% - ( var(--sideBarSize) + 110px) );
        
        width:auto;
        height:50px;

        display:flex;
        align-items:center;
        justify-content:center;

        background:none;
    }

    .topBarUserProfile:hover a h3{
        color:var(--hoverColor) !important;
    }

    .topBarUserProfile *{
        display:flex;
        margin:auto;
    }

    .topBarUserProfilePfp{

        margin-right:10px;

        border:1px solid var(--c2);
        border-radius:50px;

        background:var(--c2);
        background-repeat:no-repeat !important; 
        background-size:cover !important; 
        background-position:center center !important;
    }


</style>

<div class="topBar">
    <div class="topBarSiteName">
        <h3> <?php out($GLOBALS["CONFIG"]["APP_NAME"]); ?> </h3>
    </div>

    <div class="topBarUserProfile">
        <?php 
            if( $userLoggedIn != false ){
                echo "
                    <a href='".$userLoggedIn->profilePage."'>
                        <div class='topBarUserProfilePfp' style='width:50px; height:50px; background:url(".$userLoggedIn->pfp.");'></div>
                        <h3>".$userLoggedIn->username."</h3>
                    </a>
                    <a title='Logout' href='/logout' style='display:flex; justify-content:center; align-items:center; margin:10px; color:var(--hoverColor); font-size:2em;'>
                        <i class='fa-solid fa-right-from-bracket' style='color:var(--hoverColor);'></i>
                    </a>
                ";
            } else {
                echo "
                    <a href='/login' style='font-size:1.5em; text-decoration:none; color:var(--hoverColor);'>Login <i class='fa-solid fa-door-open' style='color:var(--hoverColor);'></i> </a>
                ";
            }
        ?>
    </div>
</div>