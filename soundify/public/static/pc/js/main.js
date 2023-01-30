



Array.prototype.shuffle = function(){

    //This method shuffles the array and returns the new shuffled array: Array.shuffle()
    _x_ = this.sort(() => 0.5 - Math.random())
    return _x_
}


Array.prototype.filterSongs = function(toDrop=null){
    //Remove duplicates and specific values from an array containg song preview objects
    songsInThis = [] //Used to control duplicates and present them from appearing in the returned value
    _filteredArrayToReturn_ = this.filter((x)=>{
        //Filter duplicates and remove the toDrop element from the entire array. (returning False means dont include the song in the returned array, True means include the song in the returned array)
        if(x.startup==true){
            //The song element created from a song startup Now-Playing song will have this property set to true. So we want to remove this song because there might be duplicates.
            return false
        }
        
        if(songsInThis.includes(x.info.id)){
            //If the song id is already in the songsInThis array, then this is a duplicate so filter it out.
            return false
        } else {
            songsInThis.push(x.info.id)  //If not, then add the song id in the songsInThis array
            if(toDrop==null){
                //If the toDrop argument is null, then just include this song in the return array.
                return true
            }
            //If not then check if the current iterating array value's id is the same as the toDrop's id, if yes then return false else return true.
            return x.info.id != toDrop?.info?.id
        }
    })

    return _filteredArrayToReturn_ //Return the final array


}


function redirect(path="/"){
    //Redirect the user to the given path
    window.location = path;
}


function setCookie(name, value, expire=0.1) {
    //Set a cookie given the cookie name, value and when the cookie should expire
    currentDate = new Date(); // current date
    currentDate.setTime(currentDate.getTime() + (expire * 24 * 60 * 60 * 1000)); 
    expires = "expires=" + currentDate.toUTCString();
    document.cookie = name + "=" + value+ "; " + expires + "; path=/"; //Set the cookie
}


function getAllSitePlaylists(){
    //Fetches and returns all Playlists available on the site
    return new Promise((resolve, reject)=>{
        try{
            fetch("/api/playlists") //Make an api request to the backend for the songs
            .then(req=>{
                if(req.ok){
                    req.json()
                    .then(data=>{
                        resolve(data)
                    })
                } else {
                    reject("Error retrieving the site playlists.")
                }
            })
        } catch(err){
            reject(`An error occured : ${err}`)
        }
    })
}

function getAllSiteSongs(){
    //Fetches and returns all songs available on the site
    return new Promise((resolve, reject)=>{
        try{
            fetch("/api/songs") //Make an api request to the backend for the songs
            .then(req=>{
                if(req.ok){
                    req.json()
                    .then(data=>{
                        resolve(data)
                    })
                } else {
                    reject("Error retrieving the site songs.")
                }
            })
        } catch(err){
            reject(`An error occured : ${err}`)
        }
    })
}


function getLoggedInUserMusic(){
    //Fetch all the logged in user's songs and playlists then add them to the page and-or save them to the SOUNDIFY_CONFIG variable
    if(SOUNDIFY_CONFIG.userLoggedIn != false){
        //Only do this if the user is logged in
        loggedInUserId = SOUNDIFY_CONFIG.userLoggedIn.id 
        getUserSongs(loggedInUserId)
        .then(userSongs => {
            //Make an api request to the backend for the songs
            SOUNDIFY_CONFIG.userLoggedIn.songs = userSongs //Save the retrieved songs
            if(SOUNDIFY_CONFIG.pageOwner!=null && SOUNDIFY_CONFIG.pageOwner.id == SOUNDIFY_CONFIG.userLoggedIn.id){
                SOUNDIFY_CONFIG.userLoggedIn.songs.forEach(song=>{
                    //Whenever SOUNDIFY_CONFIG.pageOwner is not null, that means that the current page is an artist page, so if the owner of this page is the same as the logged in user, then loop through all the retrieved songs and add them to the page's song display section.
                    songsPreviewSection.append(song)
                })
            }
        })

        getUserPlaylists(loggedInUserId)
        .then(userPlaylists=>{
            //Make an api request to the backend for the playlists
            SOUNDIFY_CONFIG.userLoggedIn.playlists = userPlaylists
            if(SOUNDIFY_CONFIG.pageOwner!=null && SOUNDIFY_CONFIG.pageOwner.id == SOUNDIFY_CONFIG.userLoggedIn.id){
                SOUNDIFY_CONFIG.userLoggedIn.playlists.forEach(playlist=>{
                    //Whenever SOUNDIFY_CONFIG.pageOwner is not null, that means that the current page is an artist page, so if the owner of this page is the same as the logged in user, then loop through all the retrieved playlists and add them to the page's playlist display section.
                    playlistPreviewSection.append(playlist)
                });
            }
            
            ((SOUNDIFY_CONFIG.userLoggedIn.playlists).shuffle().slice(0,3)).forEach(playlist=>{
                //Shuffle the playlists and add them to the side-bar so that a logged in user can access 3 of his playlists easily.
                playlistInfo = JSON.parse(playlist.dataset.playlistinfo)
                _sideBarPlaylistLink = createSideBarPlaylistLink(playlistInfo)
                sideBarPlaylistLinks.append(_sideBarPlaylistLink)
            })
        })
    }
}


