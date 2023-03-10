




<?php 

    include_once(__DIR__."/../imports/phpMainImports.php");

    $GLOBALS["currentPage"] = "signup";

    if(isset($userLoggedIn) == true && $userLoggedIn != false){
        redirect("/artists/$userLoggedIn->username");
        exit();
    };
    
    $usernameInputError = $passwordInputError = $signupFeedBackAlert = $results = "";


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $inputedUsername = $_POST["username"];
        $password = $inputedPassword = $_POST["password"];

        if(!isset($username)){
            $usernameInputErrror = "Username is required";
        };

        if(!isset($password)){
            $usernameInputErrror = "Password is required";
        };

        
        
        if($usernameInputError=="" && $passwordInputError == ""){
            $signedUp = signup($username, $password);
            $signupFeedBackAlert = $signedUp["message"];

            if($signedUp["success"] == true){
                setCookie("soundifyToken", $signedUp["token"], time() + (86400 * 30));
                redirect($signedUp["createdUser"][3]);
                exit();
            }
        }

    };
    

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title> <?php out($GLOBALS["CONFIG"]["APP_NAME"]);?> - Signup</title>

        <?php  include_once(__DIR__."/../imports/meta.php"); ?>
        <?php  include_once(__DIR__."/../imports/scriptsAndLinks.php"); ?>

        <link rel="stylesheet" href="/static/pc/css/signup.css">

        <script>
            SOUNDIFY_CONFIG.currentPage = "signup";
        </script>
        
    </head>
    <body onload="mainLoadFunction()">
        <div id="wrapper">

            <?php
                include(__DIR__."/../imports/nowPlayingControls.php");
                include(__DIR__."/../imports/sideBar.php");
            ?>

            <div class="pageWrapper" style="display:flex;">
                <div class="pageBackgroundImageDiv" style="background:url(<?php echo $pageBackgroundImage; ?>);"></div>

                <?php
                    include(__DIR__."/../imports/topBar.php");
                ?>

                <h2 class="pageTitle">Signup</h2>
                <form method="POST" action="/signup">
                    <div class="field">
                        <input type="text" name="username" autocomplete="off" value="<?php if(isset($inputedUsername)){out($inputedUsername);} ?>" required>
                        <label for="username" class="label-wrapper">
                            <span class="label-text">
                                Brukernavn
                            </span>
                        </label>
                        <span class="inputError"><?php out($usernameInputError); ?></span>
                    </div>

                    <div class="field">
                        <input type="password" name="password" autocomplete="off" value="<?php if(isset($inputedPassword)){out($inputedPassword);} ?>" required>
                        <label for="password" class="label-wrapper">
                            <span class="label-text">
                                Passord
                            </span>
                        </label>
                        <span class="inputError"><?php out($passwordInputError); ?></span>
                    </div>
                    <span class="signupFeedBackAlert"><?php out($signupFeedBackAlert); ?></span>

                    <button type="submit">Signup</button>
                    
                    <p>Already have an account? <a href="/login">Login</a></p>

                </form>
            </div>

        </div>
    </body>
</html>