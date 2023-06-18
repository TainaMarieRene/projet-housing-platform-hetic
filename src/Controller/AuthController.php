<?php 

namespace Controller;
use Controller\Controller;
use App\Request;
use Router\Route;

// Services
use Service\AuthService;

// Model
use Model\UserModel;

class AuthController extends Controller{

    public function register(Request $request, Route $route): void{
        $error = [];
        $user = null;

        if($this->userLoggedIn){
            header("Location: home");
            exit;
        }

        switch($request->getMethod()){
            case "POST":
                $authService = new AuthService();
                // Check Informations
                [$error[], $firstname] = $authService->checkFirstname($request->getRawBody()['firstname']);
                [$error[], $lastname] = $authService->checkLastname($request->getRawBody()['lastname']);
                [$error[], $mail] = $authService->checkMail($request->getRawBody()['mail'], 'register');
                [$error[], $birthdate] = $authService->checkBirthdate($request->getRawBody()['birthdate']);
                [$error[], $password] = $authService->checkPasswords($request->getRawBody()['password'], $request->getRawBody()['confpsd']);
                
                // Create user
                $user = (new UserModel())->setFirstname($firstname)->setLastname($lastname)->setMail($mail)->setPassword($password)->setBirthdate($birthdate);
                
                // Clean array (false indice)
                $error = array_filter($error, function ($value) { return $value; });
                if(!$error){
                    // Register a user
                    $authService->registerUser($user);
                    $user = $authService->loginUser($user->getMail());
                    $token = $authService->createToken($user->getId(), $request->getHeaders()['HTTP_USER_AGENT']);
                    
                    // Create Cookies
                    $authService->setCookies($user->getId(), $request->getHeaders()['HTTP_USER_AGENT'], $token);
                    
                    header("Location: home");
                    exit();
                }
                break;
        }

        $this->updateStyles(['auth.css']);
        $this->render("register.php",  $this->styles ,[
            "route" => $route,
            "request" => $request,
            "error" => $error,
            "user" => $user
        ]);
    }

    public function login(Request $request, Route $route): void{
        $error = [];
        $user = null;

        if($this->userLoggedIn){
            header("Location: home");
            exit;
        }

        switch($request->getMethod()){
            case "POST":
                $authService = new AuthService();
                // Check Informations
                [$error[], $mail] = $authService->checkMail($request->getRawBody()['mail'], 'login');
                [$error[], $password] = $authService->checkPassword($request->getRawBody()['password']);
                $error[] = $authService->verifyPassword($mail, $password);
                
                // Clean array (false indice)
                $error = array_filter($error, function ($value) { return $value; });
                if(!$error){
                    // Login a user
                    $user = $authService->loginUser($mail);
                    $token = $authService->createToken($user->getId(), $request->getHeaders()['HTTP_USER_AGENT']);

                    // Create Cookies
                    $authService->setCookies($user->getId(), $request->getHeaders()['HTTP_USER_AGENT'], $token);
                    
                    header("Location: home");
                    exit;
                    break;
                }
                $user = (new UserModel())->setMail($mail);
                break;
        }

        $this->updateStyles(['auth.css']);
        $this->render("login.php",  $this->styles ,[
            "route" => $route,
            "request" => $request,
            "error" => $error,
            "user" => $user
        ]);
    }

    public function logout(Request $request, Route $route): void{
        if($_COOKIE['aparisCookieUserID'] && $_COOKIE['aparisCookieAgent']){
            (new AuthService())->clearSession($_COOKIE['aparisCookieUserID'], $_COOKIE['aparisCookieAgent']);
        }
        header("Location: login");
        exit;
    }
}
