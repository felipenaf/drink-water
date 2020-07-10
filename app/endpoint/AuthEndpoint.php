<?php

class AuthEndpoint
{
    public static function getResponse($uri, $method)
    {
        $parameter = isset($uri[1]) ? $uri[1] : '';
        $authController = new AuthController();

        switch ($method) {
            case 'POST':
                if (empty($parameter)) {
                    $entityBody = json_decode(file_get_contents('php://input'));

                    if (!empty($entityBody->email) && !empty($entityBody->password)) {
                        $user = new UserModel();
                        $user->setEmail($entityBody->email);
                        $user->setPassword($entityBody->password);

                        return $authController->login($user);
                    }

                    return [null, 400];
                }

                return [null, 404];
            break;

            default:
                return array('status' => 405);
            break;
        }
    }
}
