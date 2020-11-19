<?php
$album = 'https://photos.google.com/share/AF1QipM4hzKPx9QNxXrUJRUrcJA1JTRPE6Su55gZtbwkWx2ldXsUBiy1dddpo3HKavSuTQ?key=NWlsSFdTdXJTcDZCSGxOMDIxNTFyaHd0RnhyS3lR';

echo getAlbum($album); 

function getAlbum($album){
    $getAlbum = get($album);
    preg_match_all('/;" href="\.(.*?)"><img class="/',$getAlbum,$videos);
    foreach($videos[1] as $k => $video){
        // return md5($video);
        if(file_exists('./cache/'.md5($video).'.c')){
            $result[] = json_decode(file_get_contents('./cache/'.md5($video).'.c'),true);
        }else{
            $getVideo = get("https://photos.google.com$video");
            preg_match('/"boq_photosuiserver_(.*?)"/',$getVideo,$bl);
            preg_match('/request:\[\["(.*?)"\]\n,"(.*?)"/',$getVideo,$query);
            preg_match("/'ds:1' : {id:'(.*?)'/",$getVideo,$id);
            $getTitleURI = 'https://photos.google.com/_/PhotosUi/data/batchexecute?rpcids='.$id[1];
            preg_match('/\[\["(.*?)","","(.*?)",(\d+)/',getTitle($getTitleURI,$query[1],$query[2]),$judul);
            $save = array(
                'judul' => $judul[2],
                'link' => "https://photos.google.com$video",
            );
            $result[] = $save;
            file_put_contents('./cache/'.md5($video).'.c',json_encode($save));
        }
    }
    return json_encode($result,JSON_PRETTY_PRINT);
    // return $result;
}

function getTitle($url,$one,$two){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'f.req=%5B%5B%5B%22fDcn4b%22%2C%22%5B%5C%22'.$one.'%5C%22%2C1%2C%5C%22'.$two.'%5C%22%5D%22%2Cnull%2C%221%22%5D%5D%5D&');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    
    $headers = array();
    $headers[] = 'X-Same-Domain: 1';
    $headers[] = 'Referer: https://photos.google.com/';
    $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded;charset=UTF-8';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    curl_close($ch);
    return str_replace('\\','',$result);
}
function get($url){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: photos.google.com';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Service-Worker-Navigation-Preload: true';
    $headers[] = 'Sec-Fetch-Site: same-origin';
    $headers[] = 'Sec-Fetch-Mode: navigate';
    $headers[] = 'Sec-Fetch-User: ?1';
    $headers[] = 'Sec-Fetch-Dest: document';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}