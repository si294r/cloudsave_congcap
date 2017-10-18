<?php

include("config.php");

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

//header('Content-Type: application/json');
//echo json_encode($data);   
return $data;


