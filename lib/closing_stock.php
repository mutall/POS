<?php
// require the database file
require_once "database.php";

// creating a new instance of database.
$database = new Database;

// create an sql querry to calculate the maximum date
$max_date = "SELECT product, max(stock.date) as max from stock group by product";

// create an sql querry to get product
$product_details = "SELECT 
                       product.product, product.name, stock.date, stock.quantity, image.name as image
                    FROM 
                        product 
                            INNER JOIN stock ON product.product = stock.product
                            INNER JOIN ($max_date) as max_date ON product.product = max_date.product
                            INNER JOIN image ON image.product = product.product
                    WHERE 
                        stock.date = max_date.max";
                                                            
// Querry the sql
$result = $database->query($product_details);

//echo the results to javascript
echo json_encode($result->fetchAll(PDO::FETCH_OBJ));