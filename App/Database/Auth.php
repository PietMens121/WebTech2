<?php

namespace App\Database;

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

    public static function login($username, $password): bool
    {
        // Check if already logged in
        if (!is_null(self::user())) {
            return false; // TODO: Throw exception
        }

        // Get user from database
        $user = (new User())->whereOne('username', $username);

        // Check if user exists
        if (is_null($user)) {
            return false; // TODO: Throw exception
        }

        // Check if passwords match TODO: Add password hashing.
        if ($user->password != $password) {
            return false;
        }

        // Log user in
        self::$user = $user;
        $_SESSION['userID'] = $user->id;
        return true;
    }
}