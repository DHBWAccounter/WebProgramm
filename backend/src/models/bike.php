<?php

namespace App\Models;

class Bike {
  public $id;
  public $type;
  public $price;
  public $description;

  function __construct($id, $type, $price, $description)
  {
    $this->id = $id;
    $this->type = $type;
    $this->price = $price;
    $this->description = $description;
  }
}
