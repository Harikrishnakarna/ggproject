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

    // Check if the provided phone number exists in gg_cart
    $stmtCheckCart = $pdo->prepare("SELECT * FROM gg_cart WHERE phone = :phone");
    $stmtCheckCart->bindParam(':phone', $providedPhoneNumber);
    $stmtCheckCart->execute();
    $cartData = $stmtCheckCart->fetchAll(PDO::FETCH_ASSOC);

    if ($cartData) {
        // Phone number exists in gg_cart, return cart details
        echo json_encode(['message' => 'Cart details retrieved successfully', 'data' => $cartData]);
    } else {
        echo json_encode(['error' => 'Phone number not insert in Database']);
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
//
{
    "token":"2f7e4164145b2d957a6de600c95b9412ca96da1fe1e98aff0b35bfde2359a517",
    "id":"2",
    "phone":"7667664231",
    "tittle":"turmic",
    "name":"new",
    "newRate":"150",
    "oldRate":"165",
    "imageUrl":"https://learning.ccbp.in/",
    "weight":"100",
    "brand":"ggg",
    "description":"qwertyuioooooplkjuytrewertyuiuytrertyui",
    "sold":"2",
    "dateOfSale":"2023-12-29",
    "quality":"good",
    "quantity":"2"
}
//
