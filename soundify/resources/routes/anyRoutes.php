

<?php 

    //Login page
    any("/login", "/soundify/public/pages/main/login.php");

    //Logout page
    any('/logout', function(){
        setCookie("soundifyToken", "");
        header("Location: /login");
    });

    //Signup page
    any("/signup", "/soundify/public/pages/main/signup.php");


?>