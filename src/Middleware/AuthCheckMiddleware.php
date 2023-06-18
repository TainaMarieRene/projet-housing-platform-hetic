<?php

namespace Middleware;

use Middleware\Middleware;

use Repository\AuthRepository;
use Repository\UserRepository;

use Model\UserModel;

class AuthCheckMiddleware implements Middleware{
    private AuthRepository $authRepository;
    private UserRepository $userRepository;
    private ?UserModel $user;

    public function __construct(
        private ?int $id = null,
        private ?string $agent = null,
        private ?string $token = null
    ){
        $this->authRepository = new AuthRepository();
        $this->userRepository = new UserRepository();
        $this->id = $id;
        $this->agent = $agent;
        $this->token = $token;

        if($this->isLoggedIn($this->id, $this->agent, $this->token)){
            $this->user = (new UserRepository())->getUserById($_COOKIE['aparisCookieUserID']);
        } else {
            setcookie('aparisCookieUserID', '', time()-(3600));
            setcookie('aparisCookieAgent', '', time()-3600);
            setcookie('aparisCookieToken', '', time()-3600);
            $this->user = null;
        }
    }

    private function isLoggedIn(?int $id, ?string $agent, ?string $token): bool{
        if($id && $this->authRepository->getAuthUserId($id, $agent, $token) === $this->id){
            $this->userRepository->updateLastSeen($id);
            return true;
        }
        return false;
    }

    public function getUser(): ?UserModel{
        return $this->user;
    }
}
