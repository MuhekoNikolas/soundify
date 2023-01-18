


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
    <body>
        
        <div id="wrapper">

            <?php 
                include(__DIR__."/../imports/sideBar.php");
            ?>

            <div class="pageWrapper">
                <div class="pageBackgroundImageDiv" style="background:url(/images/<?php echo $pageBackgroundImage; ?>);"></div>

                <?php
                    include(__DIR__."/../imports/topBar.php");
                ?>

            </div>

        </div>

    </body>
</html>