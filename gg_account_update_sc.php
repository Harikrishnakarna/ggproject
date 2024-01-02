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

    // Retrieve data from the JSON payload
    $providedPhoneNumber = isset($postData['phone']) ? $postData['phone'] : null;
    $providedName = isset($postData['name']) ? $postData['name'] : null;
    $providedEmail = isset($postData['email']) ? $postData['email'] : null;
    $providedDateOfBirth = isset($postData['dateOfBirth']) ? $postData['dateOfBirth'] : null;
    $providedGender = isset($postData['gender']) ? $postData['gender'] : null;

    // Update data in gg_users
    try {
        $stmtUpdate = $pdo->prepare("UPDATE gg_users 
                                    SET name = :name, email = :email, dateOfBirth = :dateOfBirth, gender = :gender 
                                    WHERE phone = :phone");

        $stmtUpdate->bindParam(':phone', $providedPhoneNumber);
        $stmtUpdate->bindParam(':name', $providedName);
        $stmtUpdate->bindParam(':email', $providedEmail);
        $stmtUpdate->bindParam(':dateOfBirth', $providedDateOfBirth);
        $stmtUpdate->bindParam(':gender', $providedGender);

        $stmtUpdate->execute();

        echo json_encode(['message' => 'Data updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error updating data']);
    }

} else {
    // Handle cases where the request method is not POST
    echo json_encode(['error' => 'Invalid request method. This script only handles POST requests.']);
}

// Close the connection (optional, as PHP will close it when the script ends)
// $pdo = null;

// Function to validate the token (replace with your actual logic)
function validateToken($providedToken) {
    // Replace this with your actual token validation logic
    $validToken = true;
    return $validToken;
}

/*


http://localhost/GG_PROJECT1/gg_account_update_sc.php user details update url

{          
    "phone": "1234567889",
    "name": "reddy",
    "email": "reddy@gmail.com",
    "dateOfBirth": "2000-10-20",
    "gender": "male"
}

*/

