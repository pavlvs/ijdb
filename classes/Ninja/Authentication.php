<?php
namespace Ninja;

class Authentication
{
    private $users;
    private $userNameColumn;
    private $passwordColumn;

    public function __construct(DatabaseTable $users, $usernameColumn, $passwordColumn)
    {
        session_start();
        $this->users          = $users;
        $this->userNameColumn = $usernameColumn;
        $this->passwordColumn = $passwordColumn;
    }

    public function login($username, $password)
    {
        $user = $this->users->find($this->userNameColumn, strtolower($username));

        if (!empty($user) && password_verify($password, $user[0][$this->passwordColumn])) {
            session_regenerate_id();
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $user[0][$this->passwordColumn];
            return true;
        } else {
            return false;
        }
    }

    public function isLoggedIn()
    {
        if (empty($_SESSION['username'])) {
            return false;
        }

        $user = $this->users->find($this->userNameColumn, strtolower($_SESSION["username"]));

        if (!empty($user) && $user[0][$this->passwordColumn] === $_SESSION["password"]) {
            return true;
        } else {
            return false;
        }
    }
}
