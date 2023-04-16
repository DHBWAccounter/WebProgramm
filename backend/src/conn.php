<?php

namespace App;

require_once('session.php');
require_once('models/booking.php');
require_once('models/user.php');
require_once('models/bike.php');
require_once('enums/booking-state.php');

use App\Enums\BookingState;
use App\Models\Bike;
use App\Models\Booking;
use App\Models\User;
use Exception;
use mysqli;

class DatabaseConnection {
  private static $obj;

  private $host = 'localhost';
  private $dbUsername = 'lucass';
  private $dbPassword = 'TestTest';
  private $dbname = 'homework';
  private $conn;

  function __construct()
  {
    $this->connect(); 
  }

  public function connect() {
    $this->conn = new mysqli($this->host, $this->dbUsername, $this->dbPassword, $this->dbname);

    if ($this->conn->connect_error) {
      throw new Exception($this->conn->connect_error);
    }
    // Check connection
    if ($this->conn->connect_error) {
      throw new Exception($this->conn->connect_error);
    }
  }

  public function closeConnection() {
      // Close the database connection
      $this->conn->close();
  }

  public static function getConnection() {
    if (!isset(self::$obj)) {
        self::$obj = new DatabaseConnection();
    }
    
    return self::$obj;
  }
  
  function login(string $email, string $password) {
    $stmt = $this->conn->prepare('SELECT id, firstname, lastname FROM users WHERE email = ? AND password = ?');
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      Session::getSession()->saveUserDataToCookie($row['id']);
      $stmt->close();
    } else {
      $stmt->close();
      throw new Exception('Invalid email or password.');
    }
  }
  
  function register(
    string $firstname, 
    string $lastname, 
    string $address, 
    string $email, 
    string $password
  ) {
    $stmt = $this->conn->prepare("INSERT INTO users (firstname, lastname, address, email, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstname, $lastname, $address, $email, $password);
  
    if ($stmt->execute()) {
      $stmt->close();
    } else {
      $stmt->close();
      throw new Exception($stmt->error);
    }
  }

  function getUserBookings($userId) {
    $stmt = $this->conn->prepare('SELECT u.id as user_id, b.id as booking_id, date, expiration, price, state FROM bookings as b JOIN users as u ON (b.user_id = u.id) WHERE u.id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
      array_push($bookings, new Booking($row['booking_id'], $row['date'], $row['expiration'], $row['price'], $row['state']));
    }
    $stmt->close();
    return $bookings;
  }

  function getBikeBookings($bikeId) {
    $stmt = $this->conn->prepare('SELECT bk.id as bike_id, b.id as booking_id, date, expiration, b.price, state FROM bookings as b JOIN bikes as bk ON (b.bike_id = bk.id) WHERE bk.id = ?');
    $stmt->bind_param('i', $bikeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
      array_push($bookings, new Booking($row['booking_id'], $row['date'], $row['expiration'], $row['price'], $row['state']));
    }
    $stmt->close();
    return $bookings;
  }

  function getBike($bikeId) {
    $stmt = $this->conn->prepare('SELECT id, type, price, description FROM bikes WHERE id = ?');
    $stmt->bind_param('s', $bikeId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $stmt->close();
      return new Bike($row['id'], $row['type'], $row['price'], $row['description']);
    } else {
      $stmt->close();
      throw new Exception('Bike not found.');
    }
  }

  function getAvailableBikes() {
    $stmt = $this->conn->prepare('SELECT id, type, price, description FROM bikes');
    $stmt->execute();
    $result = $stmt->get_result();
    $bikes = [];
    while ($row = $result->fetch_assoc()) {
      array_push($bikes, new Bike($row['id'], $row['type'], $row['price'], $row['description']));
    }
    $stmt->close();
    return $bikes;
  }

  function getUser($userId) {
    $stmt = $this->conn->prepare('SELECT id, firstname, lastname, address, email, password FROM users WHERE id = ?');
    $stmt->bind_param('s', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $stmt->close();
      return new User($row['id'], $row['firstname'], $row['lastname'], $row['address'], $row['email'], $row['password']);
    } else {
      $stmt->close();
      throw new Exception('User not found.');
    }
  }

  function book($bikeId, $userId, $for) {
    $bike = $this->getBike($bikeId);
    $bikeBookings = $this->getBikeBookings($bikeId);
    
    // Check if bike is already booked
    foreach ($bikeBookings as $booking) {
      if ($booking->state == BookingState::BOOKED) {
        throw new Exception('Bike already booked');
      }
    }

    $todayDate = date("Y/m/d");
    $expirationDate = date("Y/m/d", strtotime(" +". $for . " months"));
    $bikePrice = $bike->price;
    $bikePriceForTimeframe = $bikePrice * $for;
    $bookingStatus = BookingState::BOOKED;

    $stmt = $this->conn->prepare("INSERT INTO bookings (`date`, expiration, price, `state`, user_id, bike_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisii", $todayDate, $expirationDate, $bikePriceForTimeframe, $bookingStatus, $userId, $bikeId);

    if ($stmt->execute()) {
      $stmt->close();
    } else {
      $stmt->close();
      throw new Exception($stmt->error);
    }
  }
}
