<?php

//$json = json_decode($input);
$file_json = "data/server_time{$BUILD_TYPE}.json";

if ($IS_DEVELOPMENT && is_file($file_json)) {
    $content = file_get_contents($file_json);
    $data = json_decode($content, true);
} else {
    $data['server_time'] = gmdate('Y-m-d H:i:s');
    $data['update_time'] = $data['server_time'];
}

return $data;


