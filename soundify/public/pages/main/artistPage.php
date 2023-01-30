


<?php 

    include_once(__DIR__."/../imports/phpMainImports.php");

    $pageOwnerName = preg_replace("/\/(.)+\//is", "", $pageUrl);

    if($userLoggedIn != false){

        if($pageOwnerName == $userLoggedIn->username){
            $GLOBALS["currentPage"] = "myPage";
        } else {
            $GLOBALS["currentPage"] = "userPage";
        }
    } else{
        $GLOBALS["currentPage"] = "userPage";
    }

    
    $pageOwner = getUser($pageOwnerName);

    if($pageOwner == null){
        http_response_code(404);
        include(__DIR__."/../other/404.php");
        exit();
    }

    


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title> <?php out($GLOBALS["CONFIG"]["APP_NAME"]." - $pageOwnerName"); ?></title>

        <?php include_once(__DIR__."/../imports/meta.php"); ?>
        <?php  include_once(__DIR__."/../imports/scriptsAndLinks.php"); ?>

        <link rel="stylesheet" href="/static/pc/css/artistPage.css">

        
        <script>
                SOUNDIFY_CONFIG.currentPage = "userPage";

                <?php if($pageOwner != false){ ?>
                    SOUNDIFY_CONFIG.pageOwner = <?php echo(json_encode($pageOwner)); ?>
                <?php } else { ?> 
                    SOUNDIFY_CONFIG.pageOwner = false
                <?php } ?>

        </script>

    </head>
    <body onload="mainLoadFunction()">
        
        <div id="wrapper">

            <?php
                include(__DIR__."/../imports/nowPlayingControls.php");
                include(__DIR__."/../imports/sideBar.php");
            ?>

            <div class="pageWrapper" style="display:block !important;">
                <div class="pageBackgroundImageDiv" style="background:url(<?php echo $pageBackgroundImage; ?>);"></div>

                <?php
                    include(__DIR__."/../imports/topBar.php");
                ?>


                <div class="pageContentsWrapper">
                    <div>
                        <div class="artistPageProfilePreview">
                            <div class="artistPageProfilePreviewPfp" style="background:url(<?php out($pageOwner->pfp); ?>);">
                            </div>
                            <div class="artistPageProfilePreviewName">
                                <h3> <?php out(preg_replace("/(?>=[A-Za-z])\_(?!\_)/is", " ", $pageOwner->username)); ?> </div>
                            </div>
                        </div>


                        <div class="pageMusicSection">
                            <div id="songsPreviewSection" class="allArtistSongs previewSection">
                                <h3 class="pageMusicSectionTitles" style="display:flex; justfiy-content:center; align-items:center;">Artist tracks
                                <?php 
                                    if($userLoggedIn != false && $userLoggedIn->id == $pageOwner->id ){
                                        echo "
                                            
                                                <div onclick='redirect(`/artists/". $userLoggedIn->username."/songs/new`)' style='display:flex; justify-content:center; margin-left:40px; align-items:center;'>
                                                    <i class='fa-solid fa-plus' style='width:20px; box-sizing:border-box; color:var(--hoverColor);'></i>
                                                </div>
                                            
                                        ";
                                    }
                                ?>
                                </h3>

                            </div>
                            <div id="playlistPreviewSection" class="allArtistPlaylists previewSection">
                                <h3 class="pageMusicSectionTitles" style="display:flex; justfiy-content:center; align-items:center;">Artist Playlists
                                    <?php 
                                        if($userLoggedIn != false && $userLoggedIn->id == $pageOwner->id ){
                                            echo "
                                                
                                                    <div onclick='redirect(`/artists/". $userLoggedIn->username."/playlists/new`)' style='display:flex; justify-content:center; margin-left:40px; align-items:center;'>
                                                        <i class='fa-solid fa-plus' style='width:20px; box-sizing:border-box; color:var(--hoverColor);'></i>
                                                    </div>
                                                
                                            ";
                                        }
                                    ?>
                                </h3>
                                <div class="gridContainer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </body>
</html>