<?php

namespace App\Controllers;

use App\DatabaseConnection;
use App\Session;

class AuthController {

  function login($email, $password) {
    return DatabaseConnection::getConnection()->login($email, $password);
  }

  function register(
    $firstname,
    $lastname,
    $address,
    $email,
    $password
  ) {
    return DatabaseConnection::getConnection()->register(
      $firstname,
      $lastname,
      $address,
      $email,
      $password
    );
  }

  function logout() {
    Session::getSession()->logout();
  }

  function isAuthenticated() {
    return Session::getSession()->isAuthenticated();
  }

  function getAuthUser() {
    return Session::getSession()->getAuthUser();
  }
}
