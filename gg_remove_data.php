<?php

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

    // Check if the provided phone number exists in gg_users
    $stmtCheckUser = $pdo->prepare("SELECT COUNT(*) FROM gg_users WHERE phone = :phone");
    $stmtCheckUser->bindParam(':phone', $providedPhoneNumber);
    $stmtCheckUser->execute();
    $userExists = $stmtCheckUser->fetchColumn();

    if (!$userExists) {
        echo json_encode(['error' => 'Phone number does not exist in Database.']);
        exit;
    }

    // Delete data from gg_cart
    try {
        $stmtDeleteCart = $pdo->prepare("DELETE FROM gg_cart WHERE phone = :phone");
        $stmtDeleteCart->bindParam(':phone', $providedPhoneNumber);
        $stmtDeleteCart->execute();
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error deleting data from gg_cart. ' . $e->getMessage()]);
        exit;
    }

    // Delete data from gg_checkout
    try {
        $stmtDeleteCheckout = $pdo->prepare("DELETE FROM gg_checkout WHERE phone = :phone");
        $stmtDeleteCheckout->bindParam(':phone', $providedPhoneNumber);
        $stmtDeleteCheckout->execute();
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error deleting data from gg_checkout. ' . $e->getMessage()]);
        exit;
    }

    echo json_encode(['message' => 'Data deleted successfully']);

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

?>
