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
    $providedFullName = isset($postData['fullName']) ? $postData['fullName'] : null;
    $providedFlatBuilding = isset($postData['flatBuilding']) ? $postData['flatBuilding'] : null;
    $providedAreaStreetVillage = isset($postData['areaStreetVillage']) ? $postData['areaStreetVillage'] : null;
    $providedLandMark = isset($postData['landMark']) ? $postData['landMark'] : null;
    $providedPincode = isset($postData['pincode']) ? $postData['pincode'] : null;
    $providedTownAndCity = isset($postData['townAndCity']) ? $postData['townAndCity'] : null;
    $providedState = isset($postData['state']) ? $postData['state'] : null;
    $providedPhoneNo = isset($postData['phoneNo']) ? $postData['phoneNo'] : null;

    // Update data in gg_address
    try {
        $stmtUpdate = $pdo->prepare("UPDATE gg_address 
                                    SET fullName = :fullName, flatBuilding = :flatBuilding, areaStreetVillage = :areaStreetVillage,
                                    landMark = :landMark, pincode = :pincode, townAndCity = :townAndCity, state = :state,
                                    phoneNo = :phoneNo
                                    WHERE phone = :phone");

        $stmtUpdate->bindParam(':phone', $providedPhoneNumber);
        $stmtUpdate->bindParam(':fullName', $providedFullName);
        $stmtUpdate->bindParam(':flatBuilding', $providedFlatBuilding);
        $stmtUpdate->bindParam(':areaStreetVillage', $providedAreaStreetVillage);
        $stmtUpdate->bindParam(':landMark', $providedLandMark);
        $stmtUpdate->bindParam(':pincode', $providedPincode);
        $stmtUpdate->bindParam(':townAndCity', $providedTownAndCity);
        $stmtUpdate->bindParam(':state', $providedState);
        $stmtUpdate->bindParam(':phoneNo', $providedPhoneNo);

        $stmtUpdate->execute();

        echo json_encode(['message' => 'Data updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error updating data: ' . $e->getMessage()]);
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
