<?php
$day = 1; // masa aktif cache
foreach(glob('./cache/*.c') as $cache){
    if((time()-filectime($cache)) > $day*86400){
        unlink($cache);
        echo $cache. " => Expired".PHP_EOL;
    }else{
        echo $cache. " => Valid".PHP_EOL;
    }
}