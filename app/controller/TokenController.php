<?php

class TokenController
{
    private $tokenRepository;

    public function __construct()
    {
        $this->tokenRepository = new TokenRepository();
    }

    public function generate($userId, $userEmail)
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

        return $this->save($token);
    }

    private function save($token)
    {
        return $this->tokenRepository->save($token);
    }

    public function verify()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return false;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);

        return $this->tokenRepository->getByValue($token);
    }
}
