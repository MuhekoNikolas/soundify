


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
        echo (include(__DIR__."/../other/404.php"));
        exit();
    } else {
        $pageOwner = json_decode($pageOwner[1]);
    }


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title> <?php out($GLOBALS["CONFIG"]["APP_NAME"]." - $pageOwnerName"); ?></title>

        <?php include_once(__DIR__."/../imports/meta.php"); ?>
        <?php  include_once(__DIR__."/../imports/scriptsAndLinks.php"); ?>

        <link rel="stylesheet" href="/static/pc/css/profilePage.css">

        

    </head>
    <body>
        
        <div id="wrapper">

            <?php 
                include(__DIR__."/../imports/sideBar.php");
            ?>

            <div class="pageWrapper" style="display:block !important;">
                <div class="pageBackgroundImageDiv" style="background:url(/images/<?php echo $pageBackgroundImage; ?>);"></div>

                <?php
                    include(__DIR__."/../imports/topBar.php");
                ?>

                <style>
                    .pageContentsWrapper{
                        position:relative;
                        left:0px;

                        width:100%;
                        height:auto;
                        min-height: calc( 100% - 60px );

                        display:block;
                        margin:0px;
                        margin-top:90px;
                    }

                    .artistPageProfilePreview{
                        position:relative;

                        width:90%;
                        height:250px;

                        display:flex;
                        justify-content:start;
                        align-items:center;

                        margin:0px auto;
                        margin-top:60px;
                    }

                    .artistPageProfilePreview::before{
                        position:absolute;
                        content:"";
                        width:100%;
                        height:100%;

                        border-radius:20px;

                        filter:blur(0px);
                        opacity:0.7;

                        background:linear-gradient(40deg, var(--c2Lighter), var(--c1));
                    }

                    .artistPageProfilePreview *{
                        margin-left:80px;
                    }

                    .artistPageProfilePreviewPfp{
                        position:relative;

                        width:200px;
                        height:200px;

                        margin-right:-70px;

                        border:var(--hoverColor);
                        border-radius:50%;

                        background-size:100% 100% !important;
                        background-position:center center !important;
                        background-repeat:no-repeat !important;
                    }

                    .artistPageProfilePreviewName{
                        position:relative;
                        width:auto;
                        height:auto;

                    }

                    .artistPageProfilePreviewName h3{
                        color:var(--white);
                        font-size:3rem;
                    }

                    .artistPageMusicSection{
                        position:relative;
                        top:50px;
                        left:0px;

                        display:grid !important;
                        grid-template-columns:repeat(2, 49% );
                        grid-column-gap:2%;

                        width:90%;
                        min-width:400px;
                        height:auto;
                        min-height:300px;

                        margin:0px auto;
                        padding-bottom:100px;

                        background:none;
                    }

                    .artistPageMusicSection .previewSection{
                        position:relative;
                        top:0px;
                        left:0px;

                        width:auto;
                        height:auto;
                        min-height:inherit;

                        border-radius:20px;

                        background:var(--c2Lighter);

                    }

                    .artistPageMusicSectionTitles{
                        position:relative;

                        text-align:start;
                        text-indent:20px;
                    }

                </style>

                <div class="pageContentsWrapper">
                    <div>
                        <div class="artistPageProfilePreview">
                            <div class="artistPageProfilePreviewPfp" style="background:url(<?php out($pageOwner->pfp); ?>);">
                            </div>
                            <div class="artistPageProfilePreviewName">
                                <h3> <?php out(preg_replace("/(?>=[A-Za-z])\_(?!\_)/is", " ", $pageOwner->username)); ?> </div>
                            </div>
                        </div>

                        <?php 
                            $pageOwner->playLists = array(
                                0 => array()
                            );

                            $pageOwner->songs = /*json_decode($pageOwner->songs);*/array("/../../songs/firstSong/", "/../../songs/secondSong/");

                        ?>

                        <div class="artistPageMusicSection">
                            <div class="allArtistSongs previewSection">
                                <h3 class="artistPageMusicSectionTitles">Artist tracks</h3>

                                <style>
                                    .songPreviewObject{
                                        position:relative;

                                        width: calc( 90% - 20px );
                                        height:70px;

                                        display:flex;
                                        gap:20px;
                                        align-items:center;

                                        border-radius:10px;
                                        margin:0px auto;
                                        margin-bottom:5px;
                                        padding:0px 10px;

                                        background:var(--c2) !important;
                                    }

                                    .songPreviewObjectImage{
                                        position:relative;
                                        width:50px;
                                        height:50px;

                                        border-radius:10px;


                                        background-repeat:no-repeat !important;
                                        background-size:cover !important;
                                        background-position:center center !important;
                                    }

                                    .songPreviewInfo{
                                        position:relative;
                                        top:0px;
                                        left:0px;

                                        width:auto;
                                        min-width:100px;
                                        height:50px;

                                        display:flex;
                                        flex-direction:column;
                                        justify-content:center;
                                        text-align:start;

                                        background:none;
                                    }

                                    .songPreviewInfo *{
                                        margin:0px;
                                    }

                                    .songPreviewActions{
                                        display:flex;
                                    
                                    }

                                    .songPreviewActions div{
                                        margin-left:20px;
                                    }
                                </style>

                                <?php foreach ($pageOwner->songs as $songFolder){ 
                                    $songFolder = __DIR__.$songFolder;
                                    if(is_dir($songFolder)){
                                        $songJsonInfo = json_decode(file_get_contents($songFolder."info.json"));
                                        //echo var_dump($songJsonInfo);
                                        
                                    ?>
                                    <script>
                                        function playAudio(id){
                                            audioPlayer = document.getElementById(id)
                                            if(audioPlayer){
                                                if(audioPlayer.duration > 0 && !audioPlayer.paused){
                                                    audioPlayer.pause()
                                                } else {
                                                    audioPlayer.play()
                                                }
                                            }
                                            console.log(audioPlayer)
                                        }

                                        function addAudioToPlaylist(id){
                                            alert("1")
                                        }
                                    </script>
                                        
                                        <div class="songPreviewObject" data-songInfo="<?php out(json_encode($songJsonInfo));?>"> 
                                            <div class="songPreviewObjectImage" style="background:url(/songs/<?php out($songJsonInfo->folderName) ?>/image.jpeg);"></div>
                                            <div class="songPreviewInfo">
                                                <h4 style="font-weight:bold;"><?php out($songJsonInfo->name); ?></h4>
                                                <p style="color:var(--authorNameColor);"><?php out($songJsonInfo->artist); ?></p]>
                                            </div>      
                                            <div class="songPreviewActions">
                                                <div style="" onclick="playAudio(`<?php out($songJsonInfo->id); ?>`)" title="PLAY"><i class="fa-solid fa-play"></i></div>
                                                <div style="" onclick="addAudioToPlaylist(`<?php out($songJsonInfo->id); ?>`)"> <i class="fa-solid fa-add"></i> </div>
                                            </div>  
                                            <audio id="<?php out($songJsonInfo->id); ?>"  style="display:none;" src="/songs/<?php out($songJsonInfo->folderName) ?>/audios/audio.mp3">                                                                      
                                        </div>
                                <?php
                                    } else {
                                        continue;
                                    };
                                };  ?>

                            </div>
                            <div class="allArtistPlaylists previewSection">
                            <h3 class="artistPageMusicSectionTitles">Artist Playlists</h3>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </body>
</html>