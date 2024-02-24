<?php

declare(strict_types=1);

class LogoutController
{
    public function logoutUser()
    {
        Model::loadModel("User/logout");

        LogoutModel::logoutUser();
    }
}
