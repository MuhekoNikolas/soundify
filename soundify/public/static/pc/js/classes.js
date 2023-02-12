//More classes in the /pages/imports/scriptsAndLinks.php file


class songPreviewTemplate{
    //The class representing the audio preview boxes shown on the artist's profile and the home page.
    constructor(info){
        this.queryIndex = SOUNDIFY_CONFIG.queriedSongs.length //The index of this audio element based on the creation time, will use it to create the random and sequencial play mode.
        this.info = info
        this.songPreviewObject = document.createElement("soundify-song-preview")
        this.songPreviewObject.classList.add("songPreviewObject")
        this.songPreviewObject.setAttribute("data-songinfo", `${JSON.stringify(this.info)}`)
    
        this.songPreviewAudioElement = document.createElement("audio")
        this.songPreviewAudioElement.setAttribute("id", `${this.info.id}`)
        this.songPreviewAudioElement.style = "display:none;"
        this.songPreviewAudioElement.setAttribute("src", `${this.info.audio}`)
        this.songPreviewAudioElement.setAttribute("preload","metadata") //Preload the audios duration 
        this.songPreviewAudioElement.onended = ()=>{this.playNext(this)} //Play the next song in the queue when this one ends
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
        this.songPreviewAudioElement.onloadedmetadata = ()=>{this.updateAudioPreviewAudioTime()}

        this.songPreviewActions = document.createElement("div")
        this.songPreviewActions.classList.add("songPreviewActions")
        this.songPreviewActions.innerHTML = `
            <div id="${this.info.id}_play_button" style="cursor:pointer;" title="PLAY"><i class="fa-solid fa-play" style="color:green;"></i></div>
            <div id="${this.info.id}_addToPlayList_button" style="cursor:pointer;"> Add to playlist <i class="fa-solid fa-add"></i> </div>
        `;
        this.playButton = this.songPreviewActions.children[0]
        this.addToPlaylistButton = this.songPreviewActions.children[1]
        this.playButton.addEventListener("click", ()=>{this.play(this)})
        this.addToPlaylistButton.addEventListener("click", ()=>{this.addToPlaylistInit(this)})
        this.songPreviewAudioElement.onpause = ()=>{
            this.playButton.title = "PLAY"
            this.playButton.innerHTML = `<i class="fa-solid fa-play" style="color:green;"></i>`
        } 
        this.songPreviewAudioElement.onplay = ()=>{
            this.playButton.title = "PAUSE"
            this.playButton.innerHTML = `<i class="fa-solid fa-pause" style="color:blue;"></i>`
        } 
        
        this.songPreviewObject.append(this.songPreviewActions)

        this.actionsMenuButton = document.createElement("i")
        this.actionsMenuButton.setAttribute("class", "fa-solid fa-ellipsis-v")
        this.actionsMenuButton.style = "cursor:pointer; width:20px; height:20px; text-align:center;"
        this.actionsMenuButton.addEventListener("click", ()=>{this.showMenuActions(this)})
        this.songPreviewObject.append(this.actionsMenuButton)

        this.songActionsMenu = document.createElement("div")
        this.songActionsMenu.classList.add("songPreviewActionsMenu")
        this.songActionsMenu.append(this.addToPlaylistButton)

        this.songPreviewObject.append(this.songActionsMenu)


        SOUNDIFY_CONFIG.queriedSongs.push(this)
    }

    template(){
        return this.songPreviewObject
    }

    updateAudioPreviewAudioTime(){  
        let totalNumberOfSeconds = Math.floor(this.songPreviewAudioElement.duration)
        let hours = parseInt( totalNumberOfSeconds / 3600 );
        let minutes = parseInt( (totalNumberOfSeconds - (hours * 3600)) / 60 );
        let seconds = Math.floor((totalNumberOfSeconds - ((hours * 3600) + (minutes * 60))));
        let result = (minutes < 10 ?  + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds);
        this.songPreviewTotalTime.innerText = result
    }


    showMenuActions(){
        for(_x_ of document.querySelectorAll(".shownSongMenu")){
            if(_x_ != this.songActionsMenu){
                let _x_actionsMenuButton;
                if(_x_.parentNode.children[0].tagName.toLowerCase() == "audio"){
                    _x_actionsMenuButton = _x_.parentNode.children[5]
                } else {
                    _x_actionsMenuButton = _x_.parentNode.children[4]
                }

                if(_x_actionsMenuButton==null){
                    alert("An error occured")
                    continue
                }

                if(_x_actionsMenuButton.tagName.toLowerCase() == "i"){
                    _x_actionsMenuButton.setAttribute("class", "fa-solid fa-ellipsis-v")
                    _x_.classList.remove("shownSongMenu")
                }

            }
        }


        if( this.songActionsMenu.classList.contains("shownSongMenu") ){
            this.songActionsMenu.classList.remove("shownSongMenu")
            this.actionsMenuButton.setAttribute("class", "fa-solid fa-ellipsis-v")
        } else {
            this.songActionsMenu.classList.add("shownSongMenu")
            this.actionsMenuButton.setAttribute("class", "fa-solid fa-x")
        }
    }