function getPageOwnerMusic(){
    //Fetch all the page-owner's(if you are on the /artists/${artisId} page) songs and playlists then add them to the page and-or save them to the SOUNDIFY_CONFIG variable
    if(SOUNDIFY_CONFIG.pageOwner != null && SOUNDIFY_CONFIG.pageOwner != false){
        //Only do this if the SOUNDIFY_CONFIG.pageOwner variable exists
        pageOwnerId = SOUNDIFY_CONFIG.pageOwner.id 
        getUserSongs(pageOwnerId)
        .then(userSongs => {
            //Make an api request to the backend for the songs
            SOUNDIFY_CONFIG.pageOwner.songs = userSongs //Save the retrieved songs
            if(SOUNDIFY_CONFIG.pageOwner.id != SOUNDIFY_CONFIG.userLoggedIn.id){
                //If the pageOwner and the logged in user are not the same, then add the retrieved songs to the page. Without this check: we will have duplicate songs because I already add them in the  getLoggedInUserMusic() function thats called before this.
                SOUNDIFY_CONFIG.pageOwner.songs.forEach(song=>{
                    songsPreviewSection.append(song)
                })
            }
        })

        getUserPlaylists(pageOwnerId)
        .then(userPlaylists=>{
            //Make an api request to the backend for the playlists
            SOUNDIFY_CONFIG.pageOwner.playlists = userPlaylists //Save the retrieved playlists
            if(SOUNDIFY_CONFIG.pageOwner.id != SOUNDIFY_CONFIG.userLoggedIn.id){
                //If the pageOwner and the logged in user are not the same, then add the retrieved playlists to the page. Without this check: we will have duplicate playlists because I already add them in the  getLoggedInUserMusic() function thats called before this.
                SOUNDIFY_CONFIG.pageOwner.playlists.forEach(playlist=>{
                    playlistPreviewSection.append(playlist)
                })
            }
        })
    }
}


function getUserSongs(userId){
    //Make an api request to the backend and return all the user's songs given the user-id.
    return new Promise((resolve, reject)=>{
        songsToReturn = [] //Its a store that we will use for storing the songPreviewTemplate song Objects made from the fetched song info.
        fetch(`/api/artists/${userId}/songs`) //Make the api request for the songs and pass in the userId in the url
        .then(req=>req)
        .then(req=>{
            if(req.ok){
                //If the request to the server was successfull and the response code is 200
                data = req.json() //Get the response json data Promise
                data.then(userSongs=>{
                    //Loop through all the returned songs and turn them into songPreviewTemplate objects
                    userSongs.forEach(userSong=>{
                        previewTemplate = new songPreviewTemplate(userSong).template();
                        songsToReturn.push(previewTemplate) //Add the song objects to the songsToReturn array.
                    })
                    resolve(songsToReturn) //Return the songsToReturn array as the Promise value
                    return
                })
            } else {
                //Let the user know if the request wasn't successfull
                alert("An error occured while fetching the User's songs")
                reject("An error occured")
                return
            }
        })
    })

}


function getUserPlaylists(userId){
    //Make an api request to the backend and return all the user's playlists given the user-id.
    return new Promise((resolve, reject)=>{ 
        playlistsToReturn = [] //Its a store that we will use for storing the playlistPreviewTemplate playlist Objects made from the fetched playlist info.
        fetch(`/api/artists/${userId}/playlists`) //Make the api request for the playlists and pass in the userId in the url
        .then(req=>req)
        .then(req=>{
            if(req.ok){
                //If the request to the server was successfull and the response code is 200
                data = req.json() //Get the response json data Promise
                data.then(userPlaylists=>{
                    //Loop through all the returned playlists and turn them into playlistPreviewTemplate objects
                    userPlaylists.forEach(userPlaylist=>{
                        previewTemplate = new playlistPreviewTemplate(userPlaylist).template();
                        playlistsToReturn.push(previewTemplate) //Add the song objects to the playlistsToReturn array.
                    })
                    resolve(playlistsToReturn) //Return the playlistsToReturn array as the Promise value
                    return
                })
            } else {
                //Let the user know if the request wasn't successfull
                alert("An error occured while fetching the User's songs")
                reject("An error occured")
                return
            }
        })
    })
}


