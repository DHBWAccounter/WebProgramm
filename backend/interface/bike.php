<?php

namespace App\Interface;

header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");

require_once('../src/conn.php');
require_once('../src/session.php');
require_once('../src/controllers/bike-controller.php');

header('Content-Type: application/json');

use App\Controllers\BikeController;
use App\Session;
use Exception;

try {
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {    
    $bikeController = new BikeController();
    if (isset($_GET["bike-id"])) {
      
      if(!Session::getSession()->isAuthenticated()) {
        throw new Exception("Unauthenticated.");
      }
      
      $bikeId = $_GET["bike-id"];
      $bike = $bikeController->getBike($bikeId);

      echo json_encode(["error" => false, "bike" => $bike]);
      
    } else {
      $bikes = $bikeController->getAvailableBikes();
      echo json_encode(["error" => false, "bikes" => $bikes]);
    }
  } else {
    echo json_encode(["error" => true, "errorMsg" => "Request not valid."]);
  }

} catch(Exception $ex) {
  http_response_code(400);
  echo json_encode(["error" => true, "errorMsg" => $ex->getMessage()]);
}
