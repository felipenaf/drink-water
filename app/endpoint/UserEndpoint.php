<?php

class UserEndpoint
{
    public static function getResponse($uri, $method)
    {
        $parameter = isset($uri[1]) ? $uri[1] : '';

        switch ($method) {
            case 'GET':
                $userController = new UserController();

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
                # When the method is POST, includes a new client
                if(empty($route[1])){
                    return self::doPost();
                }else{
                    return $arr_json = array('status' => 404);
                }
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
