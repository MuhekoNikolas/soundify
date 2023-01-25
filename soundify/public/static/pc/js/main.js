



window.onbeforeunload = function(){
    try{

        if(SOUNDIFY_CONFIG.nowPlaying != null){
            if(SOUNDIFY_CONFIG.nowPlaying instanceof HTMLElement ){
                console.log( typeof SOUNDIFY_CONFIG.nowPlaying )
                if(typeof(SOUNDIFY_CONFIG.nowPlaying.elClass) == "object"){
                    nowPlayingInfo = SOUNDIFY_CONFIG.nowPlaying.elClass.info 
                    playedTime = SOUNDIFY_CONFIG.nowPlaying.currentTime
                    if(typeof(nowPlayingInfo) == "object"){
                        if(nowPlayingInfo.id != null){
                            obj = {
                                played: playedTime,
                                songId: nowPlayingInfo.id
                            }
                            setCookie("SoundifyNowPlaying", JSON.stringify(obj))
                            return
                        }
                    }
        
                }
            }
        } 
    
        setCookie("SoundifyNowPlaying", "")
        return

    } catch (err){
        setCookie("error", err+err.stack)
        return
    }
};


function setCookie(name, value, expire=0.1) {
    currentDate = new Date(); // current date
    currentDate.setTime(currentDate.getTime() + (expire * 24 * 60 * 60 * 1000));
    let expires = "expires=" + currentDate.toUTCString();
    document.cookie = name + "=" + value+ "; " + expires + "; path=/";
}



function redirect(path="/"){
    window.location = path;
}

function addAudioToPlaylist(id){
    alert("1")
}

function updateAudioPreviewAudioTime(audioElement, timeElement){
    audioPlayer = audioElement

    if(isNaN(audioPlayer.duration)){return}
    console.log(`${audioElement} mio ${isNaN(audioPlayer.duration)}`)

    timeElement.innerText = `${((audioPlayer.duration)/60).toFixed()}:${(((audioPlayer.duration)%60).toFixed())}`

}
