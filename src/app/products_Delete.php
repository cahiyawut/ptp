<?php
// Set CORS headers
header("Access-Control-Allow-Origin: http://localhost:4200"); // Adjust to the origin of your Angular app
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Return immediately with 200 OK status
    http_response_code(200);
    exit;
}

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

// Get the productCode from the URL parameters
$Code = isset($_GET['productCode']) ? $_GET['productCode'] : '';

// Validate the productCode
if (!empty($Code)) {
    // Database connection (replace with your actual connection details)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "classicmodels";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        http_response_code(500); // Internal Server Error
        echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
        exit;
    }

    // SQL query to delete the item
    $stmt = $conn->prepare("DELETE FROM products WHERE productCode = ?");
    $stmt->bind_param("s", $Code);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Record deleted successfully"]);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["success" => false, "message" => "Record not found"]);
        }
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["success" => false, "message" => "Error deleting record: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["success" => false, "message" => "Invalid productCode"]);
}
?>
