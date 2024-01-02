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

    // Retrieve data from the JSON payload for gg_users
    $providedPhone = isset($postData['phone']) ? $postData['phone'] : null;

    // Check if the phone number is present in gg_users
    $stmtCheckPhone = $pdo->prepare("SELECT * FROM gg_users WHERE phone = :phone");
    $stmtCheckPhone->bindParam(':phone', $providedPhone);
    $stmtCheckPhone->execute();
    $existingUser = $stmtCheckPhone->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        // Phone number exists in gg_users, insert data into gg_checkout
        insertDataIntoCheckout($pdo, $postData);
        echo json_encode(['message' => 'Data inserted into gg_checkout successfully']);
    } else {
        // Phone number does not exist in gg_users
        echo json_encode(['error' => 'Phone number not found in gg_users']);
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

// Function to insert data into gg_checkout
function insertDataIntoCheckout($pdo, $postData) {
    // Retrieve data from the JSON payload for gg_checkout
    $providedPhone = isset($postData['phone']) ? $postData['phone'] : null;
    $providedOrderId = isset($postData['orderId']) ? $postData['orderId'] : null;
    $providedProductId = isset($postData['productId']) ? $postData['productId'] : null;
    $providedName = isset($postData['name']) ? $postData['name'] : null;
    $providedNewRate = isset($postData['newRate']) ? $postData['newRate'] : null;
    $providedImageUrl = isset($postData['imageUrl']) ? $postData['imageUrl'] : null;
    $providedWeight = isset($postData['weight']) ? $postData['weight'] : null;
    $providedBrand = isset($postData['brand']) ? $postData['brand'] : null;
    $providedSold = isset($postData['sold']) ? $postData['sold'] : null;
    $providedDateOfSale = isset($postData['dateOfSale']) ? $postData['dateOfSale'] : null;
    $providedQuantity = isset($postData['quantity']) ? $postData['quantity'] : null;
    $providedOrderDate = isset($postData['orderDate']) ? $postData['orderDate'] : null;
    $providedStatus = isset($postData['status']) ? $postData['status'] : null;
    $providedTotalAmount = isset($postData['totalAmount']) ? $postData['totalAmount'] : null;
    $providedGst = isset($postData['gst']) ? $postData['gst'] : null;
    $providedTax = isset($postData['tax']) ? $postData['tax'] : null;
    $providedShippingOrder = isset($postData['shippingOrder']) ? $postData['shippingOrder'] : null;
    $providedCategory = isset($postData['category']) ? $postData['category'] : null;

    // Insert data into gg_checkout
    try {
        $stmtInsertCheckout = $pdo->prepare("INSERT INTO gg_checkout (
                                                phone, orderId, productId, name, newRate, 
                                                imageUrl, weight, brand, sold, dateOfSale, 
                                                quantity, orderDate, status, totalAmount, gst, 
                                                tax, shippingOrder, category
                                            ) VALUES (
                                                :phone, :orderId, :productId, :name, :newRate, 
                                                :imageUrl, :weight, :brand, :sold, :dateOfSale, 
                                                :quantity, :orderDate, :status, :totalAmount, :gst, 
                                                :tax, :shippingOrder, :category
                                            )");

        $stmtInsertCheckout->bindParam(':phone', $providedPhone);
        $stmtInsertCheckout->bindParam(':orderId', $providedOrderId);
        $stmtInsertCheckout->bindParam(':productId', $providedProductId);
        $stmtInsertCheckout->bindParam(':name', $providedName);
        $stmtInsertCheckout->bindParam(':newRate', $providedNewRate);
        $stmtInsertCheckout->bindParam(':imageUrl', $providedImageUrl);
        $stmtInsertCheckout->bindParam(':weight', $providedWeight);
        $stmtInsertCheckout->bindParam(':brand', $providedBrand);
        $stmtInsertCheckout->bindParam(':sold', $providedSold);
        $stmtInsertCheckout->bindParam(':dateOfSale', $providedDateOfSale);
        $stmtInsertCheckout->bindParam(':quantity', $providedQuantity);
        $stmtInsertCheckout->bindParam(':orderDate', $providedOrderDate);
        $stmtInsertCheckout->bindParam(':status', $providedStatus);
        $stmtInsertCheckout->bindParam(':totalAmount', $providedTotalAmount);
        $stmtInsertCheckout->bindParam(':gst', $providedGst);
        $stmtInsertCheckout->bindParam(':tax', $providedTax);
        $stmtInsertCheckout->bindParam(':shippingOrder', $providedShippingOrder);
        $stmtInsertCheckout->bindParam(':category', $providedCategory);

        $stmtInsertCheckout->execute();
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error inserting data into gg_checkout']);
    }
}
?>
//
{
    "token":"2f7e4164145b2d957a6de600c95b9412ca96da1fe1e98aff0b35bfde2359a517",
    "id":"2",
    "phone":"7667664231",
    "orderId":"123432",
    "productId":"234",
    "name":"turmic",
    "newRate":"150",
    "imageUrl":"https://chat.openai.com/c/a4dfca6c-e8f3-43be-adc4-455c3363e198",
    "weight":"100",
    "brand":"readmic",
    "sold":"1",
    "dateOfSale":"2023-12-29",
    "quantity":"2",
    "orderDate":"2023-12-27",
    "status":"ontheway",
    "totalAmount":"150",
    "gst":"10",
    "tax":"2",
    "shippingOrder":"yes",
    "category":"style"
}
//