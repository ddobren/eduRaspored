<?php

use Firebase\JWT\JWT;

class LoginController
{
    public function loginUser($email, $password)
    {
        Model::loadModel("User/login");

        $user = LoginModel::loginUser($email, $password);

        if (!$user) {
            Response::setStatus(401);
            Response::setJsonContent(["error" => "Pogrešan email ili lozinka."]);
            return;
        }

        $userId = $user['id'];
        $username = $user['username'];
        $email = $user['email'];

        $returnedToken = LoginModel::getUserToken($userId);

        if (LoginModel::isTokenExpired($returnedToken)) {
            LoginModel::updateToken($userId, $username, $email);
        }

        if (LoginModel::isTokenActive($userId)) {
            Response::setJsonContent(["error" => "Odjavite se iz druge sesije prije nego što se pokušate prijaviti ovdje."]);
            exit();
        } else {
            if (LoginModel::setTokenActive($userId)) {
                Response::setStatus(200);
                Response::setJsonContent(["token" => $returnedToken]);
            }
        }

    }
}
