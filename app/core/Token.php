<?php

class Token
{
    public static function generate($userId, $userEmail)
    {
        $key = '';

        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $payload = [
            'exp' => (new DateTime('now'))->getTimestamp(),
            'uid' => $userId,
            'email' => $userEmail
        ];

        //JSON
        $header = json_encode($header);
        $payload = json_encode($payload);

        //Base 64
        $header = base64_encode($header);
        $payload = base64_encode($payload);

        //Sign
        $sign = hash_hmac('sha256', $header . "." . $payload, $key, true);
        $sign = base64_encode($sign);

        //Token
        $token = $header . '.' . $payload . '.' . $sign;

        return $token;
    }

    public static function verify($header)
    {
        $headers = apache_request_headers();
        return in_array("Bearer " . $header, $headers);
    }
}
