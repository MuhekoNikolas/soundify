


<?php 

    include_once(__DIR__."/../imports/phpMainImports.php");

    $GLOBALS["currentPage"] = "login";
    
    if(isset($userLoggedIn) == true && $userLoggedIn != false){
        redirect("/artists/$userLoggedIn->username");
        exit();
    };
    
    $usernameInputError = $passwordInputError = $loginFeedBackAlert = "";

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $inputedUsername = $_POST["username"];
        $password = $inputedPassword  = $_POST["password"];

        if(isset($username) == False || strlen($username) <= 2){
            $usernameInputErrror = "Username must be 2 chars+, but no higher than 12!";
        };

        if(!isset($password)){
            $usernameInputErrror = "Password is required";
        };

        if($usernameInputError=="" && $passwordInputError == ""){
            $loggedIn = login($username, $password);

            $loginFeedBackAlert = $loggedIn["message"];

            if($loggedIn["success"] == true){
                setCookie("soundifyToken", $loggedIn["token"], time() + (86400 * 30));
                redirect($loggedIn["loggedInUser"]->profilePage);
                exit();
            }
        
        }
    };

?>

<!DOCTYPE html>
<html lang="en" onload="mainLoadFunction()">
    <head>
        <title> <?php out($GLOBALS["CONFIG"]["APP_NAME"]);?> - Login</title>

        <?php  include_once(__DIR__."/../imports/meta.php"); ?>
        <?php  include_once(__DIR__."/../imports/scriptsAndLinks.php"); ?>

        <link rel="stylesheet" href="/static/pc/css/login.css">

        
    </head>
    <body>
        <div id="wrapper">
            

            <?php 
                include(__DIR__."/../imports/sideBar.php");
            ?>

            <div class="pageWrapper" style="display:flex;">
                <div class="pageBackgroundImageDiv" style="background:url(<?php echo $pageBackgroundImage; ?>);"></div>
                <?php
                    include(__DIR__."/../imports/topBar.php");
                ?>

                <h2 class="pageTitle">Login</h2>
                <form method="POST" action="/login">

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

                    <span class="loginFeedbackAlert"><?php out($loginFeedBackAlert); ?></span>

                    <button type="submit">Logg inn</button>
                    
                    <p>Ny bruker? <a href="/signup">Registrer Her</a></p>
                </form>

                <?php
                    include(__DIR__."/../imports/nowPlayingControls.php");
                ?>
            </div>

        </div>

    </body>
</html>