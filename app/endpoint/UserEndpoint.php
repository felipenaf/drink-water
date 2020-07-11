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

                    return [null, 400];
                } else {
                    return $userController->getAll();
                }

                return [null, 404];
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

                    return [null, 400];
                }

                return [null, 404];
            break;

            case 'PUT':
                if (!empty($parameter)) {
                    $entityBody = json_decode(file_get_contents('php://input'));

                    if (is_numeric($parameter)) {
                        if (!empty($entityBody->name) ||
                            !empty($entityBody->email) ||
                            !empty($entityBody->password)) {

                            $oldUser = $userController->getByIdForUpdate($parameter);

                            $user = new UserModel();

                            $user->setId($parameter);

                            $user->setName(
                                !empty($entityBody->name) ?
                                $entityBody->name :
                                $oldUser['name']
                            );

                            $user->setEmail(
                                !empty($entityBody->email) ?
                                $entityBody->email :
                                $oldUser['email']
                            );

                            $user->setPassword(
                                !empty($entityBody->password) ?
                                $entityBody->password :
                                $oldUser['password']
                            );

                            return $userController->update($user);
                        }

                        return [null, 400];
                    }

                    return [null, 400];
                }

                return [null, 404];
            break;

            case 'DELETE':
                if (!empty($parameter) && is_numeric($parameter)) {
                    return $userController->delete($parameter);
                }

                return [null, 404];
            break;

            default:
                return [null, 405];
            break;
        }
    }
}
