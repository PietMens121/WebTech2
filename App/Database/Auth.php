<?php

namespace App\Database;

use App\Exceptions\Database\Auth\AlreadyLoggedInException;
use App\Exceptions\Database\Auth\LoginException;
use App\Exceptions\Database\Auth\UserNotFoundException;
use App\Exceptions\Database\Auth\WrongPasswordException;
use src\models\User;

class Auth
{
    private static Model $user;

    public static function user(): Model|null
    {
        if (isset(self::$user)) {
            return self::$user;
        }

        $user_id = $_SESSION['userID'] ?? null;
        if (!is_null($user_id)) {
            self::$user = (new User())->find($user_id);
            return self::$user;
        }

        return null;
    }

    /**
     * @param $username
     * @param $password
     * @return void
     * @throws LoginException
     */
    public static function login($username, $password): void
    {
        // Check if already logged in
        if (!is_null(self::user())) {
            throw new AlreadyLoggedInException();
        }

        // Get user from database
        $user = (new User())->whereOne('username', $username);

        // Check if user exists
        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        // Check if passwords match TODO: Add password hashing.
        if ($user->password != $password) {
            throw new WrongPasswordException();
        }

        // Log user in
        self::$user = $user;
        $_SESSION['userID'] = $user->id;
    }
}