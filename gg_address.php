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

    // Retrieve address data from the JSON payload
    $providedFullName = isset($postData['fullName']) ? $postData['fullName'] : null;
    $providedFlatBuilding = isset($postData['flatBuilding']) ? $postData['flatBuilding'] : null;
    $providedAreaStreetVillage = isset($postData['areaStreetVillage']) ? $postData['areaStreetVillage'] : null;
    $providedLandMark = isset($postData['landMark']) ? $postData['landMark'] : null;
    $providedPincode = isset($postData['pincode']) ? $postData['pincode'] : null;
    $providedTownAndCity = isset($postData['townAndCity']) ? $postData['townAndCity'] : null;
    $providedState = isset($postData['state']) ? $postData['state'] : null;
    $providedPhoneNo = isset($postData['phoneNo']) ? $postData['phoneNo'] : null;

    // Check if the provided address data exists in gg_address
    $stmtCheckAddress = $pdo->prepare("SELECT COUNT(*) FROM gg_address WHERE phone = :phone");
    $stmtCheckAddress->bindParam(':phone', $providedPhoneNumber);
    $stmtCheckAddress->execute();
    $addressExists = $stmtCheckAddress->fetchColumn();

    if ($addressExists) {
        // Update address data in gg_address
        try {
            $stmtUpdateAddress = $pdo->prepare("UPDATE gg_address SET fullName = :fullName, flatBuilding = :flatBuilding, areaStreetVillage = :areaStreetVillage, landMark = :landMark, pincode = :pincode, townAndCity = :townAndCity, state = :state, phoneNo = :phoneNo WHERE phone = :phone");
            $stmtUpdateAddress->bindParam(':phone', $providedPhoneNumber);
            $stmtUpdateAddress->bindParam(':fullName', $providedFullName);
            $stmtUpdateAddress->bindParam(':flatBuilding', $providedFlatBuilding);
            $stmtUpdateAddress->bindParam(':areaStreetVillage', $providedAreaStreetVillage);
            $stmtUpdateAddress->bindParam(':landMark', $providedLandMark);
            $stmtUpdateAddress->bindParam(':pincode', $providedPincode);
            $stmtUpdateAddress->bindParam(':townAndCity', $providedTownAndCity);
            $stmtUpdateAddress->bindParam(':state', $providedState);
            $stmtUpdateAddress->bindParam(':phoneNo', $providedPhoneNo);
            $stmtUpdateAddress->execute();

            if ($stmtUpdateAddress->rowCount() > 0) {
                echo json_encode(['message' => 'Address data updated successfully']);
            } else {
                echo json_encode(['message' => 'No update performed for address data.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error updating address data in gg_address. ' . $e->getMessage()]);
        }
    } else {
        // Insert address data into gg_address
        try {
            $stmtInsertAddress = $pdo->prepare("INSERT INTO gg_address (phone, fullName, flatBuilding, areaStreetVillage, landMark, pincode, townAndCity, state, phoneNo) VALUES (:phone, :fullName, :flatBuilding, :areaStreetVillage, :landMark, :pincode, :townAndCity, :state, :phoneNo)");
            $stmtInsertAddress->bindParam(':phone', $providedPhoneNumber);
            $stmtInsertAddress->bindParam(':fullName', $providedFullName);
            $stmtInsertAddress->bindParam(':flatBuilding', $providedFlatBuilding);
            $stmtInsertAddress->bindParam(':areaStreetVillage', $providedAreaStreetVillage);
            $stmtInsertAddress->bindParam(':landMark', $providedLandMark);
            $stmtInsertAddress->bindParam(':pincode', $providedPincode);
            $stmtInsertAddress->bindParam(':townAndCity', $providedTownAndCity);
            $stmtInsertAddress->bindParam(':state', $providedState);
            $stmtInsertAddress->bindParam(':phoneNo', $providedPhoneNo);
            $stmtInsertAddress->execute();

            if ($stmtInsertAddress->rowCount() > 0) {
                echo json_encode(['message' => 'Address data inserted successfully']);
            } else {
                echo json_encode(['message' => 'No insert performed for address data.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error inserting address data into gg_address. ' . $e->getMessage()]);
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
\\{
    "token":"4ef067042c2284309bdabd0e9a674b5e9738fc60d439989c4f4f2a6b5ca3920f",
    "phone":"7680009838",
    "fullName":"hari",
    "flatBuilding":"3-40",
    "areaStreetVillage":"main ramalayam",
    "landMark":"beside third line",
    "pincode":"500081",
    "townAndCity":"madhapur",
    "state":"telagana",
    "phoneNo":"1238756432"
}
//
