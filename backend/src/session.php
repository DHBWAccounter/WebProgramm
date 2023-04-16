<?php

namespace App;

class Session {
    private static $obj;

    function saveUserDataToCookie(string $id) {
        $this->startSession();
        $_SESSION['userId'] = $id;
    }

    function getAuthUser() {
        $this->startSession();
        return DatabaseConnection::getConnection()->getUser($_SESSION['userId']);
    }

    function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function destroySession() {
        session_destroy();
    }

    function logout() {
        $this->startSession();
        $this->destroySession();
    }

    function isAuthenticated() {
        $this->startSession();
        return isset($_SESSION['userId']);
    }

    public static function getSession() {
        if (!isset(self::$obj)) {
            self::$obj = new Session();
        }
         
        return self::$obj;
      }
}