    play(elClass){
        //elClass is basically 'this' but using 'this' in an event (onclick) will referer to the window element instead of the class.
        elClass.nowPlayingContainer = createNowPlayingContainer(this)
        elClass.nowPlayingContainer.classList.remove("playing")
        try{
            if(elClass.songPreviewAudioElement){
                if(SOUNDIFY_CONFIG.nowPlaying != null){
                    if(typeof(SOUNDIFY_CONFIG.nowPlaying.getAttribute) != "function"){
                        setCookie("SoundifyNowPlaying", "")
                        SOUNDIFY_CONFIG.nowPlaying = null
                    } else {
                        if(SOUNDIFY_CONFIG.nowPlaying.elClass.info.id != elClass.info.id){
                            SOUNDIFY_CONFIG.nowPlaying.pause()
                            SOUNDIFY_CONFIG.nowPlaying.currentTime = 0
                            SOUNDIFY_CONFIG.nowPlaying.elClass.playButton.title = "PLAY"
                            SOUNDIFY_CONFIG.nowPlaying.elClass.playButton.innerHTML = `<i class="fa-solid fa-play" style="color:green;"></i>`
                            setCookie("SoundifyNowPlaying", "")
                            SOUNDIFY_CONFIG.nowPlaying = null
                        } 
                    } 
                }
    
                if(elClass.songPreviewAudioElement.paused == false){
                    //If the audio preview element is playing then pause
                    elClass.songPreviewAudioElement.pause()
                    elClass.playButton.title = "PLAY"
                    elClass.playButton.innerHTML = `<i class="fa-solid fa-play" style="color:green;"></i>`
                    setCookie("SoundifyNowPlaying", "")
                    SOUNDIFY_CONFIG.nowPlaying = null
                } else {
                    //If the audio is paused then play
                    elClass.songPreviewAudioElement.play()
                    SOUNDIFY_CONFIG.nowPlaying = elClass.songPreviewAudioElement
                    elClass.playButton.title = "PAUSE"
                    elClass.playButton.innerHTML = `<i class="fa-solid fa-pause" style="color:blue;"></i>`
                    elClass.nowPlayingContainer.classList.add("playing")
                    SOUNDIFY_CONFIG.nowPlaying.elClass = elClass
                }
            }
        } catch(err){
            console.log(err)
        }
    }    

    playNext(elClass){
        //elClass is basically 'this' but using 'this' in an event (onclick) will referer to the window element instead of the class.
        if(elClass.songPreviewAudioElement.paused == false){
            //If this class's audio element is not paused then pause it by running the .play method
            elClass.play(elClass)
        }

        SOUNDIFY_CONFIG.nowPlaying = null
        elClass.nextSong = SOUNDIFY_CONFIG.queriedSongs[SOUNDIFY_CONFIG.queriedSongs.indexOf(elClass)+1] 
        if(elClass.nextSong != null){
            elClass.nextSong.play(elClass.nextSong)
        }
    }

