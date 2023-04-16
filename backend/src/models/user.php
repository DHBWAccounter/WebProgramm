<?php

namespace App\Models;

class User {
  public $id;
  public $firstname;
  public $lastname;
  public $address;
  public $email;
  public $password;

  function __construct($id, $firstname, $lastname, $address, $email, $password)
  {
    $this->id = $id;
    $this->firstname = $firstname;
    $this->lastname = $lastname;
    $this->address = $address;
    $this->email = $email;
    $this->password = $password;
  }
}
