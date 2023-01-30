


<?php 


    get('/static/$device/$resource/$file', function($device, $resource, $file){

        $device = preg_replace("/\/(.)*/i", "", $device);
        $resource = preg_replace("/\/(.)*/i", "", $resource);
        $file = preg_replace("/\/(.)*/i", "", $file);

        $FileName = __DIR__."/../../public/static/$device/$resource/$file";

        if(file_exists($FileName)){
            if($resource=="js"){
                header("Content-Type:application/javascript");
            } else {
                header("Content-Type:text/css");
            }
            echo(file_get_contents($FileName));
        } else {
            include(__DIR__."/../../public/pages/other/404.php");
        }

    });
?>