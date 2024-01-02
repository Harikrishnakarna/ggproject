<?php

// Generate a random token
$token = bin2hex(random_bytes(32));

// Send the token in the response
echo json_encode(['token' => $token]);