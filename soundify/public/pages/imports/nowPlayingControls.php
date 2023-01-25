

<style>
    soundify-now-playing-controls{
        position:fixed;
        left: var(--sideBarSize);
        bottom:0px;

        width: calc( 100% - var(--sideBarSize) );
        height:100px;

        display:flex;
        justify-content:center;
        align-items:center;
        gap:20px;

        background:var(--hoverColor);;
    }

    .soundifyNowPlayingImage{
        position:relative;

        width:90px;
        height:90px;

        border:2px solid var(--sideBarLightColor);
        border-radius:50px;

        margin-left:20px;

        background-repeat:no-repeat !important;
        background-size:cover !important;
        background-position:center center !important;
    }

    soundify-now-playing-controls.playing .soundifyNowPlayingImage{
        animation:2s .01s infinite spin;
    }

    .soundifyNowPlayingSongInfo{
        display:flex;
        flex-direction:column;

        justify-content:center;
    }

    .soundifyNowPlayingSongInfo *{
        margin:2px;
    }

    .soundifyNowPlayingSongInfo h3{
        font-weight:bold;
    }

    .nowPlayingAudioElement{
        width:600px !important;
    }

    @keyframes spin{
        0%{
            transform:rotate(100deg);
        }
        100%{
            transform:rotate(460deg);
        }
    }

</style>

<?php 
    if(!isset($GLOBALS["nowPlaying"])){ 
        if(array_key_exists("SoundifyNowPlaying", $_COOKIE)){
            $GLOBALS["nowPlaying"] = $_COOKIE["SoundifyNowPlaying"];
        } else {
            die();
            exit;
        }
    } 

    if( json_decode($GLOBALS["nowPlaying"]) == null || isset(json_decode($GLOBALS["nowPlaying"])->played) == false ){die();exit;}

    $nowPlaying = json_decode($GLOBALS["nowPlaying"]);

    $nowPlayingSongFolder = __DIR__."/../../songs/".$nowPlaying->songId;
    if(file_exists($nowPlayingSongFolder) == true){
        $nowPlayingInfo = $nowPlayingSongFolder."/info.json";
        if(file_exists($nowPlayingInfo) == false){die();exit;}
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
                        this.currentTime = <?php out($nowPlaying->played+0.25); ?>;
                        this.play()
                        soundifyNowPlayingCont.classList.add("playing")
                        if(this.paused == true){
                            soundifyNowPlayingCont.classList.remove("playing")
                        }
                        this.oncanplay = null
                    }
                    audioElement.onpaused = ()=>{
                        alert(1)
                    }
                    SOUNDIFY_CONFIG.nowPlaying = audioElement
                    SOUNDIFY_CONFIG.nowPlaying.elClass = new songPreviewTemplate(JSON.parse(`<?php echo(json_encode($nowPlayingInfo)); ?>`));
                })() : (()=>{
                    SOUNDIFY_CONFIG.nowPlaying = null
                })();

                
            </script>

        </soundify-now-playing-controls>

<?php
    } else {
?>
    <script>SOUNDIFY_CONFIG.nowPlaying = null</script>
<?php
    }
?>

