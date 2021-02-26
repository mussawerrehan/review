<?php


namespace App\Service;


use App\Repository\UserRepository;

class TokenHelper
{
    private $user;

    public function __construct(UserRepository $userRepository)
    {
        $this->user = $userRepository;
    }

    public function getUserFromToken($authrization)
    {
        $data = explode(' ',$authrization);
        $jwt = explode('.', $data[1]);
        // Extract the middle part, base64 decode, then json_decode it
        $userInfo = json_decode(base64_decode($jwt[1]), true);
        return $this->user->findOneBy(['email' => $userInfo['sub']]);
    }

}