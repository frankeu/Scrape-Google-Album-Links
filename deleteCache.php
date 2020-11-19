<?php
$day = 1; // jumlah masa aktif cacke
foreach(glob('./cache/*.c') as $cache){
    if((time()-filectime($cache)) < $day*86400){
        unlink($cache);
        echo $cache. " => Deleted".PHP_EOL;
    }
}
echo PHP_EOL . "All caches that are older than 1 day have been deleted";