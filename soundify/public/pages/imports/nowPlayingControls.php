



<?php 
    if(!isset($GLOBALS["nowPlaying"])){ 
        if(array_key_exists("SoundifyNowPlaying", $_COOKIE)){
            $GLOBALS["nowPlaying"] = $_COOKIE["SoundifyNowPlaying"];
        } else {
            return;
        }
    } 

    if( json_decode($GLOBALS["nowPlaying"]) != null && isset(json_decode($GLOBALS["nowPlaying"])->played) != false ){
        $nowPlaying = json_decode($GLOBALS["nowPlaying"]);

        $nowPlayingSongFolder = __DIR__."/../../songs/".$nowPlaying->songId;
        if(file_exists($nowPlayingSongFolder) == true){
            $nowPlayingInfo = $nowPlayingSongFolder."/info.json";
            if(file_exists($nowPlayingInfo) == true){
                $nowPlayingInfo = json_decode(file_get_contents($nowPlayingInfo),false);

?>

            <soundify-now-playing-controls>
                <div class="soundifyNowPlayingImage" style="background:url(<?php echo($nowPlayingInfo->image); ?>);"></div>
                <div class="soundifyNowPlayingSongInfo">
                    <h3><?php echo($nowPlayingInfo->name); ?></h3>
                    <h5><?php echo($nowPlayingInfo->artist); ?></h5>
                </div>
                <script>
                    soundifyNowPlayingCont = document.querySelector("soundify-now-playing-controls")
                    soundifyNowPlayingCont.classList.remove("playing")

                    nowPlayingAudioElement = document.createElement("audio")
                    nowPlayingAudioElement.setAttribute("class", "nowPlayingAudioElement")
                    nowPlayingAudioElement.setAttribute("src", "<?php echo($nowPlayingInfo->audio); ?>")
                    nowPlayingAudioElement.setAttribute("preload", "auto")
                    nowPlayingAudioElement.setAttribute("controls", "")
                    soundifyNowPlayingCont.insertBefore(nowPlayingAudioElement, soundifyNowPlayingCont.lastChild)

                    document.querySelector(".nowPlayingAudioElement") !=null ? (()=>{
                        audioElement = document.querySelector(".nowPlayingAudioElement"); 
                        audioElement.oncanplay  = function(){
                            this.currentTime = <?php out($nowPlaying->played+0.1); ?>;
                            this.play()
                            soundifyNowPlayingCont.classList.add("playing")
                            if(this.paused == true){
                                soundifyNowPlayingCont.classList.remove("playing")
                            }
                            this.oncanplay = null //Stop it from autoplaying because it causes a play-pause loop
                        }

                        SOUNDIFY_CONFIG.nowPlaying = audioElement
                        SOUNDIFY_CONFIG.nowPlaying.elClass = new songPreviewTemplate(JSON.parse(`<?php echo(json_encode($nowPlayingInfo)); ?>`));
                        SOUNDIFY_CONFIG.nowPlaying.elClass.startup = true
                    })() : (()=>{
                        SOUNDIFY_CONFIG.nowPlaying = null
                    })();
                </script>

                <div class="nowPlayingPlayMode">
                    <div class="nowPlayingPlayModeIcon" style="background:url(https://cdn0.iconfinder.com/data/icons/multimedia-126/24/205_-_Multimedia_music_list_music_queue_queue_icon-1024.png);" title="sequence mode" onclick="sequenceSongQueue()"></div>
                    <div class="nowPlayingPlayModeIcon" title="shuffle mode" onclick="shuffleSongQueue()"><i class="fa-solid fa-shuffle"></i></div>
                </div>
            </soundify-now-playing-controls>

<?php
        }
    } else {
?>
    <script>SOUNDIFY_CONFIG.nowPlaying = null</script>
<?php
    }
}
?>

