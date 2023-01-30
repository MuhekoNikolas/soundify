


var songsPreviewSection;
var playlistPreviewSection;

var sideBarPlaylistLinks;
var topPlaylistsSlide;

var songsToPreview = [];
var mainWrapper;


function mainLoadFunction(){
    mainWrapper = document.querySelector("#wrapper") || document.createElement("div")
    sideBarPlaylistLinks = document.querySelector(".sideBarSection .sideBarPlaylistsSection") || document.createElement("div")
    topPlaylistsSlide = document.querySelector(".homeTopPlaylistsSlide") || document.createElement("div")

    songsPreviewSection = document.querySelector("#songsPreviewSection") || document.createElement("div");
    playlistPreviewSection = document.querySelector("#playlistPreviewSection .gridContainer") || document.createElement("div")

    getLoggedInUserMusic()

    if(SOUNDIFY_CONFIG.pageOwner != null && SOUNDIFY_CONFIG.pageOwner.id != SOUNDIFY_CONFIG.userLoggedIn.id){
        getPageOwnerMusic()
    }

    getAllSitePlaylists()
    .then(sitePlaylists=>{
        SOUNDIFY_CONFIG.sitePlaylists = sitePlaylists

        if((["home","playlistPage"]).includes(SOUNDIFY_CONFIG.currentPage)){
            //If the current page is home then add all the playlists to the playlist preview section.
            if((["home"]).includes(SOUNDIFY_CONFIG.currentPage)){
                ((Object.values(SOUNDIFY_CONFIG.sitePlaylists)).shuffle().slice(0,10)).forEach(playlistInfo=>{
                    _topPlaylistPreview = createTopPlaylistPreview(playlistInfo)
                    topPlaylistsSlide.append(_topPlaylistPreview)
                })
            }

            if(Object.keys(SOUNDIFY_CONFIG.sitePlaylists).length<=0){
                _x_ = document.createElement("p")
                _x_.style.textAlign = "center"
                _x_.style.color = "var(--hoverColor)"
                _x_.innerText = "Nothing to see here..."
                playlistPreviewSection.append(_x_)
            }

            Object.keys(SOUNDIFY_CONFIG.sitePlaylists).shuffle().forEach(sitePlaylistKey=>{
                sitePlaylistInfo = SOUNDIFY_CONFIG.sitePlaylists[sitePlaylistKey]
                sitePlaylist = new playlistPreviewTemplate(sitePlaylistInfo)
                playlistPreviewSection.append(sitePlaylist.template())
            })
        }

        if((["playlistPage"]).includes(SOUNDIFY_CONFIG.currentPage)){
            SOUNDIFY_CONFIG.pagePlaylist.elClass = new pagePlaylistClass(SOUNDIFY_CONFIG.pagePlaylist)
        }

        if(SOUNDIFY_CONFIG.userLoggedIn == false){
            ((Object.values(SOUNDIFY_CONFIG.sitePlaylists)).shuffle().slice(0,3)).forEach(playlistInfo=>{
                _sideBarPlaylistLink = createSideBarPlaylistLink(playlistInfo)
                sideBarPlaylistLinks.append(_sideBarPlaylistLink)
            })
        }

    })

    getAllSiteSongs()
    .then(siteSongs=>{
        SOUNDIFY_CONFIG.siteSongs = siteSongs
        if((["home"]).includes(SOUNDIFY_CONFIG.currentPage)){
            //If the current page is home then add all the songs to the song preview section.
            if(Object.keys(SOUNDIFY_CONFIG.siteSongs).length<=0){
                _x_ = document.createElement("p")
                _x_.style.textAlign = "center"
                _x_.style.color = "var(--hoverColor)"
                _x_.innerText = "Nothing to see here..."
                songsPreviewSection.append(_x_)
            }

            Object.keys(SOUNDIFY_CONFIG.siteSongs).shuffle().forEach(siteSongKey=>{
                siteSongInfo = SOUNDIFY_CONFIG.siteSongs[siteSongKey]
                siteSong = new songPreviewTemplate(siteSongInfo)
                songsPreviewSection.append(siteSong.template())
            })
        }
    })



}




window.onbeforeunload = function(){
    try{

        if(SOUNDIFY_CONFIG.nowPlaying != null){
            if(SOUNDIFY_CONFIG.nowPlaying instanceof HTMLElement ){
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
        return
    }
};