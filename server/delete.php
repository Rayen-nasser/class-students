<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: access, Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: DELETE");

$method = $_SERVER['REQUEST_METHOD'];

if($method === "OPTIONS") {
    die();
}

if($method !== 'DELETE') {
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Method Not Allowed. HTTP Method Should Be DELETE'
    ]);
    exit();
}

require 'db_connect.php';

$database = new Operations();
$conn = $database->dbConnection();

$id = $_GET['id'];

if(!isset($id)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Please provide the post ID'
    ]);
    exit();
}

try {
    $fetch_post = "SELECT * FROM `students` WHERE id = :id"; // Add a WHERE clause to specify the ID
    $fetch_stmt = $conn->prepare($fetch_post);
    $fetch_stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if($fetch_stmt->rowCount() > 0) {
        $delete_post = "DELETE FROM `students` WHERE id = :id"; // Add a WHERE clause to specify the ID
        $delete_post_stmt = $conn->prepare($delete_post);
        $delete_post_stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if($delete_post_stmt->execute()) {
            echo json_encode([
                'success' => 1,
                'message' => 'Record Deleted Successfully'
            ]);
            exit();
        } else {
            echo json_encode([
                'success' => 0,
                'message' => 'Could not delete. Something went wrong'
            ]);
            exit();
        }
    } else {
        echo json_encode([
            'success' => 0,
            'message' => 'Invalid Id. No posts'
        ]);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}
