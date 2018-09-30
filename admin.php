<?php
header('Content-Type: application/json');
// Database connection
try { 
    $dbh = new PDO("mysql: host=localhost; dbname=fishtruck;", "fishUser", "fotboll");

} catch (PDOException $e) {
    echo 'Connection fail: ' . $e->getMessage();
}


// CRUD operations. Changes the query depending on the html select tag option
$crud = "";

if ($_POST['crudSelection'] == 'create') {
    $crud = "INSERT INTO products (id, name, calories, protein, price, category) VALUES (:id, :name, :cal, :prot, :price, :cat)";
}
if ($_POST['crudSelection'] == 'read') {
    $crud = "SELECT * FROM products";
}
if ($_POST['crudSelection'] == 'update') {
    $crud = "UPDATE products SET name = :name, calories = :cal, protein = :prot, price = :price, category = :cat WHERE id = :id";
}
if ($_POST['crudSelection'] == 'delete') {
    $crud = "DELETE FROM products WHERE name = :name AND calories = :cal AND protein = :prot AND price = :price AND category = :cat";
}

// removes last value in array (submit)
array_pop($_POST);
// The Query
$stmt = $dbh->prepare($crud);
// Creates keywords of the $_POST array 
$stmt->bindParam(":id", $_POST['id']);
$stmt->bindParam(":name", $_POST['name']);
$stmt->bindParam(":cal", $_POST['calories']);
$stmt->bindParam(":prot", $_POST['protein']);
$stmt->bindParam(":price", $_POST['price']);
$stmt->bindParam(":cat", $_POST['category']);

// Checks what selection is set and if execute is successful 
if ($stmt->execute() && $_POST['crudSelection'] == 'create') {
    $result = $dbh->lastInsertId();
    $jsonResult = json_encode($result);
    echo "The product will have the ID: " . $jsonResult;
} elseif ($stmt->execute() && $_POST['crudSelection'] == 'read') {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $jsonResult = json_encode($result);
    echo $jsonResult;
} elseif ($stmt->execute() && $_POST['crudSelection'] == 'update') {
    echo "Update on ID: " . $_POST['id'] . " is successful";
} elseif ($stmt->execute() && $_POST['crudSelection'] == 'delete') {
    echo "Deletion successful";
} else {
    $result = $stmt->errorInfo();
    var_dump($result);
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <form action="admin.php" method="POST">
        <select name="crudSelection">
            <option value="select">Select</option>
            <option value="create">Create</option>
            <option value="read">Read</option>
            <option value="update">Update</option>
            <option value="delete">Delete</option>
        </select><br>
        <input type="text" name="id" placeholder="ID"><br>
        <input type="text" name="name" placeholder="Name"><br>
        <input type="text" name="calories" placeholder="Calories"><br>
        <input type="text" name="protein" placeholder="Protein"><br>
        <input type="text" name="price" placeholder="Price"><br>
        <input type="text" name="category" placeholder="Category"><br>
        <input type="submit" name="submit" value="Go">
    </form>
</body>
</html>