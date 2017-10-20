<?php

include("config.php");

require '/var/www/vendor/autoload.php';
include '/var/www/redshift-config2.php';

use Aws\S3\S3Client;


//$input = file_get_contents("php://input");
$json = json_decode($input);

$data['document_id'] = isset($json->document_id) ? $json->document_id : "";

$connection = new PDO(
    "mysql:dbname=$mydatabase;host=$myhost;port=$myport",
    $myuser, $mypass
);
    
$sql1 = "
    SELECT *
    FROM cloudsave 
    WHERE document_id = :document_id
    ORDER BY last_update DESC
    LIMIT 5
";
$statement1 = $connection->prepare($sql1);
$statement1->bindParam(":document_id", $data['document_id']);
$statement1->execute();
$list = $statement1->fetchAll(PDO::FETCH_ASSOC);

$data['list'] = $list;

if (count($list) == 0 && isset($json->list_s3)) {
    
    $clientS3 = S3Client::factory(array(
        'credentials' => array(
            'key' => $aws_access_key_id,
            'secret' => $aws_secret_access_key
        )
    ));
    
    $folder = $IS_DEVELOPMENT ? "dev" : "live"; 
    
    $result = $clientS3->listObjectVersions([
    'Bucket' => 'alegrium-www', // REQUIRED
//    'Delimiter' => '<string>',
//    'EncodingType' => 'url',
//    'KeyMarker' => '<string>',
    'MaxKeys' => 10,
    'Prefix' => 'conglomerate/cloudsave/'.$folder.'/'.$data['document_id'],
//    'VersionIdMarker' => '<string>',
    ]);
    var_dump($result);
    $data['list_s3'] = $result;
}

//header('Content-Type: application/json');
//echo json_encode($data);   
return $data;


