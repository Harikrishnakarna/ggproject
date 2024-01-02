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

    // Mandatory fields: phone and id
    $mandatoryFields = ['phone', 'id'];

    // Check if mandatory fields are present
    foreach ($mandatoryFields as $field) {
        if (!isset($postData[$field])) {
            echo json_encode(['error' => ucfirst($field) . ' is a mandatory field']);
            exit;
        }
    }

    // Retrieve phone number from the JSON payload
    $providedPhoneNumber = $postData['phone'];
    $providedId = $postData['id'];
    $providedTittle = isset($postData['tittle']) ? $postData['tittle'] : null;
    $providedName = isset($postData['name']) ? $postData['name'] : null;
    $providedNewRate = isset($postData['newRate']) ? $postData['newRate'] : null;
    $providedOldRate = isset($postData['oldRate']) ? $postData['oldRate'] : null;
    $providedImageUrl = isset($postData['imageUrl']) ? $postData['imageUrl'] : null;
    $providedWeight = isset($postData['weight']) ? $postData['weight'] : null;
    $providedBrand = isset($postData['brand']) ? $postData['brand'] : null;
    $providedDescription = isset($postData['description']) ? $postData['description'] : null;
    $providedSold = isset($postData['sold']) ? $postData['sold'] : null;
    $providedDateOfSale = isset($postData['dateOfSale']) ? $postData['dateOfSale'] : null;
    $providedQuality = isset($postData['quality']) ? $postData['quality'] : null;
    $providedQuantity = isset($postData['quantity']) ? $postData['quantity'] : null;

    // Check if the provided phone number exists in gg_users
    $stmtCheckUser = $pdo->prepare("SELECT COUNT(*) FROM gg_users WHERE phone = :phone");
    $stmtCheckUser->bindParam(':phone', $providedPhoneNumber);
    $stmtCheckUser->execute();
    $userExists = $stmtCheckUser->fetchColumn();

    if (!$userExists) {
        echo json_encode(['error' => 'Phone number does not exist in Database.']);
        exit;
    }

    // Check if the provided phone number and id exist in gg_cart
    $stmtCheckRow = $pdo->prepare("SELECT COUNT(*) FROM gg_cart WHERE phone = :phone AND id = :id");
    $stmtCheckRow->bindParam(':phone', $providedPhoneNumber);
    $stmtCheckRow->bindParam(':id', $providedId);
    $stmtCheckRow->execute();
    $rowExists = $stmtCheckRow->fetchColumn();

    if ($rowExists) {
        // Update data in gg_cart
        try {
            $stmtUpdate = $pdo->prepare("UPDATE gg_cart SET tittle = :tittle, name = :name, newRate = :newRate, oldRate = :oldRate, imageUrl = :imageUrl, weight = :weight, brand = :brand, description = :description, sold = :sold, dateOfSale = :dateOfSale, quality = :quality, quantity = :quantity WHERE phone = :phone AND id = :id");
            $stmtUpdate->bindParam(':phone', $providedPhoneNumber);
            $stmtUpdate->bindParam(':id', $providedId);
            $stmtUpdate->bindParam(':tittle', $providedTittle);
            $stmtUpdate->bindParam(':name', $providedName);
            $stmtUpdate->bindParam(':newRate', $providedNewRate);
            $stmtUpdate->bindParam(':oldRate', $providedOldRate);
            $stmtUpdate->bindParam(':imageUrl', $providedImageUrl);
            $stmtUpdate->bindParam(':weight', $providedWeight);
            $stmtUpdate->bindParam(':brand', $providedBrand);
            $stmtUpdate->bindParam(':description', $providedDescription);
            $stmtUpdate->bindParam(':sold', $providedSold);
            $stmtUpdate->bindParam(':dateOfSale', $providedDateOfSale);
            $stmtUpdate->bindParam(':quality', $providedQuality);
            $stmtUpdate->bindParam(':quantity', $providedQuantity);
            $stmtUpdate->execute();

            if ($stmtUpdate->rowCount() > 0) {
                echo json_encode(['message' => 'Data updated successfully']);
            } else {
                echo json_encode(['message' => 'No update performed.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error updating data in gg_cart. ' . $e->getMessage()]);
        }
    } else {
        // Insert data into gg_cart
        try {
            $stmtInsert = $pdo->prepare("INSERT INTO gg_cart (phone, id, tittle, name, newRate, oldRate, imageUrl, weight, brand, description, sold, dateOfSale, quality, quantity) VALUES (:phone, :id, :tittle, :name, :newRate, :oldRate, :imageUrl, :weight, :brand, :description, :sold, :dateOfSale, :quality, :quantity)");
            $stmtInsert->bindParam(':phone', $providedPhoneNumber);
            $stmtInsert->bindParam(':id', $providedId);
            $stmtInsert->bindParam(':tittle', $providedTittle);
            $stmtInsert->bindParam(':name', $providedName);
            $stmtInsert->bindParam(':newRate', $providedNewRate);
            $stmtInsert->bindParam(':oldRate', $providedOldRate);
            $stmtInsert->bindParam(':imageUrl', $providedImageUrl);
            $stmtInsert->bindParam(':weight', $providedWeight);
            $stmtInsert->bindParam(':brand', $providedBrand);
            $stmtInsert->bindParam(':description', $providedDescription);
            $stmtInsert->bindParam(':sold', $providedSold);
            $stmtInsert->bindParam(':dateOfSale', $providedDateOfSale);
            $stmtInsert->bindParam(':quality', $providedQuality);
            $stmtInsert->bindParam(':quantity', $providedQuantity);
            $stmtInsert->execute();

            if ($stmtInsert->rowCount() > 0) {
                echo json_encode(['message' => 'Data inserted successfully']);
            } else {
                echo json_encode(['message' => 'No insert performed.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error inserting data into gg_cart. ' . $e->getMessage()]);
        }
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



