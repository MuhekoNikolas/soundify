


<?php 

    include_once(__DIR__."/../imports/phpMainImports.php");

    $GLOBALS["currentPage"] = "explore";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title> <?php out($GLOBALS["CONFIG"]["APP_NAME"]); ?> - HOME</title>

        <?php include_once(__DIR__."/../imports/meta.php"); ?>
        <?php  include_once(__DIR__."/../imports/scriptsAndLinks.php"); ?>

        <link rel="stylesheet" href="/static/pc/css/home.css">

        

    </head>
    <body onload="mainLoadFunction()">
        
        <div id="wrapper">

            <?php 
                include(__DIR__."/../imports/sideBar.php");
            ?>

            <div class="pageWrapper">
                <div class="pageBackgroundImageDiv" style="background-image:url(<?php echo $pageBackgroundImage; ?>);"></div>

                <?php
                    include(__DIR__."/../imports/topBar.php");
                ?>

                <style>
                    .homeGreetingText{
                        position:relative;
                        float:right;

                        font-size:40px;

                        display:block;
                        margin-right:50px;
                        margin-bottom:40px;
                    }

                    .homeTopPlaylistsSection{
                        position:relative;

                        width:100%;
                        height:200px;

                        margin-top:40px !important;

                        background:red;
                    }


                </style>

                <div class="pageContentsWrapper">

                    <h3 class="homeGreetingText">
                        Good morning!
                    </h3>

                    <div class="homeTopPlaylistsSection">

                    </div>

                </div>

                <?php
                    include(__DIR__."/../imports/nowPlayingControls.php");
                ?>
            </div>

        </div>

    </body>
</html>