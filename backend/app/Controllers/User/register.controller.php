<?php

class RegisterController
{
    private static $errors;

    public function addNewUser($username, $email, $password)
    {
        self::$errors = [];

        self::validateUsername($username);
        self::validateEmail($email);
        self::validatePassword($password);

        if (!empty(self::$errors)) {
            Response::setStatus(400);

            Response::setJsonContent([
                "messages" => self::$errors,
            ]);
        } else {
            Model::loadModel("User/register");

            RegisterModel::addNewUser($username, $email, $password);
        }
    }

    private static function validateUsername(string $username)
    {
        if (empty($username)) {
            self::$errors[] = 'Korisničko ime ne smije biti prazno.';
        }

        if (strlen($username) < 3) {
            self::$errors[] = 'Korisničko ime ne smije biti manje od 3 znaka';
        }

        if (strlen($username) > 30) {
            self::$errors[] = 'Korisničko ime ne smije biti veće od 30 znakova';
        }
    }

    private static function validateEmail(string $email)
    {
        if (empty($email)) {
            self::$errors[] = 'Email ne smije biti prazan.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::$errors[] = 'Neispravan format e-pošte.';
        }

        if (strlen($email) > 100) {
            self::$errors[] = 'Email ne smije biti veći od 100 znakova';
        }
    }

    private static function validatePassword(string $password)
    {
        if (empty($password)) {
            self::$errors[] = 'Lozinka ne smije biti prazna.';
        }

        if (strlen($password) < 8) {
            self::$errors[] = 'Lozinka mora imati barem 8 znakova.';
        }

        if (strlen($password) > 100) {
            self::$errors[] = 'Lozinka ne smije biti veći od 100 znakova';
        }
    }
}
