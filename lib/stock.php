<?php

require_once "database.php";

$db = new Database; 

$data = file_get_contents('php://input');

$identifier = json_decode($data)->identifier;

$sql = "SELECT max(date) as max_date FROM stock";
$result = $db->query($sql);

$date = $result->fetch(PDO::FETCH_OBJ)->max_date;

$sql2 = "SELECT 
            product.name, stock.quantity 
        FROM 
            product 
        INNER JOIN 
            stock 
        ON 
            product.product = stock.product
        WHERE stock.date = '$date' AND location = $identifier";


$res = $db->query($sql2);

$final = $res->fetchAll(PDO::FETCH_ASSOC);
$obj = new stdClass;
$obj->date = $date;
$obj->data = $final;
echo json_encode($obj);