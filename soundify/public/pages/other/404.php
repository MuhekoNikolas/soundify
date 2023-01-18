

<?php 

    include_once(__DIR__."/../imports/phpMainImports.php");

    $mainDB = initDatabase();
    if($mainDB==null){
        echo "An error occured while initialising the database, please make sure that you set up the app correctly";
    } else {
        $GLOBALS["mainDB"] = $mainDB;
    }

    $userLoggedIn = isLoggedIn();
    $GLOBALS["currentPage"] = "404";


?>


<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title> Soundify - 404 </title>

        <?php
            include(__DIR__."/../imports/meta.php");
            include(__DIR__."/../imports/scriptsAndLinks.php");
        ?>
        

        <style>

            .pageWrapper{
                display:flex;
                justify-content:center;
                align-items:center;
            }
            ._404pageContentBox{
                position:relative;

                width:80%;
                height:300px;

                display:block;
                margin:0px auto;

                background:none;
            }

            ._404pageContentBox h1{
                position:relative;

                color:red;
                font-size:4em;
                font-family:Roboto,Helvetica, sans-serif;
            }
            ._404pageContentBox span{
                color:white;
                font-size:1.5em;
                font-family:Roboto,Helvetica, sans-serif;
            }
        </style>
    </head>

    <body>
        <div id="wrapper">

            <?php 
                include_once (__DIR__."/../imports/sideBar.php");
            ?>

            <div class="pageWrapper">
                <div class="pageBackgroundImageDiv" style="background:url(/images/<?php echo $pageBackgroundImage; ?>);"></div>

                <?php
                    include_once (__DIR__."/../imports/topBar.php");
                ?>

                <div class="_404pageContentBox">
                    <h1>404</h1>
                    <span> The page you were looking for wasnt found... <br>Go to <a href="/">Home</a></span>
               </div>

            </div>

        </div>


    </body>

    
</html>