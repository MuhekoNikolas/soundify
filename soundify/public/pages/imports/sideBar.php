



<div class="sideBar">
    <div class="topBarMenuButton" onclick="manageSideMenu(this)" style="position:fixed !important; top:40px; left:80vw; display:none;">
        <i class="fa-solid fa-close" style="color:var(--hoverColor); font-size:1.5em;"></i>
    </div>

    <h2 class="sideBarAppName"><?php out($GLOBALS["CONFIG"]["APP_NAME"]); ?></h2>

    <div class="sideBarSection sideBarNavigationSection">
        <h3 class="sideBarSectionTitle">Navigation</h3>

        <?php
            $GLOBALS["currentPage"]=="explore" ? $exploreLinkColor = "var(--hoverColor)" : $exploreLinkColor = "inherit";
        
            echo "
            <a href='/' class='sideBarNavigationSectionLink'>
                <i class='fa-brands fa-hotjar' style='color:$exploreLinkColor;'></i> 
                <p style='color:$exploreLinkColor;'> Explore </p>
            </a>
            ";
            

            if($userLoggedIn == false){
                $GLOBALS["currentPage"]=="login" ? $loginLinkColor = "var(--hoverColor)" : $loginLinkColor = "inherit";
                $GLOBALS["currentPage"]=="signup" ? $signupLinkColor = "var(--hoverColor)" : $signupLinkColor = "inherit";
                
                echo "
                <a href='/login' class='sideBarNavigationSectionLink'>
                    <i class='fa-solid fa-door-open' style='color:$loginLinkColor;'></i> 
                    <p style='color:$loginLinkColor;'> Login </p>
                </a>
                <a href='/signup' class='sideBarNavigationSectionLink'>
                    <i class='fa-solid fa-user-plus' style='color:$signupLinkColor;'></i> 
                    <p style='color:$signupLinkColor;'> Signup </p>
                </a>
                ";
            } else {
                $GLOBALS["currentPage"]=="myPage" ? $myPageLinkColor = "var(--hoverColor)" : $myPageLinkColor = "inherit";
                echo "
                <a href='".$userLoggedIn->profilePage."' class='sideBarNavigationSectionLink'>
                    <i class='fa-solid fa-home' style='color:$myPageLinkColor;'></i> 
                    <p style='color:$myPageLinkColor;'> My Page </p>
                </a>
                <a href='/logout' class='sideBarNavigationSectionLink'>
                    <i class='fa-solid fa-right-from-bracket' style='color:red;'></i> 
                    <p style='color:red;'> Logout </p>
                </a>
                ";
            }
        ?>

        <div class="sideBarSection sideBarPlaylistsSection">
            <h3 class="sideBarSectionTitle" style="display:flex; justfiy-content:center; align-items:center; margin-bottom:-10px;">Playlists 
                <?php 
                    if($userLoggedIn != false){
                        echo "
                        <div onclick='redirect(\"/artists/".$userLoggedIn->username."/playlists/new\")' style='display:flex; justify-content:center; margin-left:40px; align-items:center;'>
                            <i class='fa-solid fa-plus' style='width:20px; box-sizing:border-box; color:var(--hoverColor);'></i>
                        </div>
                        ";
                    }
                ?>
            </h3>
        </div>

    </div>



</div>
