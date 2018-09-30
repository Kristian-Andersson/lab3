<?php
header('Content-Type: application/json');

// Database connection
try { 
    $dbh = new PDO("mysql: host=localhost; dbname=fishtruck;", "fishUser", "fotboll");

} catch (PDOException $e) {
    echo 'Connection fail: ' . $e->getMessage();
}

// Initial variable values
$products = "";
$key = "";

// Get all with API-key
if ($_GET['api-key']) {
    $products = 1;
    $key = "api_keys.api_key = " . $_GET['api-key'];
}

// Get some products
if ($_GET['category']) {
    $products = "categories.category_id = " . $_GET['category'];
}

// Get one product
if ($_GET['id']) {
    $products = "products.id = " . $_GET['id'];
}

// The Query
$stmt = $dbh->prepare("
    SELECT *
    FROM products
    LEFT JOIN categories
    ON products.category = categories.category_id
    CROSS JOIN api_keys
    WHERE " . $products . " AND " . $key
);

// Runs the query (Will return error if incorrect or no API is given)
if ($stmt->execute() == false) {
    echo "Something is incorrect in the query.";
    return;
} else {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
     
// Result handler
if (is_null($result)) {
    echo "The given value does not exist.";
    return;
} else {
    $jsonResult = json_encode($result);
    echo $jsonResult;
}
?>
