


var songsPreviewSection;
var songsToPreview = [];
var mainWrapper;



function mainLoadFunction(){
    mainWrapper = document.querySelector("#wrapper") || document.createElement("div")
    songsPreviewSection = document.querySelector("#songsPreviewSection") || document.createElement("div");

    getLoggedInUserMusic()
    if(SOUNDIFY_CONFIG.pageOwner != null && SOUNDIFY_CONFIG.pageOwner.id != SOUNDIFY_CONFIG.userLoggedIn.id){
        getPageOwnerMusic()
    }

}


function updateNowPlayingActions(){
    alert(SOUNDIFY_CONFIG.nowPlaying)
    try{
        if(typeof SOUNDIFY_CONFIG.nowPlaying == "object" ){

            alert(SOUNDIFY_CONFIG.nowPlaying.songId)
            //SOUNDIFY_CONFIG.nowPlaying
        } else {
            SOUNDIFY_CONFIG.nowPlaying = null
        }
    } catch (err){
        SOUNDIFY_CONFIG.nowPlaying = null
    }
}

function getLoggedInUserMusic(){
    if(SOUNDIFY_CONFIG.userLoggedIn != false){
        loggedInUserId = SOUNDIFY_CONFIG.userLoggedIn.id 
        getUserSongs(loggedInUserId)
        .then(userSongs => {
            SOUNDIFY_CONFIG.userLoggedIn.songs = userSongs
            if(SOUNDIFY_CONFIG.pageOwner!=null && SOUNDIFY_CONFIG.pageOwner.id == SOUNDIFY_CONFIG.userLoggedIn.id){
                SOUNDIFY_CONFIG.userLoggedIn.songs.forEach(song=>{
                    songsPreviewSection.append(song)
                })
            }
        })
    }
}

function getPageOwnerMusic(){
    if(SOUNDIFY_CONFIG.pageOwner != null && SOUNDIFY_CONFIG.pageOwner != false){
        pageOwnerId = SOUNDIFY_CONFIG.pageOwner.id 
        getUserSongs(pageOwnerId)
        .then(userSongs => {
            SOUNDIFY_CONFIG.pageOwner.songs = userSongs
            if(SOUNDIFY_CONFIG.pageOwner.id != SOUNDIFY_CONFIG.userLoggedIn.id){
                SOUNDIFY_CONFIG.pageOwner.songs.forEach(song=>{
                    songsPreviewSection.append(song)
                })
            }
        })
    }
}


function getUserSongs(userId){
    return new Promise((resolve, reject)=>{
        songsToReturn = []
        fetch(`http://localhost/api/artists/${userId}/songs`)
        .then(req=>req)
        .then(req=>{
            if(req.ok){
                data = req.json()
                data.then(userSongs=>{
                    userSongs.forEach(userSong=>{
                        previewTemplate = new songPreviewTemplate(userSong).template();
                        songsToReturn.push(previewTemplate)
                    })
                    resolve(songsToReturn)
                    return
                })
            } else {
                alert("An error occured while fetching the User's songs")
                reject("An error occured")
                return
            }
        })
    })

}



function createNowPlayingContainer(elClass){
    oldNowPlayingContainers = document.querySelectorAll("soundify-now-playing-controls")
    nowPlayingCont = document.createElement("soundify-now-playing-controls")

    if(oldNowPlayingContainers!=null){
        for(cont of oldNowPlayingContainers){
            cont.remove()
        }
    }

    nowPlayingCont.innerHTML += `
            <div class="soundifyNowPlayingImage" style="background:url(${elClass.info.image});"></div>
    `;
    soundifyNowPlayingSongInfo = document.createElement("div")
    soundifyNowPlayingSongInfo.classList.add("soundifyNowPlayingSongInfo")
    soundifyNowPlayingSongInfoName = document.createElement("h3")
    soundifyNowPlayingSongInfoName.innerText = `${elClass.info.name}`
    soundifyNowPlayingSongInfoArtist = document.createElement("h5")
    soundifyNowPlayingSongInfoArtist.innerText = `${elClass.info.artist}`

    soundifyNowPlayingSongInfo.append(soundifyNowPlayingSongInfoName)
    soundifyNowPlayingSongInfo.append(soundifyNowPlayingSongInfoArtist)
    nowPlayingCont.append(soundifyNowPlayingSongInfo)

    thisAudioElement = elClass.songPreviewAudioElement
    thisAudioElement.setAttribute("controls", "")
    thisAudioElement.setAttribute("class", "nowPlayingAudioElement")
    thisAudioElement.setAttribute("id", `${elClass.info.id}`)
    thisAudioElement.style.display = "block"
    nowPlayingCont.append(thisAudioElement)

    nowPlayingCont.classList.add("playing")


    mainWrapper.appendChild(nowPlayingCont)

}

