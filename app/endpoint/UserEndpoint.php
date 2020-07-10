<?php

class UserEndpoint
{
    public static function getResponse($uri, $method)
    {
        $parameter = isset($uri[1]) ? $uri[1] : '';
        $userController = new UserController();

        switch ($method) {
            case 'GET':
                if (!empty($parameter)) {
                    if (is_numeric($parameter)) {
                        return $userController->getById($parameter);
                    }

                    return null;
                } else {
                    return $userController->getAll();
                }

                return null;
            break;

            case 'POST':
                if (empty($parameter)) {
                    $entityBody = json_decode(file_get_contents('php://input'));

                    if (!empty($entityBody->name) &&
                        !empty($entityBody->email) &&
                        !empty($entityBody->password)) {

                        $user = new UserModel();
                        $user->setName($entityBody->name);
                        $user->setEmail($entityBody->email);
                        $user->setPassword($entityBody->password);

                        return $userController->save($user);
                    }
                }

                return null;
            break;

            case 'PUT':
                # When the method is PUT, alters an existing client
                return self::doPut($route);
                break;
            case 'DELETE':
                # When the method is DELETE, excludes an existing client.
                return self::doDelete($route);
                break;
            default:
                # When the method is different of the previous methods, return an error message.
                return array('status' => 405);
                  break;
        }
    }
}
