

<div class="topBar">
    <div class="topBarSiteName">
        <h3> <?php out($GLOBALS["CONFIG"]["APP_NAME"]); ?> </h3>
    </div>

    <div class="topBarUserProfile">
        <?php 
            if( $userLoggedIn != false ){
                echo "
                    <a href='".$userLoggedIn->profilePage."'>
                        <div class='topBarUserProfilePfp' style='width:50px; height:50px; background:url(".$userLoggedIn->pfp.");'></div>
                        <h3>".$userLoggedIn->username."</h3>
                    </a>
                    <a title='Logout' href='/logout' style='display:flex; justify-content:center; align-items:center; margin:10px; color:var(--hoverColor); font-size:2em;' class='topBarLogoutButton'>
                        <i class='fa-solid fa-right-from-bracket' style='color:var(--hoverColor);'></i>
                    </a>
                ";
            } else {
                echo "
                    <a href='/login' style='font-size:1.5em; text-decoration:none; color:var(--hoverColor);'>Login <i class='fa-solid fa-door-open' style='color:var(--hoverColor);' class='topBarLoginButton'></i> </a>
                ";
            }
        ?>

        <div class="topBarMenuButton" onclick="manageSideMenu(this)" style="display:none;">
            <i class="fa-solid fa-bars" style="color:var(--hoverColor); font-size:1.5em;"></i>
        </div>
    </div>
</div>