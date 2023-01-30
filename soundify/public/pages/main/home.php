


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

        <script>
            SOUNDIFY_CONFIG.currentPage = "home";
        </script>

    </head>
    <body onload="mainLoadFunction()">
        
        <div id="wrapper">

        <?php
                include(__DIR__."/../imports/nowPlayingControls.php");
                include(__DIR__."/../imports/sideBar.php");
            ?>

            <div class="pageWrapper">
                <div class="pageBackgroundImageDiv" style="background-image:url(<?php echo $pageBackgroundImage; ?>);"></div>

                <?php
                    include(__DIR__."/../imports/topBar.php");
                ?>

                <div class="pageContentsWrapper">

                    <h3 class="homeGreetingText">
                        <?php if( date("H") < 12 ){ ?>
                            Good morning!
                        <?php } else { ?>
                            Good evening!
                        <?php } ?>
                    </h3>

                    <div class="homeTopPlaylistsSection">
                        <h3 class="homeTopPlaylistsTitle">
                            Top playlists
                        </h3>
                        <div class="homeTopPlaylistsSlide">
                        </div>
                        
                        <div class="pageMusicSection">
                            <div id="songsPreviewSection" class="allSiteSongs previewSection">
                                <h3 class="pageMusicSectionTitles" style="display:flex; justfiy-content:center; align-items:center;">All tracks
                                <?php 
                                    if($userLoggedIn != false){
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
                                <h3 class="pageMusicSectionTitles" style="display:flex; justfiy-content:center; align-items:center;">All Playlists
                                    <?php 
                                        if($userLoggedIn != false ){
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