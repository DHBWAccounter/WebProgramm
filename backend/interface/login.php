<?php

namespace App\Interface;

header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");

require_once('../src/conn.php');
require_once('../src/controllers/auth-controller.php');

header('Content-Type: application/json');

use App\Controllers\AuthController;
use Exception;

try {
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $authController = new AuthController();
    $isAuthenticated = $authController->isAuthenticated();
    
    $authUser = null;
    if ($isAuthenticated){
      $authUser = $authController->getAuthUser();
    }

    echo json_encode(["isAuthenticated" => $isAuthenticated, "authUser" => $authUser]);

  } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
  
    $authController = new AuthController();
    $authController->login($data->email, $data->password);

    echo json_encode(["error" => false, "status" => "Success."]);

  } else {
    echo json_encode(["error" => true, "status" => "Request not valid."]);
  }
} catch(Exception $ex) {
  http_response_code(400);
  echo json_encode(["error" => true, "errorMsg" => $ex->getMessage()]);
}