function createSideBarPlaylistLink(info){
    //Create an HTMLElement represeting a playlist link given the playlist info, the returned Element will be added to the side bar playlist preview section
    _anchor = document.createElement("a") //The anchor link that we will wrap the element in, so that it redirects to the playlist page when clicked.
    _anchor.style.textDecoration = "none"
    _anchor.setAttribute("href", `/playlists/${info.id}`)
    _anchor.classList.add("sideBarPlaylistLink")
    _cont = document.createElement("h5") //The main element/The playlist's name
    _cont.innerText = info.name

    _anchor.append(_cont) //Wrap the main Element with the anchor

    return _anchor //Return the anchor which is the parent of the main element.
}



function createTopPlaylistPreview(info){
    //Create a Top list HTMLElement Object that will be added to the home page Top Playlists Section
    _anchor = document.createElement("a") //The anchor link that we will wrap the element in, so that it redirects to the playlist page when clicked.
    _anchor.style.textDecoration = "none"
    _anchor.setAttribute("href",`/playlists/${info.id}`)
    _homeTopPlaylist = document.createElement("div") //The main element: it contains all the Top Playlist's info.
    _homeTopPlaylist.classList.add("homeTopPlaylist")
    _anchor.append(_homeTopPlaylist) //Wrap the main element with the _anchor

    _homeTopPlaylistImage = document.createElement("div")
    _homeTopPlaylistImage.classList.add("homeTopPlaylistImage")
    _homeTopPlaylistImage.style = `background:url(${info.image})`
    _homeTopPlaylist.append(_homeTopPlaylistImage)

    _homeTopPlaylistInfo = document.createElement("div")
    _homeTopPlaylistInfo.classList.add("homeTopPlaylistInfo")

    _homeTopPlaylistInfoName = document.createElement("h3")
    _homeTopPlaylistInfoName.classList.add("homeTopPlaylistInfoName")
    _homeTopPlaylistInfoName.innerText = info.name
    _homeTopPlaylistInfo.append(_homeTopPlaylistInfoName)

    _homeTopPlaylistInfoArtist = document.createElement("p")
    _homeTopPlaylistInfoArtist.classList.add("homeTopPlaylistInfoArtist")
    _homeTopPlaylistInfoArtist.innerText = info.user
    _homeTopPlaylistInfo.append(_homeTopPlaylistInfoArtist)

    _homeTopPlaylist.append(_homeTopPlaylistInfo)

    return _anchor; //Return the anchor containing the main Element (_homeTopPlaylist)
}


