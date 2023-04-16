<?php

namespace App\Interface;

header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");

require_once('../src/conn.php');
require_once('../src/session.php');
require_once('../src/controllers/booking-controller.php');

header('Content-Type: application/json');

use App\Controllers\BookingController;
use App\DatabaseConnection;
use App\Session;
use Exception;

try {
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $bookingController = new BookingController();

    $bookings = [];
    if (!isset($_GET["bike-id"])) {

      if(!Session::getSession()->isAuthenticated()) {
        throw new Exception("Unauthenticated.");
      }

      $userId = Session::getSession()->getAuthUser()->id;
      $bookings = $bookingController->getUserBookings($userId);
    } else {
      $bikeId = $_GET["bike-id"];
      $bookings = $bookingController->getBikeBookings($bikeId);
    }

    echo json_encode(["error" => false, "bookings" => $bookings]);
    
  } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(!Session::getSession()->isAuthenticated()) {
      throw new Exception("Unauthenticated.");
    }

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $userId = Session::getSession()->getAuthUser()->id;
    
    DatabaseConnection::getConnection()->book($data->bikeId, $userId, $data->for);

    echo json_encode(["error" => false, "status" => "Booked successfully."]);
  } else {
    echo json_encode(["error" => true, "errorMsg" => "Request not valid."]);
  }
} catch(Exception $ex) {
  http_response_code(400);
  echo json_encode(["error" => true, "errorMsg" => $ex->getMessage()]);
}
