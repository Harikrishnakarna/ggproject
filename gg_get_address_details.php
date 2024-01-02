<?php

// Include the database connection file
require_once('db_connection.php');

// Check if data is provided through POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Read JSON data from the request body
    $jsonPayload = file_get_contents('php://input');
    $postData = json_decode($jsonPayload, true);

    // Validate the token
    $providedToken = isset($postData['token']) ? $postData['token'] : null;

    // Check if a token is provided
    if (!$providedToken) {
        echo json_encode(['error' => 'Token not provided']);
        exit;
    }

    // Replace this with your actual token validation logic
    $validToken = validateToken($providedToken);

    if (!$validToken) {
        // Token is invalid, respond accordingly
        echo json_encode(['error' => 'Invalid token']);
        exit;
    }

    // Retrieve phone number from the JSON payload
    $providedPhoneNumber = isset($postData['phone']) ? $postData['phone'] : null;

    // Retrieve data for the selected phone number
    try {
        $stmtSelect = $pdo->prepare("SELECT * FROM gg_address WHERE phone = :phone");
        $stmtSelect->bindParam(':phone', $providedPhoneNumber);
        $stmtSelect->execute();

        $result = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        // If the phone number is not found, return null
        $responseData = $result ? $result : "Phone number not inserted into Database";

        // Convert the result to JSON and echo it
        echo json_encode($responseData);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error retrieving data']);
    }

} else {
    // Handle cases where the request method is not POST
    echo json_encode(['error' => 'Invalid request method. This script only handles POST requests.']);
}

// Function to validate the token (replace with your actual logic)
function validateToken($providedToken) {
    // Replace this with your actual token validation logic
    $validToken = true;
    return $validToken;
}

// Close the connection (optional, as PHP will close it when the script ends)
// $pdo = null;
?>