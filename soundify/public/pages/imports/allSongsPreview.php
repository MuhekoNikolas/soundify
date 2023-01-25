
<!-- This file contains the snippets used for generating song preview blocks server side. Its equivalent to the songPreviewTemplate class-->

<?php foreach ($allPageSongs as $songDbInfo){ 

    $songFolder = $songDbInfo[2];

    $songFolder = __DIR__."/../../songs/$songFolder/";

    if(is_dir($songFolder)){
        $songJsonInfo = json_decode(file_get_contents($songFolder."info.json"));
        //echo var_dump($songJsonInfo);
        
    ?>        
        <div class="songPreviewObject" data-songInfo="<?php out(json_encode($songJsonInfo));?>"> 
            <audio id="<?php out($songJsonInfo->id); ?>"  style="display:none;" src="/songs/<?php out($songJsonInfo->folderName) ?>/audios/audio.mp3" preload="metadata"></audio>
            <div class="songPreviewObjectImage" style='background-image:url(<?php out(getDataLink(__DIR__."/../../songs/".$songJsonInfo->folderName."/image.png")) ?>);'></div>
            <div class="songPreviewInfo">
                <h4 style="font-weight:bold;"><?php out($songJsonInfo->name); ?></h4>
                <a href="/artists/<?php out($songJsonInfo->artist); ?>" style="text-decoration:none; color:inherit;"><p style="color:var(--authorNameColor);"><?php out($songJsonInfo->artist); ?></p></a>
            </div>   
            <div id="<?php out($songJsonInfo->id); ?>_totalTime" class="songPreviewTotalTime"><script>updateAudioPreviewAudioTime('<?php out($songJsonInfo->id); ?>')</script></div>   
            <div class="songPreviewActions">
                <div id="<?php out($songJsonInfo->id); ?>_button" style="" onclick="playAudio(`<?php out($songJsonInfo->id); ?>`, button=this)" title="PLAY"><i class="fa-solid fa-play" style="color:green;"></i></div>
                <div style="" onclick="addAudioToPlaylist(`<?php out($songJsonInfo->id); ?>`)"> <i class="fa-solid fa-add"></i> </div>
            </div>                                                                        
        </div>
<?php
    } else {
        continue;
    };
};  ?>