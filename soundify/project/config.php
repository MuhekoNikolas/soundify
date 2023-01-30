

<?php 
    $CONFIG_JSON = '{
            "APP_NAME" : "Soundify", 
            "APP_DESCRIPTION" : "Lorem ipsum doler sit amet.",
            "DB": { 
                "username":"root",
                "password":"",
                "database":"soundify"
        }
    }';

    $GLOBALS["CONFIG"] = json_decode($CONFIG_JSON, true);;

?>