

<?php 

    include_once(__DIR__."/../imports/phpMainImports.php");

    $GLOBALS["currentPage"] = "playlistPage";

    $pagePlaylist = json_decode(json_encode($pagePlaylist));
?>




<!DOCTYPE html>
<html lang="en">
    <head>
        <title> <?php out($GLOBALS["CONFIG"]["APP_NAME"]." - playlists - ".$pagePlaylist->name); ?></title>

        <?php include_once(__DIR__."/../imports/meta.php"); ?>
        <?php  include_once(__DIR__."/../imports/scriptsAndLinks.php"); ?>

        <link rel="stylesheet" href="/static/pc/css/artistPage.css">
        <link rel="stylesheet" href="/static/pc/css/playlistPage.css">

        
        <script>
                SOUNDIFY_CONFIG.currentPage = "playlistPage";

                <?php if($pagePlaylist != null){ ?>
                    SOUNDIFY_CONFIG.pagePlaylist = <?php echo(json_encode($pagePlaylist)); ?>
                <?php } else { ?> 
                    SOUNDIFY_CONFIG.pagePlaylist = false
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
                <div class="pageBackgroundImageDiv" style="background:url(<?php echo $pagePlaylist->image; ?>), url(<?php echo $pageBackgroundImage; ?>);"></div>

                <?php
                    include(__DIR__."/../imports/topBar.php");
                ?>


                <div class="pageContentsWrapper">
                    <div>

                        <div class="playlistPageInfoPreview" style="background:url(<?php out($pagePlaylist->image); ?>);">
                            <div>
                                <h3><?php out($pagePlaylist->name); ?></h3>
                                <a href="/artists/<?php out($pagePlaylist->user); ?>" style="text-decoration:none;"><?php out($pagePlaylist->user); ?></a>
                            </div>
                            <button class="playlistPlayButton" style="display:none;">
                                <i class="fa-solid fa-play"></i>
                            </button>
                        </div>


                        <div class="pageMusicSection">
                            <div id="songsPreviewSection" class="allPlaylistSongs previewSection">
                                <h3 class="pageMusicSectionTitles" style="display:flex; justfiy-content:center; align-items:center;">Playlist tracks</h3>

                            </div>
                            <div id="playlistPreviewSection" class="otherPlaylists previewSection">
                                <h3 class="pageMusicSectionTitles" style="display:flex; justfiy-content:center; align-items:center;">Other Playlists
                                    <?php 
                                        if($userLoggedIn != false){
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