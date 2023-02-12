Array.prototype.shuffle = function(){
    // Denne metoden spiller av en tilfeldig array, og bruker return på den nye tilfeldige arrayen: Array.shuffle()
    _x_ = this.sort(() => 0.5 - Math.random())
    return _x_
}


Array.prototype.filterSongs = function(toDrop=null){
    // Fjerner duplikater, og spesifikke verdier fra en liste som inneholder forhåndsviste sang objekter
    songsInThis = [] //Used to control duplicates and present them from appearing in the returned value
    _filteredArrayToReturn_ = this.filter((x)=>{
        // Filteret duplikerer og fjerner toDrop elementet fra hele arrayen. (Hvis det er False som blir returnert, betyr det at sangen ikke skal inkluderes i arrayen som blir returnet. Hvis det er True som blir returnert, betyr det at sangen skal inkluderes i arrayen som blir returnet  True means include the song in the returned array)
        if(x.startup==true){
            //Sangen sitt element som blir laget fra en sang som blir oppstartet i  Now-Playing, denne sangen vil bli sett til true. Så vi fjerner denne sangen, fordi det kan være duplikater.
            return false
        }
        
        if(songsInThis.includes(x.info.id)){
            // Hvis sangens id allerede finnes i arrayen songsInThis, så er det en duplikat, så filtrer den ut.
            return false
        } else {
            songsInThis.push(x.info.id)  //Hvis ikke, legg til sangen i arrayen songsInThis
            if(toDrop==null){
                // Hvis toDrop er null, så bare inkluder denne sangen i return arrayen
                return true
            }
            // Hvis ikke, så sjekk om den nåværende arrayen som itererer sin id er den samme som toDrop sin id, hvis ja, return false, ellers return true.
            return x.info.id != toDrop?.info?.id
        }
    })

    return _filteredArrayToReturn_ //Returner den endelige arrayen


}


function redirect(path="/"){
    //Omdiriger mottakeren til den gitte plassen
    window.location = path;
}


function setCookie(name, value, expire=0.1) {
    // Sett en cookie gitt med cookiens navn, value og når cookien utløper
    currentDate = new Date(); // Nåværende dato
    currentDate.setTime(currentDate.getTime() + (expire * 24 * 60 * 60 * 1000)); 
    expires = "expires=" + currentDate.toUTCString();
    document.cookie = name + "=" + value+ "; " + expires + "; path=/"; //Sett cookien
}


