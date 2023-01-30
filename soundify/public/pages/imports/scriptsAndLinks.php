

        <link rel="stylesheet" href="/static/pc/css/main.css">
        <link rel="stylesheet" href="/static/pc/css/home.css">
        <link rel="stylesheet" href="/static/pc/css/login.css">
        <link rel="stylesheet" href="/static/pc/css/signup.css">
        <link rel="stylesheet" href="/static/pc/css/artistPage.css">

        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

        
        <script src="/static/pc/js/classes.js"></script>
        <script src="/static/pc/js/variables.js"></script>
        <script src="/static/pc/js/main.js" defer></script>
        <script src="https://kit.fontawesome.com/49ac55f315.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js" defer></script>


        <script>
                const SOUNDIFY_CONFIG = {};


                SOUNDIFY_CONFIG.queriedSongs = []
                SOUNDIFY_CONFIG.nowPlaying = 
                <?php
                    if(array_key_exists("SoundifyNowPlaying", $_COOKIE)){
                        $GLOBALS["nowPlaying"] = $_COOKIE["SoundifyNowPlaying"];
                        if(strlen($GLOBALS["nowPlaying"]) <= 2 || json_decode($GLOBALS["nowPlaying"]) == null ){
                                $GLOBALS["nowPlaying"] = "null";
                        } 
                        echo($GLOBALS["nowPlaying"]);
                    } else {
                        echo("null");
                    }
                ?> || null;

                SOUNDIFY_CONFIG.pageUrl = "<?php out(filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL)); ?>"

                <?php if($userLoggedIn != false){ ?>
                        SOUNDIFY_CONFIG.userLoggedIn = <?php echo(json_encode($userLoggedIn)); ?>
                <?php } else { ?> 
                        SOUNDIFY_CONFIG.userLoggedIn = false
                <?php } ?>

                class soundifySongPreviewElement extends HTMLElement{
                        constructor(){
                                super()
                                this.shadow = this.attachShadow({mode:"open"})
                                this.songInfo  = this.dataset.songInfo
                        }
                }

                class soundifyPlaylistPreviewElement extends HTMLElement{
                        constructor(){
                                super()
                                this.shadow = this.attachShadow({mode:"open"})
                                this.playlistInfo  = this.dataset.playlistInfo
                        }
                }


                class soundifyNowPlayingControlsElement extends HTMLElement{
                        constructor(){
                                super()
                                this.shadow = this.attachShadow({mode:"open"})
                        }
                }

                class soundifyAudioElement extends HTMLElement{
                        constructor(){
                                super()
                                this.shadow = this.attachShadow({mode:"open"})
                                this.shadow.innerHTML = `
                                        div{
                                        background:red !important;
                                }
                                 `;
                                 console.log("0", this)
                        }

                        play(){
                                alert(1)
                        }
                }


                function defineCustomElements(){
                        customElements.define("soundify-song-preview", soundifySongPreviewElement, {extends:"div"})
                        customElements.define("soundify-playlist-preview", soundifyPlaylistPreviewElement, {extends:"div"})
                        customElements.define("soundify-now-playing-controls", soundifyNowPlayingControlsElement, {extends:"div"})
                        customElements.define("soundify-audio", soundifyAudioElement, {extends:"audio"})
                }
                defineCustomElements()

                console.log(SOUNDIFY_CONFIG)

        </script>