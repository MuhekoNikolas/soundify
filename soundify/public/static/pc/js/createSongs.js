

function startUploadSongProccess(form){


    try{
        songObj = {
            name: "",
            image: "",
            audio: ""
        }

        songName = document.querySelector("#songName")?.value || ""
        songImageUrl = document.querySelector("#songImageUrl")?.value || ""

        songAudioInputFile = document.querySelector("#songAudio").getAttribute("data-audio-data-url")


        if(songName.length < 1){
            Toastify({
                text: "Song name can't be empty",
                style: {
                  background: "linear-gradient(to right, red, var(--c2))",
                }
            }).showToast();
            return false
        }

        if(songImageUrl.length < 1){
            Toastify({
                text: "Song image can't be empty",
                style: {
                  background: "linear-gradient(to right, red, var(--c2))",
                }
              }).showToast();
              return false
        }

        if(songAudioInputFile.length < 1){
            Toastify({
                text: "Song audio can't be empty",
                style: {
                  background: "linear-gradient(to right, red, var(--c2))",
                }
              }).showToast();
              return false
        }

        songObj.name = songName
        songObj.image = songImageUrl
        songObj.audio = songAudioInputFile

        options = {
            method:"POST",
            body: JSON.stringify(songObj),
            headers: {
                "Content-Type":"application/json"
            }
        }

        fetch(`/artists/${SOUNDIFY_CONFIG.userLoggedIn.username}/songs/new`, options)
        .then(req=>req)
        .then(req=>{
            if(!req.ok){
                Toastify({
                    text: "An error occured.",
                    style: {
                      background: "linear-gradient(to right, red, var(--c2))",
                    }
                }).showToast();
                return false
            }

            try{
                req.json()
                .then(data=>{
                    if(data.success == false){
                        Toastify({
                            text: `An error occured: ${data.message}`,
                            style: {
                              background: "linear-gradient(to right, red, var(--c2))",
                            }
                        }).showToast();
                    } else {
                        Toastify({
                            text: `${data.message}`,
                            style: {
                              background: "linear-gradient(to right, #00b09b, var(--c2))",
                            }
                        }).showToast();
                        location.reload()
                    }
                })
            } catch(err){
                Toastify({
                    text: `An error occured: ${err}`,
                    style: {
                      background: "linear-gradient(to right, red, var(--c2))",
                    }
                }).showToast();
            }

        })

        return false
    } catch (err){
        Toastify({
            text: `An error occured: ${err}`,
            style: {
              background: "linear-gradient(to right, red, var(--c2))",
            }
        }).showToast();
        return false
    }
}


function updateSongImageFileInput(evt){
    songUrlInput = document.querySelector("#songImageUrl") 

    songImageInputFile = evt.target.files[0]
    fileReader = new FileReader()
    fileReader.readAsDataURL(songImageInputFile)
    fileReader.onload = function(evt){
        songUrlInput.value = evt.target.result
    }
}

function updateSongAudioFileInput(evt){
    songAudioInput = document.querySelector("#songAudio") 

    songAudioInputFile = evt.target.files[0]
    fileReader = new FileReader()
    fileReader.readAsDataURL(songAudioInputFile)
    fileReader.onload = function(evt){
        songAudioInput.dataset.audioDataUrl = evt.target.result
    }
}