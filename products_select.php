<?php
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Origin: http://localhost:4200");
header('Access-Control-Allow-Methods: POST');
header("Content-Type: application/json; charset=UTF-8");

// Create connection
$conn = mysqli_connect("localhost", "root", "", "classicmodels");

// Check connection
if (!$conn) {
    echo json_encode(["error" => "Connection failed: " . mysqli_connect_error()]);
    exit();
}

$sql = "SELECT `productCode`, `productName`, `productLine`, `productScale`, `productVendor`,
`productDescription`, `quantityInStock`, `buyPrice`, `MSRP` 
FROM products";

$result = mysqli_query($conn, $sql);

$data = [];

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        // Fetch data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                "productCode" => $row["productCode"],
                "productName" => $row["productName"],
                "productLine" => $row["productLine"],
                "productScale" => $row["productScale"],
                "productVendor" => $row["productVendor"],
                "productDescription" => $row["productDescription"],
                "quantityInStock" => $row["quantityInStock"],
                "buyPrice" => $row["buyPrice"],
                "MSRP" => $row["MSRP"]
            ];
        }
    } else {
        // If no results, return an empty array
        $data = [];
    }
} else {
    // Query execution failed
    $data = ["error" => "Query failed: " . mysqli_error($conn)];
}

// Output the data as JSON
echo json_encode($data);

// Close connection
mysqli_close($conn);
?>
