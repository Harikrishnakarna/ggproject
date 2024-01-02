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
    $providedId = isset($postData['id']) ? $postData['id'] : null;

    // Check if the provided phone number and id exist in gg_cart
    $stmtCheckRow = $pdo->prepare("SELECT COUNT(*) FROM gg_cart WHERE phone = :phone AND id = :id");
    $stmtCheckRow->bindParam(':phone', $providedPhoneNumber);
    $stmtCheckRow->bindParam(':id', $providedId);
    $stmtCheckRow->execute();
    $rowExists = $stmtCheckRow->fetchColumn();

    if ($rowExists) {
        // Delete data from gg_cart
        try {
            $stmtDelete = $pdo->prepare("DELETE FROM gg_cart WHERE phone = :phone AND id = :id");
            $stmtDelete->bindParam(':phone', $providedPhoneNumber);
            $stmtDelete->bindParam(':id', $providedId);
            $stmtDelete->execute();

            if ($stmtDelete->rowCount() > 0) {
                echo json_encode(['message' => 'Data deleted successfully']);
            } else {
                echo json_encode(['message' => 'No delete performed.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error deleting data from gg_cart. ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'No matching data found for deletion.']);
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
?>
