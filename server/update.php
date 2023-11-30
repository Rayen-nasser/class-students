<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: access, Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: PUT");

$method = $_SERVER['REQUEST_METHOD'];

if($method === "OPTIONS") {
    die();
}
require 'db_connect.php';


if($method !== 'PUT') {
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Method Not Allowed. HTTP Method Should Be PUT'
    ]);
    exit();
}

$database = new Operations();
$conn = $database->dbConnection();
$data = json_decode(file_get_contents("php://input"));


$hobbies = $data->hobbyField;
//sport,runing
$hobbies_list = "";
foreach($hobbies as $hobby){
    $hobbies_list .= $hobby . ",";
}
// print_r($hobbies_list);
// print_r($data);
// die();


if(!isset($data->id)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Please enter correct students ID'
    ]);
    exit();
}

try {
    $fetch_post = "SELECT * FROM `students` WHERE id = :id";
    $fetch_stmt = $conn->prepare($fetch_post);
    $fetch_stmt->bindValue(':id', $data->id, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) {

        // $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);

        $update_post = "UPDATE  `students` SET
            first_name = :first_name, 
            last_name = :last_name,
            email = :email,
            profile = :profile,
            gender = :gender,
            hobbies = '$hobbies_list',
            country = :country
         WHERE id = :id";
        $update_stmt = $conn->prepare($update_post);
        $update_stmt->bindValue(':id', $data->id, PDO::PARAM_INT);
        $update_stmt->bindValue(':first_name',  $data->first_name, PDO::PARAM_STR);
        $update_stmt->bindValue(':last_name',  $data->last_name, PDO::PARAM_STR);
        $update_stmt->bindValue(':email',  $data->email, PDO::PARAM_STR);
        $update_stmt->bindValue(':profile',  $data->profile, PDO::PARAM_STR);
        $update_stmt->bindValue(':gender',  $data->gender, PDO::PARAM_STR);
        $update_stmt->bindValue(':country',  $data->country, PDO::PARAM_STR);

        if ($update_stmt->execute()) {
            echo json_encode([
                'success' => 1,
                'message' => 'Record UPDATED Successfully'
            ]);
            exit();
        } else {
            echo json_encode([
                'success' => 0,
                'message' => 'Could not update. Something went wrong'
            ]);
            exit();
        }
    } else {
        echo json_encode([
            'success' => 0,
            'message' => 'Invalid Id. No student found'
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}