    addToPlaylistInit(elClass){
        //elClass is basically 'this' but using 'this' in an event (onclick) will referer to the window element instead of the class.
        document.querySelectorAll(".addToPlaylistWrapper").forEach(_X_=>{
            console.log(_x_)
            _X_.remove()
        })

        if(SOUNDIFY_CONFIG.userLoggedIn == false){
            redirect("/login")
        }

        let _addToPlaylistWrapper = document.createElement("div")
        _addToPlaylistWrapper.classList.add("addToPlaylistWrapper")

        let _addToPlaylistsWrapperCloseButton = document.createElement("div")
        _addToPlaylistsWrapperCloseButton.classList.add("addToPlaylistsWrapperCloseButton")
        _addToPlaylistsWrapperCloseButton.setAttribute("onclick", "this.parentNode.remove()")
        _addToPlaylistsWrapperCloseButton.innerText = "X"
        _addToPlaylistWrapper.append(_addToPlaylistsWrapperCloseButton)

        let _addToPlaylistsWrapperTitle = document.createElement("h3")
        _addToPlaylistsWrapperTitle.classList.add("addToPlaylistsWrapperTitle")
        _addToPlaylistsWrapperTitle.innerHTML = "Add <span></span> to playlist!"
        let _addToPlaylistsWrapperTitleSpan = _addToPlaylistsWrapperTitle.children[0]
        _addToPlaylistsWrapperTitleSpan.innerText = elClass.info.name
        _addToPlaylistWrapper.append(_addToPlaylistsWrapperTitle)

        let _addToPlaylistsGridContainer = document.createElement("div")
        _addToPlaylistsGridContainer.classList.add("addToPlaylistsGridContainer")
        if(SOUNDIFY_CONFIG.userLoggedIn.playlists.length <= 0){
            let _noPlaylistsError = document.createElement("p")
            _noPlaylistsError.innerHTML = `You dont have any playlists <a href='/artists/${SOUNDIFY_CONFIG.userLoggedIn.username}/playlists/new'>Create one</a>`
            _addToPlaylistsGridContainer.append(_noPlaylistsError)
        } else {
            for(let userPlaylist of SOUNDIFY_CONFIG.userLoggedIn.playlists){
                let playListInfo = JSON.parse(userPlaylist.dataset.playlistinfo)
                let _addToPlaylistPreview = document.createElement("div")
                _addToPlaylistPreview.classList.add("addToPlaylistPreview")
                _addToPlaylistPreview.setAttribute("data-playlistinfo", JSON.stringify(playListInfo))
                _addToPlaylistPreview.onclick = ()=>{this.addSongToPlaylist(playlistInfo=playListInfo)}
                _addToPlaylistPreview.style.cursor = "pointer"

                let _addToPlaylistPreviewImage = document.createElement("div")
                _addToPlaylistPreviewImage.classList.add("addToPlaylistPreviewImage")
                _addToPlaylistPreviewImage.style.background = `url(${playListInfo.image})`
                _addToPlaylistPreview.append(_addToPlaylistPreviewImage)

                let _addToPlaylistPreviewName = document.createElement("h3")
                _addToPlaylistPreviewName.classList.add("addToPlaylistPreviewName")
                _addToPlaylistPreviewName.innerText = playListInfo.name
                _addToPlaylistPreview.append(_addToPlaylistPreviewName)

                _addToPlaylistsGridContainer.append(_addToPlaylistPreview)


            }
        }
        _addToPlaylistWrapper.append(_addToPlaylistsGridContainer)

        mainWrapper.append(_addToPlaylistWrapper)
    }

    addSongToPlaylist(playlistInfo){
        let data = {
            songInfo: this.info
        }
        let options = {
            "method":"POST",
            "headers":{
                "Content-Type":"application/json"
            },
            "body":JSON.stringify(data)
        }

        fetch(`/api/playlists/${playlistInfo.id}/songs/add`, options)
        .then(req=>{
            if(req.ok){
                req.json().then(data=>{
                    if(data.success == true){
                        Toastify({
                            text: data.message,
                            style: {
                              background: "linear-gradient(to right, #00b09b, var(--c2))",
                            }
                        }).showToast();
                        return
                    } else {
                        Toastify({
                            text: `Error while adding this song to this playlist: ${data.message}`,
                            style: {
                              background: "linear-gradient(to right, red, var(--c2))",
                            }
                        }).showToast();
                        return
                    }
                })
            } else {
                Toastify({
                    text: "Error while adding this song to this playlist",
                    style: {
                      background: "linear-gradient(to right, red, var(--c2))",
                    }
                }).showToast();
                return
            }
        })

    }

}


class playlistPreviewTemplate{
    constructor(info){
        this.info = info
        this.playlistPreviewObject = document.createElement("soundify-playlist-preview")
        this.playlistPreviewObject.classList.add("playlistPreviewObject")
        this.playlistPreviewObject.setAttribute("onclick",`redirect('/playlists/${this.info.id}')`)
        this.playlistPreviewObject.setAttribute("data-playlistinfo", `${JSON.stringify(this.info)}`)

        this.playlistPreviewObject.style.setProperty("--playlistBackgroundImage", `url(${this.info.image})`)
        this.playlistPreviewObject.setAttribute("data-playlistName", `${this.info.name}`)
    }

    template(){
        return this.playlistPreviewObject
    }

}


