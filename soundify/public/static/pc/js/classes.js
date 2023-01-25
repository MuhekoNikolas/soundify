


class songPreviewTemplate{
    //The class representing the audio preview boxes shown on the artist's profile and the home page.
    constructor(info){
        this.queryIndex = SOUNDIFY_CONFIG.queriedSongs.length //The index of this audio element in the songs query

        this.info = info
        this.songPreviewObject = document.createElement("soundify-song-preview")
        this.songPreviewObject.classList.add("songPreviewObject")
        this.songPreviewObject.setAttribute("data-songInfo", `${JSON.stringify(this.info)}`)
    
        this.songPreviewAudioElement = document.createElement("audio")
        this.songPreviewAudioElement.setAttribute("id", `${this.info.id}`)
        this.songPreviewAudioElement.style = "display:none;"
        this.songPreviewAudioElement.setAttribute("src", `${this.info.audio}`)
        this.songPreviewAudioElement.setAttribute("preload","metadata") //Preload the audios duration 
        this.songPreviewAudioElement.onended = ()=>{if(this.songPreviewAudioElement.currentTime == this.songPreviewAudioElement.duration ){this.play(this);this.playNext(this)}} //Play the next song in the queue when this one ends
        this.songPreviewObject.append(this.songPreviewAudioElement)
    
        this.songPreviewObjectImage = document.createElement("div");
        this.songPreviewObjectImage.classList.add("songPreviewObjectImage")
        this.songPreviewObjectImage.style = `background-image:url(${this.info.image});`
        this.songPreviewObject.append(this.songPreviewObjectImage)
    
        this.songPreviewInfo = document.createElement("div")
        this.songPreviewInfo.classList.add("songPreviewInfo")
        this.songPreviewInfoH4 = document.createElement("h4")
        this.songPreviewInfoH4.style = "font-weight:bold;"
        this.songPreviewInfoH4.innerText = `${this.info.name}`
        this.songPreviewInfo.append(this.songPreviewInfoH4)
        this.songPreviewInfoA = document.createElement("a")
        this.songPreviewInfoA.setAttribute("href", `/artists/${this.info.artist}`)
        this.songPreviewInfoA.style = "text-decoration:none; color:inherit;"
        this.songPreviewInfoA.innerHTML = `<p style="color:var(--authorNameColor);">${this.info.artist}</p]>`
        this.songPreviewInfo.append(this.songPreviewInfoA)
        this.songPreviewObject.append(this.songPreviewInfo)
    
        this.songPreviewTotalTime = document.createElement("div")
        this.songPreviewTotalTime.setAttribute("id", `${this.info.id}_totalTime`)
        this.songPreviewTotalTime.classList.add("songPreviewTotalTime")
        this.songPreviewObject.append(this.songPreviewTotalTime)
        this.songPreviewAudioElement.onloadedmetadata = ()=>{updateAudioPreviewAudioTime(this.songPreviewAudioElement, this.songPreviewTotalTime)}
    
        this.songPreviewActions = document.createElement("div")
        this.songPreviewActions.classList.add("songPreviewActions")
        this.songPreviewActions.innerHTML = `
            <div id="${this.info.id}_play_button" style="cursor:pointer;" title="PLAY"><i class="fa-solid fa-play" style="color:green;"></i></div>
            <div id="${this.info.id}_addToPlayList_button" style="cursor:pointer;"> <i class="fa-solid fa-add"></i> </div>
        `;
        this.playButton = this.songPreviewActions.children[0]
        this.addToPlaylistButton = this.songPreviewActions.children[1]
        this.playButton.addEventListener("click", ()=>{this.play(this)})
        this.addToPlaylistButton.addEventListener("click", ()=>{this.addToPlaylistInit(this)})

        this.songPreviewObject.append(this.songPreviewActions)

        SOUNDIFY_CONFIG.queriedSongs.push(this)
    }

    template(){
        return this.songPreviewObject
    }


    play(elClass){
        createNowPlayingContainer(this)
        try{
            if(elClass.songPreviewAudioElement){
                console.log(SOUNDIFY_CONFIG.nowPlaying )
                if(SOUNDIFY_CONFIG.nowPlaying != null){
                    if(typeof(SOUNDIFY_CONFIG.nowPlaying.getAttribute) != "function"){
                        SOUNDIFY_CONFIG.nowPlaying = null
                    } else {
                        console.log(1)
                        if(SOUNDIFY_CONFIG.nowPlaying.getAttribute("id") != elClass.songPreviewAudioElement.getAttribute("id")){
                            SOUNDIFY_CONFIG.nowPlaying.pause()
                            SOUNDIFY_CONFIG.nowPlaying.currentTime = 0
                            SOUNDIFY_CONFIG.nowPlaying.elClass.playButton.title = "PLAY"
                            SOUNDIFY_CONFIG.nowPlaying.elClass.playButton.innerHTML = `<i class="fa-solid fa-play" style="color:green;"></i>`
                            SOUNDIFY_CONFIG.nowPlaying = null
                        }
                    } 
                }
    
                if(elClass.songPreviewAudioElement.paused == false){
                    //If the audio preview element is playing then pause
                    elClass.songPreviewAudioElement.pause()
                    elClass.playButton.title = "PLAY"
                    elClass.playButton.innerHTML = `<i class="fa-solid fa-play" style="color:green;"></i>`

                    SOUNDIFY_CONFIG.nowPlaying = null
                } else {
                    //If the audio is paused then play
                    elClass.songPreviewAudioElement.play()
                    SOUNDIFY_CONFIG.nowPlaying = elClass.songPreviewAudioElement
                    elClass.playButton.title = "PAUSE"
                    elClass.playButton.innerHTML = `<i class="fa-solid fa-pause" style="color:blue;"></i>`

                    SOUNDIFY_CONFIG.nowPlaying.elClass = elClass
                    console.log(SOUNDIFY_CONFIG.nowPlaying.elClass.info.id)
                }
            }
        } catch(err){
            throw(err)
        }
    }


    addToPlaylistInit(elClass){
        alert(elClass)
    }

    playNext(elClass){
        if(elClass.songPreviewAudioElement.paused == false){
            //If this class's audio element is not paused then pause it by running the .play method
            elClass.play(elClass)
        }

        SOUNDIFY_CONFIG.nowPlaying = null
        elClass.nextSong = SOUNDIFY_CONFIG.queriedSongs[elClass.queryIndex+1] 
        if(elClass.nextSong != null){
            elClass.play(elClass)
            elClass.nextSong.play(elClass.nextSong)
        }
    }

}


