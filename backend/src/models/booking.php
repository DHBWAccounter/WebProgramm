<?php

namespace App\Models;

class Booking {
  public $id;
  public $date;
  public $expiration;
  public $price;
  public $state;

  function __construct($id, $date, $expiration, $price, $state)
  {
    $this->id = $id;
    $this->date = $date;
    $this->expiration = $expiration;
    $this->price = $price;
    $this->state = $state;
  }
}
