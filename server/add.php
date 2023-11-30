<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: access, Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => "Bad request! Only POST method is allowed"
    ]);
    exit;
}

require 'db_connect.php';

$database = new Operations();
$conn = $database->dbConnection();

$data = json_decode(file_get_contents("php://input"));

// Check if 'hobbyField' exists and is an array, or set it to an empty array if not.
$hobbies = isset($data->hobbyField) && is_array($data->hobbyField) ? $data->hobbyField : [];

// Create a comma-separated string from the 'hobbies' array.
$hobbies_list = implode(', ', $hobbies);

// Check for missing or empty required fields.
if (
    !isset($data->first_name) ||
    !isset($data->last_name) ||
    !isset($data->email) ||
    !isset($data->profile) ||
    !isset($data->gender) ||
    !isset($data->country) ||
    empty(trim($data->first_name)) ||
    empty(trim($data->last_name)) ||
    empty(trim($data->email)) ||
    empty(trim($data->profile)) ||
    empty(trim($data->gender)) ||
    empty(trim($data->country))
) {
    echo json_encode([
        'success' => 0,
        'message' => 'Please enter compulsory fields'
    ]);
    exit;
}

try {
    $first_name = htmlspecialchars(trim($data->first_name));
    $last_name = htmlspecialchars(trim($data->last_name));
    $email = htmlspecialchars(trim($data->email));
    $profile = htmlspecialchars(trim($data->profile));
    $gender = $data->gender;
    $country = $data->country;

    $query = "INSERT INTO students (first_name, last_name, email, profile, gender, hobbies, country) 
              VALUES (:first_name, :last_name, :email, :profile, :gender, :hobbies, :country)";

    $stmt = $conn->prepare($query);

    $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':profile', $profile, PDO::PARAM_STR);
    $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindValue(':hobbies', $hobbies_list, PDO::PARAM_STR);
    $stmt->bindValue(':country', $country, PDO::PARAM_STR);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            'success' => 1,
            'message' => "Data Inserted Successfully"
        ]);
        exit;
    }

    echo json_encode([
        'success' => 0,
        'message' => "There is some problem in data inserting"
    ]);
    exit;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}
