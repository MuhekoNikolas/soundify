


function startCreatePlaylistProccess(form){
    try{
        playlistObj = {
            name: "",
            image: ""
        }

        playlistName = document.querySelector("#playlistName")?.value || ""
        playlistImageUrl = document.querySelector("#playlistImageUrl")?.value || ""


        if(playlistName.length < 1){
            Toastify({
                text: "Playlist name can't be empty",
                style: {
                  background: "linear-gradient(to right, red, var(--c2))",
                }
            }).showToast();
            return false
        }

        if(playlistImageUrl.length < 1){
            Toastify({
                text: "Playlist image can't be empty",
                style: {
                  background: "linear-gradient(to right, red, var(--c2))",
                }
              }).showToast();
              return false
        }

        playlistObj.name = playlistName
        playlistObj.image = playlistImageUrl

        options = {
            method:"POST",
            body: JSON.stringify(playlistObj),
            headers: {
                "Content-Type":"application/json"
            }
        }

        fetch(`/api/artists/${SOUNDIFY_CONFIG.userLoggedIn.username}/playlists/new`, options)
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
        .catch(err=>{
            Toastify({
                text: `An error occured: ${err}`,
                style: {
                  background: "linear-gradient(to right, red, var(--c2))",
                }
            }).showToast();
        })


    } catch (err){
        Toastify({
            text: `An error occured: ${err}`,
            style: {
              background: "linear-gradient(to right, red, var(--c2))",
            }
        }).showToast();
        return false
    }
    return false
}


function updatePlaylistImageFileInput(evt){
    playlistUrlInput = document.querySelector("#playlistImageUrl") 

    playlistImageInputFile = evt.target.files[0]
    fileReader = new FileReader()
    fileReader.readAsDataURL(playlistImageInputFile)
    fileReader.onload = function(evt){
        playlistUrlInput.value = evt.target.result
    }
}