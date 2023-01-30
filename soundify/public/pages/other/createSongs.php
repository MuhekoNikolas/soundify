





<?php 
    include_once(__DIR__."/../imports/phpMainImports.php");

    $GLOBALS["currentPage"] = "explore";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title> <?php out($GLOBALS["CONFIG"]["APP_NAME"]); ?> - Create Song</title>

    <?php include_once(__DIR__."/../imports/meta.php"); ?>
    <?php  include_once(__DIR__."/../imports/scriptsAndLinks.php"); ?>

    <link rel="stylesheet" href="/static/pc/css/createSongs.css">
    <script src="/static/pc/js/createSongs.js" defer></script>

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
                <h2 class="pageTitle" style="margin-top:0px;">Post a Song</h2>
                <form enctype = "multipart/form-data" onsubmit="return startUploadSongProccess(this)">
                    <label for="songName">Name of Song:</label>
                    <input type="text" id="songName" name="songName" placeholder="Enter song name" required>

                    <label for="songUrl">Image Url:</label>
                    <input type="text" id="songImageUrl" name="songUrl" placeholder="Enter image url">

                    <label for="songImage">Upload Image:</label>
                    <input type="file" id="songImageFile" name="songImage" accept="image/*" onchange="updateSongImageFileInput(event)">

                    <label for="songAudio">Upload Audio:</label>
                    <input type="file" id="songAudio" name="songAudio" accept="audio/*,*/ogv" onchange="updateSongAudioFileInput(event)" required>

                    <input type="submit" value="Submit">
                </form>
            </div>

        </div>

    </div>

</body>
</html>