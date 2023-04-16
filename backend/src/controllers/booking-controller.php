<?php

namespace App\Controllers;

use App\DatabaseConnection;

class BookingController {
  function book($bikeId, $userId, $for) {
    return DatabaseConnection::getConnection()->book($bikeId, $userId, $for);
  }

  function getUserBookings($userId) {
    return DatabaseConnection::getConnection()->getUserBookings($userId);
  }

  function getBikeBookings($bikeId) {
    return DatabaseConnection::getConnection()->getBikeBookings($bikeId);
  }
}
