


<?php

    include_once(__DIR__."/../imports/phpMainImports.php");

    if(!isset($GLOBALS["mainDB"])){
        $mainDB= initDatabase();

        if($mainDB==null){
            echo "An error occured while initialising the database, please make sure that you set up the app correctly";
        } else {
            $GLOBALS["mainDB"] = $mainDB;
        }
    }

    $userLoggedIn = isLoggedIn();
?>



<style>


    .sideBarAppName{
        position:relative;

        font-size:3em;
        text-align:center;

        display:block;
        margin-top:90px;

        background:none;
    }

    .sideBarSection{
        position:relative;

        width:300px;
        height:auto;
        min-height:200px;

        margin:0px auto;

        text-indent:40px;

        background:none;
    }

    .sideBarSection * :not(.sideBarNavigationSectionLink){
        margin:10px;
    }

    .sideBarSectionTitle{
        position:relative;
        top:10px;

        font-size:25px;
        color:var(--sideBarTitleColor);
        text-indent:30px;
    }

    .sideBarNavigationSectionLink{

        color:var(--sideBarLightColor);
        font-size:20px;
        text-decoration:none;


        display:flex;
    }

    .sideBarNavigationSectionLink::after {
        position:absolute;
        transform: translate(0px, 15px);
        right:70px;

        content:"";

        width:10px;
        height:10px;


        border-radius:50px;
        display:none;

        background:var(--hoverColor);
    }

    .sideBarNavigationSectionLink:hover{
        color:var(--hoverColor);
    }

    .sideBarNavigationSectionLink:hover::after{
            display:block;
    }

    .sideBarNavigationSectionLink i{
        --iconSize: 20px;

        position:relative;

        color:var(--hoverColor);
        font-size:var(--iconSize);

        margin-right:-30px !important;

        
        display:flex;
        justify-content:center;
        align-items:center;

    }

    .fa-plus::before{
        position:relative;
        left:-150%;

    }
</style>

<div class="sideBar">
    <h2 class="sideBarAppName"><?php out($GLOBALS["CONFIG"]["APP_NAME"]); ?></h2>

    <div class="sideBarSection sideBarNavigationSection">
        <h3 class="sideBarSectionTitle">Navigation</h3>

        <?php
            $GLOBALS["currentPage"]=="explore" ? $exploreLinkColor = "var(--hoverColor)" : $exploreLinkColor = "inherit";
        
            echo "
            <a href='/' class='sideBarNavigationSectionLink'>
                <i class='fa-brands fa-hotjar' style='color:$exploreLinkColor;'></i> 
                <p style='color:$exploreLinkColor;'> Explore </p>
            </a>
            ";
            

            if($userLoggedIn == false){
                $GLOBALS["currentPage"]=="login" ? $loginLinkColor = "var(--hoverColor)" : $loginLinkColor = "inherit";
                $GLOBALS["currentPage"]=="signup" ? $signupLinkColor = "var(--hoverColor)" : $signupLinkColor = "inherit";
                
                echo "
                <a href='/login' class='sideBarNavigationSectionLink'>
                    <i class='fa-solid fa-door-open' style='color:$loginLinkColor;'></i> 
                    <p style='color:$loginLinkColor;'> Login </p>
                </a>
                <a href='/signup' class='sideBarNavigationSectionLink'>
                    <i class='fa-solid fa-user-plus' style='color:$signupLinkColor;'></i> 
                    <p style='color:$signupLinkColor;'> Signup </p>
                </a>
                ";
            } else {
                $GLOBALS["currentPage"]=="myPage" ? $myPageLinkColor = "var(--hoverColor)" : $myPageLinkColor = "inherit";
                echo "
                <a href='".$userLoggedIn->profilePage."' class='sideBarNavigationSectionLink'>
                    <i class='fa-solid fa-home' style='color:$myPageLinkColor;'></i> 
                    <p style='color:$myPageLinkColor;'> My Page </p>
                </a>
                <a href='/logout' class='sideBarNavigationSectionLink'>
                    <i class='fa-solid fa-right-from-bracket' style='color:red;'></i> 
                    <p style='color:red;'> Logout </p>
                </a>
                ";
            }
        ?>

        <div class="sideBarSection sideBarNavigationSection">
            <h3 class="sideBarSectionTitle" style="display:flex; justfiy-content:center; align-items:center;">Playlists 
                <?php 
                    if($userLoggedIn != false){
                        echo "
                        <div onclick='redirect(\"/playlists/new\")' style='display:flex; justify-content:center; margin-left:40px; align-items:center;'>
                            <i class='fa-solid fa-plus' style='width:20px; box-sizing:border-box; color:var(--hoverColor);'></i>
                        </div>
                        ";
                    }
                ?>
            </h3>
        </div>

    </div>



</div>
