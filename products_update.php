<?php
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Origin: http://localhost:4200");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header("Content-Type: application/json; charset=UTF-8");

// Create connection
$conn = mysqli_connect("localhost", "root", "", "classicmodels");

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Read the input data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
  echo json_encode(["error" => "Invalid JSON data"]);
  exit();
}

// Prepare data for SQL query
$productCode = mysqli_real_escape_string($conn, $data['c1']);
$productName = mysqli_real_escape_string($conn, $data['c2']);
$productLine = mysqli_real_escape_string($conn, $data['c3']);
$productScale = mysqli_real_escape_string($conn, $data['c4']);
$productVendor = mysqli_real_escape_string($conn, $data['c5']);
$productDescription = mysqli_real_escape_string($conn, $data['c6']);
$quantityInStock = mysqli_real_escape_string($conn, $data['c7']);
$buyPrice = mysqli_real_escape_string($conn, $data['c8']);
$MSRP = mysqli_real_escape_string($conn, $data['c9']);

// Define SQL query with placeholders
$sql = "UPDATE products 
        SET productName=?, 
            productLine=?, 
            productScale=?, 
            productVendor=?, 
            productDescription=?, 
            quantityInStock=?, 
            buyPrice=?, 
            MSRP=?
        WHERE productCode=?";

// Prepare the SQL statement
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    echo json_encode(["error" => "Failed to prepare SQL statement"]);
    exit();
}

// Bind parameters
mysqli_stmt_bind_param($stmt, 'ssssssdds', $productName, $productLine, $productScale, $productVendor, $productDescription, $quantityInStock, $buyPrice, $MSRP, $productCode);

// Execute SQL query
if (mysqli_stmt_execute($stmt)) {
  echo json_encode(["success" => "Record updated successfully"]);
} else {
  echo json_encode(["error" => "Error updating record: " . mysqli_error($conn)]);
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