class pagePlaylistClass{
    constructor(info){
        this.info = info
        this.songs = []
        this.songIds = []
        this.lastPlayedSong = null
        this.playButton = document.querySelector(".playlistPlayButton") || document.createElement("div")

        this.playButton.onclick = ()=>{
            this.play()
        }

        
        fetch(`/api/playlists/${this.info.id}/songs`)
        .then(req=>{
            if(req.ok){
                req.json()
                .then(pagePlaylistSongs=>{
                    pagePlaylistSongs = pagePlaylistSongs.reverse()
                    if(pagePlaylistSongs.length<=0){
                        _x_ = document.createElement("p")
                        _x_.style.textAlign = "center"
                        _x_.style.color = "var(--hoverColor)"
                        _x_.innerText = "Nothing to see here..."
                        songsPreviewSection.append(_x_)
                    }
                    for(let pagePlaylistSongInfo of pagePlaylistSongs){
                        let pagePlaylistSong = new songPreviewTemplate(pagePlaylistSongInfo)
                        if(SOUNDIFY_CONFIG.userLoggedIn != false && SOUNDIFY_CONFIG.userLoggedIn.username == SOUNDIFY_CONFIG.pagePlaylist.user){
                            let thisSong_removeFromPlaylistButton = document.createElement("div")                    
                            thisSong_removeFromPlaylistButton.innerHTML = `Remove <i class="fa-solid fa-x"></i>`
                    
                            thisSong_removeFromPlaylistButton.addEventListener("click", ()=>{this.removeSongFromPlaylist(this, pagePlaylistSongInfo.id)})
                            pagePlaylistSong.songActionsMenu.append(thisSong_removeFromPlaylistButton)
                            console.log(pagePlaylistSong.songActionsMenu)
                        }


                        pagePlaylistSong.songPreviewAudioElement.addEventListener("ended", ()=>{
                            this.playButton.innerHTML = '<i class="fa-solid fa-play"></i>'
                            this.playButton.style.background = "green"
                        })
                        pagePlaylistSong.songPreviewAudioElement.addEventListener("pause", ()=>{
                            this.playButton.innerHTML = '<i class="fa-solid fa-play"></i>'
                            this.playButton.style.background = "green"
                        })
            
                        pagePlaylistSong.songPreviewAudioElement.addEventListener("play", ()=>{
                            this.playButton.innerHTML = '<i class="fa-solid fa-pause"></i>'
                            this.playButton.style.background = "blue"
                        })

                        this.songs.push(pagePlaylistSong)
                        this.songIds.push(pagePlaylistSong.info.id)
                        songsPreviewSection.append(pagePlaylistSong.template())
                    }
                    this.playButton.style.display = "block"
                })
            } else {
                Toastify({
                    text: "Error fetching this playlists's songs",
                    style: {
                      background: "linear-gradient(to right, red, var(--c2))",
                    }
                  }).showToast();
            }
        })
    }

    removeSongFromPlaylist(elClass=this, songId){
        if(elClass.info.id != null && songId != null){
            let options = {
                method: "POST",
                body: JSON.stringify({songId:songId}),
                headers: {
                    "Content-Type": "application/json"
                }
            }


            fetch(`/api/playlists/${elClass.info.id}/songs/remove`, options)
            .then(req=>{
                req.json().then(data=>{
                    if(data.success == true){
                        location.reload();
                    } else {
                        Toastify({
                            text: `Error removing song from playlist: ${data.message}`,
                            style: {
                              background: "linear-gradient(to right, red, var(--c2))",
                            }
                          }).showToast();
                    }
                })
            })
        }
    }

    play(){
        let nowPlaying = SOUNDIFY_CONFIG.nowPlaying
        if(SOUNDIFY_CONFIG.queriedSongs.sort((a,b)=>{if(a.queryIndex>b.queryIndex){return 1} else {return -1}}) != this.songs){
            SOUNDIFY_CONFIG.queriedSongs = this.songs
        }

        let songToPlay = this.songs[0]

        if(nowPlaying == null){
            if(this.lastPlayedSong){
                songToPlay = this.lastPlayedSong
            }

            if(songToPlay){
                songToPlay.play(songToPlay)
                this.lastPlayedSong = songToPlay
                return
            }
        } else {

            if( this.songIds.includes(nowPlaying.elClass.info.id) == true){
                if(nowPlaying.elClass.startup!=true){
                    songToPlay = nowPlaying.elClass
                }

                if(songToPlay){
                    songToPlay.play(songToPlay)
                    this.lastPlayedSong = songToPlay
                    return
                }

            } else {
                if(songToPlay){
                    songToPlay.play(songToPlay)
                    this.lastPlayedSong = songToPlay
                    return
                }
            }
        }


    }

}


