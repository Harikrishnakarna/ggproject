
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

    // Insert phone number into gg_users
    try {
        $stmtInsert = $pdo->prepare("INSERT IGNORE INTO gg_users (phone) VALUES (:phone)");
        $stmtInsert->bindParam(':phone', $providedPhoneNumber);
        $stmtInsert->execute();

        if ($stmtInsert->rowCount() > 0) {
            echo json_encode(['message' => 'Phone number inserted successfully']);
        } else {
            echo json_encode(['message' => 'Phone number already exists. No insert performed.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error inserting phone number']);
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


/*


http://localhost/GG_PROJECT1/gg_login_sc.php Postmethod url

{          
    "phone_number": "1234567889"

}


*/
