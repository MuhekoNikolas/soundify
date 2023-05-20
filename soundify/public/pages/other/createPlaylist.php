







<?php 
    include_once(__DIR__."/../imports/phpMainImports.php");

    $GLOBALS["currentPage"] = "explore";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title> <?php out($GLOBALS["CONFIG"]["APP_NAME"]); ?> - Create Playlist</title>

    <?php include_once(__DIR__."/../imports/meta.php"); ?>
    <?php  include_once(__DIR__."/../imports/scriptsAndLinks.php"); ?>

    <link rel="stylesheet" href="/static/pc/css/createSongs.css">
    <link rel="stylesheet" href="/static/mobile/css/createSongs.css">
    <script src="/static/pc/js/createPlaylist.js" defer></script>

    <script>
        SOUNDIFY_CONFIG.currentPage = "createSongs";
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

            <div class="pageContentsWrapper" style="padding-top:50px;">
                <h2 class="pageTitle" style="margin-top:0px;">Create a Playlist</h2>
                <form enctype = "multipart/form-data" onsubmit="return startCreatePlaylistProccess(this)">
                    <label for="playlistName">Name of Playlist:</label>
                    <input type="text" id="playlistName" name="playlistName" placeholder="Enter playlist name" required>

                    <label for="playlistUrl">Image Url:</label>
                    <input type="text" id="playlistImageUrl" name="playlistUrl" placeholder="Enter image url">

                    <label for="playlistImage">Upload Image:</label>
                    <input type="file" id="playlistImageFile" name="playlistImage" accept="image/*" onchange="updatePlaylistImageFileInput(event)">

                    <input type="submit" value="Submit">
                </form>
            </div>

        </div>

    </div>

</body>
</html>