function createNowPlayingContainer(elClass){
    //Creates a Bar at the bottom of the page that shows information about the current song playing.

    oldNowPlayingContainers = document.querySelectorAll("soundify-now-playing-controls") //Get all the currently shown Now-Playing containers and hide remove them.
    if(oldNowPlayingContainers!=null){
        for(cont of oldNowPlayingContainers){
            cont.remove() //Remove the containers
        }
    }

    nowPlayingCont = document.createElement("soundify-now-playing-controls") //The container that we will use for the Now-Playing bar
    nowPlayingCont.innerHTML += `
            <div class="soundifyNowPlayingImage" style="background:url(${elClass.info.image});"></div>
    `; //Add the image of the currently playing song
    soundifyNowPlayingSongInfo = document.createElement("div") 
    soundifyNowPlayingSongInfo.classList.add("soundifyNowPlayingSongInfo")
    soundifyNowPlayingSongInfoName = document.createElement("h3")
    soundifyNowPlayingSongInfoName.innerText = `${elClass.info.name}`
    soundifyNowPlayingSongInfoArtist = document.createElement("h5")
    soundifyNowPlayingSongInfoArtist.innerText = `${elClass.info.artist}`
    //Add the name and the artist of the currently playing song
    soundifyNowPlayingSongInfo.append(soundifyNowPlayingSongInfoName)
    soundifyNowPlayingSongInfo.append(soundifyNowPlayingSongInfoArtist)
    nowPlayingCont.append(soundifyNowPlayingSongInfo)

    //Add the audio element of the currently playing song retrieved from the parsed class's songPreviewAudioElement property element.
    thisAudioElement = elClass.songPreviewAudioElement
    thisAudioElement.setAttribute("controls", "")
    thisAudioElement.setAttribute("class", "nowPlayingAudioElement")
    thisAudioElement.setAttribute("id", `${elClass.info.id}`)
    thisAudioElement.style.display = "block"
    nowPlayingCont.append(thisAudioElement)

    //Add this class so that the image gets animated.
    nowPlayingCont.classList.add("playing")


    //Add buttons that make it able to change playing modes.
    nowPlayingPlayMode = document.createElement("div")
    nowPlayingPlayMode.classList.add("nowPlayingPlayMode")

    nowPlayingPlayModeIconSequence = document.createElement("div") //This button will be used to change the playing mode to sequencial mode
    nowPlayingPlayModeIconSequence.classList.add("nowPlayingPlayModeIcon")
    nowPlayingPlayModeIconSequence.style = "background:url(https://cdn0.iconfinder.com/data/icons/multimedia-126/24/205_-_Multimedia_music_list_music_queue_queue_icon-1024.png);"
    nowPlayingPlayModeIconSequence.setAttribute("title","sequence mode")
    nowPlayingPlayModeIconSequence.setAttribute("onclick","sequenceSongQueue()")
    nowPlayingPlayMode.append(nowPlayingPlayModeIconSequence)

    nowPlayingPlayModeIconShuffle = document.createElement("div") //This button will shuffle all the queued songs
    nowPlayingPlayModeIconShuffle.classList.add("nowPlayingPlayModeIcon")
    nowPlayingPlayModeIconShuffle.setAttribute("title","shuffle mode")
    nowPlayingPlayModeIconShuffle.setAttribute("onclick","shuffleSongQueue()")
    nowPlayingPlayModeIconShuffle.innerHTML = '<i class="fa-solid fa-shuffle"></i>'
    nowPlayingPlayMode.append(nowPlayingPlayModeIconShuffle)
    nowPlayingCont.append(nowPlayingPlayMode)

    mainWrapper.append(nowPlayingCont) //Add the Now-Playing element to the page
    return nowPlayingCont; //return the nowPlaying element so that the class that created it can perform more actions with it

}



function shuffleSongQueue(){
    //Shuffles all the songs in the current queue
    nowPlaying = SOUNDIFY_CONFIG.nowPlaying //Get the currently playing song
    SOUNDIFY_CONFIG.nowPlaying = null

    if(nowPlaying){ //If there is a currently playing song then restart and pause it
        nowPlaying.currentTime = 0
        nowPlaying.pause()
    }

    queriedSongs = SOUNDIFY_CONFIG.queriedSongs
    queriedSongs = queriedSongs.filterSongs(null) //Remove the duplicate songs in the current queue

    queriedSongs = queriedSongs.shuffle() //Shuffle the current filtered song queue

    SOUNDIFY_CONFIG.queriedSongs = queriedSongs //Save the shuffled song queue as the real song queue

    toPlay = SOUNDIFY_CONFIG.queriedSongs[0] //Get the first song of the new queue and play it if it exists
    if(toPlay){
        toPlay.currentTime = 0
        toPlay.play(toPlay)
    }
}


function sequenceSongQueue(){
    //Sorts all the songs in the current queue based on when they were loaded to the site.
    nowPlaying = SOUNDIFY_CONFIG.nowPlaying //Get the currently playing song
    SOUNDIFY_CONFIG.nowPlaying = null

    if(nowPlaying){ //If there is a currently playing song then restart and pause it
        nowPlaying.currentTime = 0
        nowPlaying.pause()
    }
 
    queriedSongs = SOUNDIFY_CONFIG.queriedSongs.filterSongs(null) //Remove the duplicate songs in the current queue
    queriedSongs.sort((a,b)=>{
        //Sort all the queued songs based on their queryIndex property(queryIndex represents when the song was loaded to the site)
        if(a.queryIndex < b.queryIndex){
            return -1
        } else {
            return 1
        }
    })

    
    SOUNDIFY_CONFIG.queriedSongs = queriedSongs
    toPlay = queriedSongs[0] //Get the first song of the new sorted queue and play it if it exists
    if(toPlay){
        toPlay.play(toPlay)
    }
}

