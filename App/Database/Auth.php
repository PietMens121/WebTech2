<?php

namespace App\Database;

use App\Exceptions\Database\Auth\AlreadyLoggedInException;
use App\Exceptions\Database\Auth\LoginException;
use App\Exceptions\Database\Auth\RegisterException;
use App\Exceptions\Database\Auth\UserAlreadyExistsException;
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

        // Check if passwords match
        if ($user->password != hash('sha256', $password)) {
            throw new WrongPasswordException();
        }

        // Log user in
        self::$user = $user;
        $_SESSION['userID'] = $user->id;
    }

    /**
     * @param string $username Username of the new user.
     * @param string $password Password of the new user.
     * @return void
     * @throws UserAlreadyExistsException
     */
    public static function register(string $username, string $password, int $role): void
    {
        // Check if user already exists
        $user = (new User())->whereOne('username', $username);
        if ($user) {
            throw new UserAlreadyExistsException();
        }

        // Register user
        $user = new User();
        $user->username = $username;
        $user->password = hash('sha256', $password);
        $user->role_id = $role;
        $user->save();
    }
}