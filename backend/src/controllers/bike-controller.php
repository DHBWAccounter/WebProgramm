<?php

namespace App\Controllers;

use App\DatabaseConnection;

class BikeController {
  function getAvailableBikes() {
    return DatabaseConnection::getConnection()->getAvailableBikes();
  }

  function getBike($bikeId) {
    return DatabaseConnection::getConnection()->getBike($bikeId);
  }
}
