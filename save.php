<?php

include("config.php");

//$input = file_get_contents("php://input");
$json = json_decode($input);

$data['document_id'] = isset($json->document_id) ? $json->document_id : "";
$data['version_id'] = isset($json->version_id) ? $json->version_id : "";
$data['data'] = isset($json->data) ? $json->data : "";

$connection = new PDO(
    "mysql:dbname=$mydatabase;host=$myhost;port=$myport",
    $myuser, $mypass
);
    
// create record if not exists
$sql1 = "INSERT INTO cloudsave (document_id, version_id, data, last_update)
    VALUES (:document_id, :version_id, :data, NOW())
";
$statement1 = $connection->prepare($sql1);
$statement1->bindParam(":document_id", $data['document_id']);
$statement1->bindParam(":version_id", $data['version_id']);
$statement1->bindParam(":data", $data['data']);
$statement1->execute();

$data['affected_row'] = $statement1->rowCount();
$data['error'] = 0;
$data['message'] = 'Success';

//header('Content-Type: application/json');
//echo json_encode($data);   
return $data;