function getAllSitePlaylists(){
    // Bruker fetch og returnerer alle tilgjengelige Playlistene på siden
    return new Promise((resolve, reject)=>{
        try{
            fetch("/api/playlists") // Lager en api forespørsel til baksiden for sangene
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
    //Bruker fetch og returnerer alle sangene tilgjengelig på siden
    return new Promise((resolve, reject)=>{
        try{
            fetch("/api/songs") // Lager en api forespørsel til baksiden for sangene
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
    // Fetch alle sangene og playlistene til brukeren som er logget inn, deretter legge dem til til siden og/eller lagre dem til SOUNDIFY_CONFIG variabelen
    if(SOUNDIFY_CONFIG.userLoggedIn != false){
        //Bare gjør dette om burkeren er logget på
        loggedInUserId = SOUNDIFY_CONFIG.userLoggedIn.id 
        getUserSongs(loggedInUserId)
        .then(userSongs => {
            //Lager en api forespørsel til baksiden for sangene
            SOUNDIFY_CONFIG.userLoggedIn.songs = userSongs //Lager sangene som er innhentet
            if(SOUNDIFY_CONFIG.pageOwner!=null && SOUNDIFY_CONFIG.pageOwner.id == SOUNDIFY_CONFIG.userLoggedIn.id){
                SOUNDIFY_CONFIG.userLoggedIn.songs.forEach(song=>{
                    //Når SOUNDIFY_CONFIG.pageOwner ikke er null, det betyr at den siden som er i bruk er en artist side, så hvis eieren av siden er den samme brukeren som er logget inn, så loop gjennom alle innhentet sangene, og legg dem til i avspillingsseksjonen på siden
                    songsPreviewSection.append(song)
                })
            }
        })

        getUserPlaylists(loggedInUserId)
        .then(userPlaylists=>{
            //Lager en api forespørsel til baksiden for playlistene
            SOUNDIFY_CONFIG.userLoggedIn.playlists = userPlaylists
            if(SOUNDIFY_CONFIG.pageOwner!=null && SOUNDIFY_CONFIG.pageOwner.id == SOUNDIFY_CONFIG.userLoggedIn.id){
                SOUNDIFY_CONFIG.userLoggedIn.playlists.forEach(playlist=>{
                    // //Når SOUNDIFY_CONFIG.pageOwner ikke er null, det betyr at den siden som er i bruk er en artist side, så hvis eieren av siden er den samme brukeren som er logget inn, så loop gjennom alle innhentet playlistene, og legg dem til i avspillingsseksjonen på siden
                    playlistPreviewSection.append(playlist)
                });
            }
            
            ((SOUNDIFY_CONFIG.userLoggedIn.playlists).shuffle().slice(0,3)).forEach(playlist=>{
                //Gjør playlistene tilfeldig, og legg dem til i side-baren slik at brukeren som er logget inn kan se 3 av playlistene brukeren har samtidig
                playlistInfo = JSON.parse(playlist.dataset.playlistinfo)
                _sideBarPlaylistLink = createSideBarPlaylistLink(playlistInfo)
                sideBarPlaylistLinks.append(_sideBarPlaylistLink)
            })
        })
    }
}


function getPageOwnerMusic(){
    //Fetch alle av side-eierens (hvis du er på /artists/${artisId} page) sanger og playlist, deretter legg dem til siden og/eller lagre dem til SOUNDIFY_CONFIG variabelen
    if(SOUNDIFY_CONFIG.pageOwner != null && SOUNDIFY_CONFIG.pageOwner != false){
        //Bare gjør dette dersom SOUNDIFY_CONFIG.pageOwner variabelen eksisterer
        pageOwnerId = SOUNDIFY_CONFIG.pageOwner.id 
        getUserSongs(pageOwnerId)
        .then(userSongs => {
            //Lager en api forespørsel til baksiden for sangene
            SOUNDIFY_CONFIG.pageOwner.songs = userSongs //Lagrer de innhentet sangene
            if(SOUNDIFY_CONFIG.pageOwner.id != SOUNDIFY_CONFIG.userLoggedIn.id){
                //Hvis pageOwner og brukeren som er logget inn ikke er den samme, legg til de innhentet sangene til siden. Uten denne sjekken, vil vi ha sanger som er duplikat, fordi de allerede blir lagt til i getLoggedInUserMusic() funksjonen som blir kalt før denne blir det.
                SOUNDIFY_CONFIG.pageOwner.songs.forEach(song=>{
                    songsPreviewSection.append(song)
                })
            }
        })
        
        getUserPlaylists(pageOwnerId)
        .then(userPlaylists=>{
            //Lager en api forespørsel til baksiden for playlistene
            SOUNDIFY_CONFIG.pageOwner.playlists = userPlaylists //Lagrer de innhentet playlistene
            if(SOUNDIFY_CONFIG.pageOwner.id != SOUNDIFY_CONFIG.userLoggedIn.id){
                //Hvis pageOwner og brukeren som er logget inn ikke er den samme, legg til de innhentet playlistene til siden. Uten denne sjekken, vil vi ha playlister som er duplikat, fordi de allerede blir lagt til i getLoggedInUserMusic() funksjonen som blir kalt før denne blir det.
                SOUNDIFY_CONFIG.pageOwner.playlists.forEach(playlist=>{
                    playlistPreviewSection.append(playlist)
                })
            }
        })
    }
}


function getUserSongs(userId){
    //Lager en api forespørsel til baksiden, og return alle sangene til brukeren via user-id
    return new Promise((resolve, reject)=>{
        songsToReturn = [] //Det er et laggerrom som blir brukt for å lagre songPreviewTemplate sangene. Objektene er laget fra sangens informasjon via fetch
        fetch(`/api/artists/${userId}/songs`) //Lager en api forespørsel for sangene, og legger inn userId i url-en
        .then(req=>req)
        .then(req=>{
            if(req.ok){
                // Hvis forespørselen til serveren var vellykket, er responskoden 200
                data = req.json() //Få respons json data Promise
                data.then(userSongs=>{
                    //Loop gjennom alle sangene som har blitt returnet, og gjør dem om til songPreviewTemplate objekter
                    userSongs.forEach(userSong=>{
                        previewTemplate = new songPreviewTemplate(userSong).template();
                        songsToReturn.push(previewTemplate) //Legg til sang-objektene i arrayen songsToReturn
                    })
                    resolve(songsToReturn) //Returner arrayen songsToReturn som Promise-verdien
                    return
                })
            } else {
                //Lar brukeren få vite at forespøreslen ikke var vellykket
                alert("An error occured while fetching the User's songs")
                reject("An error occured")
                return
            }
        })
    })

}


function getUserPlaylists(userId){
    //Lager en api forespørsel til baksiden, og return alle playlistene til brukeren via user-id
    return new Promise((resolve, reject)=>{ 
        playlistsToReturn = [] //Det er et laggerrom som blir brukt for å lagre playlistPreviewTemplate playlistene. Objektene er laget fra playlistenes informasjon via fetch
        fetch(`/api/artists/${userId}/playlists`) //Lager en api forespørsel for playlistene, og legger inn userId i url-en
        .then(req=>req)
        .then(req=>{
            if(req.ok){
                //Hvis forespørselen til serveren var vellykket, er responskoden 200
                data = req.json() //Få respons json data Promise
                data.then(userPlaylists=>{
                    //Loop gjennom alle playlistene som har blitt returnet, og gjør dem om til playlistPreviewTemplate objekter
                    userPlaylists.forEach(userPlaylist=>{
                        previewTemplate = new playlistPreviewTemplate(userPlaylist).template();
                        playlistsToReturn.push(previewTemplate) //Legg til sang-objektene i arrayen playlistToReturn
                    })
                    resolve(playlistsToReturn) //Returner arrayen playlistsToReturn som Promise-verdien
                    return
                })
            } else {
                //Lar brukeren få vite at forespøreslen ikke var vellykket
                alert("An error occured while fetching the User's songs")
                reject("An error occured")
                return
            }
        })
    })
}


function createSideBarPlaylistLink(info){
    //Lager et HTMLelement som representerer en playlist link basert på playlistens informasjon, elementet som blir returnert vil bli lagt til i sidebaren i playlist seksjonen 
    _anchor = document.createElement("a") //Anchor linken som vi vil legge elementet i, slik at man blir omdirigert til playlistens side når den klikkes
    _anchor.style.textDecoration = "none"
    _anchor.setAttribute("href", `/playlists/${info.id}`)
    _anchor.classList.add("sideBarPlaylistLink")
    _cont = document.createElement("h5") //Hovedelementet/Playlistenes navn
    _cont.innerText = info.name

    _anchor.append(_cont) //Legg hoved-elementet med anchor

    return _anchor //Return anchor, som er foreldre til hovedelementet
}



function createTopPlaylistPreview(info){
    //Lager et top list HTMLelement objekt som vil bli lagt til i hjemmesidens Top playlist seksjon
    _anchor = document.createElement("a") //Anchor linken som vi vil legge elementet i, slik at man blir omdirigert til playlistens side når den klikkes
    _anchor.style.textDecoration = "none"
    _anchor.setAttribute("href",`/playlists/${info.id}`)
    _homeTopPlaylist = document.createElement("div") //Hovedelementet: Den inneholder alle Top Playlist's informasjon.
    _homeTopPlaylist.classList.add("homeTopPlaylist")
    _anchor.append(_homeTopPlaylist) //Legg hoved-elementet med _anchor

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

    return _anchor; //Returner anchor som inneholder hoved-Elementet (_homeTopPlaylist)
}


function createNowPlayingContainer(elClass){
    //Lager en plass på bunnen av siden, som viser informasjon om sangen som blir spilt nå

    oldNowPlayingContainers = document.querySelectorAll("soundify-now-playing-controls") //Få alle viste nåværende Now-Playing container, og gjem og fjern dem
    if(oldNowPlayingContainers!=null){
        for(cont of oldNowPlayingContainers){
            cont.remove() //Fjern containerene
        }
    }

    nowPlayingCont = document.createElement("soundify-now-playing-controls") //Containeren som vi vil bruke for Now-Playing baren
    nowPlayingCont.innerHTML += `
            <div class="soundifyNowPlayingImage" style="background:url(${elClass.info.image});"></div>
    `; //Legg til bilde av sangen som avspilles nå
    soundifyNowPlayingSongInfo = document.createElement("div") 
    soundifyNowPlayingSongInfo.classList.add("soundifyNowPlayingSongInfo")
    soundifyNowPlayingSongInfoName = document.createElement("h3")
    soundifyNowPlayingSongInfoName.innerText = `${elClass.info.name}`
    soundifyNowPlayingSongInfoArtist = document.createElement("h5")
    soundifyNowPlayingSongInfoArtist.innerText = `${elClass.info.artist}`
    //Legg til tittel og artisten av sangen som blir spilt av nå
    soundifyNowPlayingSongInfo.append(soundifyNowPlayingSongInfoName)
    soundifyNowPlayingSongInfo.append(soundifyNowPlayingSongInfoArtist)
    nowPlayingCont.append(soundifyNowPlayingSongInfo)

    //Legg til lyd-elementet fra den nåværende spilt av sangen som er innhentet fra class-ens songPreviewAudioElement element.
    thisAudioElement = elClass.songPreviewAudioElement
    thisAudioElement.setAttribute("controls", "")
    thisAudioElement.setAttribute("class", "nowPlayingAudioElement")
    thisAudioElement.setAttribute("id", `${elClass.info.id}`)
    thisAudioElement.style.display = "block"
    nowPlayingCont.append(thisAudioElement)

    //Legg til denne classen, som gjør at bilde blir animert
    nowPlayingCont.classList.add("playing")


    //Legg til knapper som gjør at man kan endre hvilken modus som blir avspilt
    nowPlayingPlayMode = document.createElement("div")
    nowPlayingPlayMode.classList.add("nowPlayingPlayMode")

    nowPlayingPlayModeIconSequence = document.createElement("div") //Denne knappen vil bli brukt til å forandre avspillingsmodusen fra playing-modus til sequencial-mode
    nowPlayingPlayModeIconSequence.classList.add("nowPlayingPlayModeIcon")
    nowPlayingPlayModeIconSequence.style = "background:url(https://cdn0.iconfinder.com/data/icons/multimedia-126/24/205_-_Multimedia_music_list_music_queue_queue_icon-1024.png);"
    nowPlayingPlayModeIconSequence.setAttribute("title","sequence mode")
    nowPlayingPlayModeIconSequence.setAttribute("onclick","sequenceSongQueue()")
    nowPlayingPlayMode.append(nowPlayingPlayModeIconSequence)

    nowPlayingPlayModeIconShuffle = document.createElement("div") //Denne knappen vil shuffle alle sangene som er lagt i kø
    nowPlayingPlayModeIconShuffle.classList.add("nowPlayingPlayModeIcon")
    nowPlayingPlayModeIconShuffle.setAttribute("title","shuffle mode")
    nowPlayingPlayModeIconShuffle.setAttribute("onclick","shuffleSongQueue()")
    nowPlayingPlayModeIconShuffle.innerHTML = '<i class="fa-solid fa-shuffle"></i>'
    nowPlayingPlayMode.append(nowPlayingPlayModeIconShuffle)
    nowPlayingCont.append(nowPlayingPlayMode)

    mainWrapper.append(nowPlayingCont) //Legg til Now-Playing elementet til siden
    return nowPlayingCont; //return nowPlaying elementet slik at classen som lagde den kan utføre flere handlinger med den

}



function shuffleSongQueue(){
    //Shuffler alle sangene som er lagt i kø
    nowPlaying = SOUNDIFY_CONFIG.nowPlaying //Få sangen som blir avspilt nå
    SOUNDIFY_CONFIG.nowPlaying = null

    if(nowPlaying){ //Hvis det er en sang som blir spilt av nå, restart og pause den
        nowPlaying.currentTime = 0
        nowPlaying.pause()
    }

    queriedSongs = SOUNDIFY_CONFIG.queriedSongs
    queriedSongs = queriedSongs.filterSongs(null) //Fjerner duplikat-sangene som er lagt i kø

    queriedSongs = queriedSongs.shuffle() //Shuffle de nåværende filtrerte sangene som er queuet

    SOUNDIFY_CONFIG.queriedSongs = queriedSongs //Lagre sang-køen som den ekte sang-køen

    toPlay = SOUNDIFY_CONFIG.queriedSongs[0] //Få den første sangen av den nye køen, og spill den av hvis den eksisterer
    if(toPlay){
        toPlay.currentTime = 0
        toPlay.play(toPlay)
    }
}


function sequenceSongQueue(){
    //Sorter alle sangene til den nåværende queuen basert på når de ble lastet inn til siden
    nowPlaying = SOUNDIFY_CONFIG.nowPlaying //Få sangen som blir spilt av nå
    SOUNDIFY_CONFIG.nowPlaying = null

    if(nowPlaying){ //Hvis det er en sang som blir spilt av nå, restart og pause den
        nowPlaying.currentTime = 0
        nowPlaying.pause()
    }
 
    queriedSongs = SOUNDIFY_CONFIG.queriedSongs.filterSongs(null) //Fjerner duplikat-sangene som er lagt i kø
    queriedSongs.sort((a,b)=>{
        //Sorterer alle sangene i queuen basert på deres queryIndex property(queryIndex representerer når sangen ble lastet inn til siden)
        if(a.queryIndex < b.queryIndex){
            return -1
        } else {
            return 1
        }
    })

    
    SOUNDIFY_CONFIG.queriedSongs = queriedSongs
    toPlay = queriedSongs[0] //Få den første sangen fra den nye sorterte køen, og spill den av om den eksisterer
    if(toPlay){
        toPlay.play(toPlay)
    }
}
