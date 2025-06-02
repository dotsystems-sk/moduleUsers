<?php	
    namespace Dotsystems\App\Modules\Users\Controllers;
    use Dotsystems\App\DotApp;
	use Dotsystems\App\Parts\Middleware;
	use Dotsystems\App\Parts\Response;
	use Dotsystems\App\Parts\Renderer;
	use Dotsystems\App\Parts\Router;
    use Dotsystems\App\Parts\Validator;
    use Dotsystems\App\Parts\TOTP;
    use Dotsystems\App\Parts\Auth;
    use Dotsystems\App\Modules\Users\Module;
    
    class CreateUser extends \Dotsystems\App\Parts\Controller {

        private static function seoVar() {
            $seo = [];
            $seo['title'] = "DotApp Example: Advanced User Authentication with 2FA and QR Codes";
            $seo['description'] = "Explore how to build a secure user authentication module in the DotApp PHP framework, featuring registration, login, two-factor authentication (2FA) with QR codes and email, and a reusable module design for easy integration across projects.";
            $seo['keywords'] = "DotApp framework, user authentication, two-factor authentication, 2FA, QR codes, PHP module, reusable module, secure login, registration, middleware, DotApp philosophy";
            return $seo;
        }
        

        public static function url() {
            // User logout logic here
            $url = [];
            $url['defaultUrl'] = Module::getStatic("defaultUrl");
            $url['loginUrl'] = Module::getStatic("loginUrl");
            $url['loginUrl2fa'] = Module::getStatic("loginUrl2fa");
            $url['loginUrl2faEmail'] = Module::getStatic("loginUrl2faEmail");
            $url['registerUrl'] = Module::getStatic("registerUrl");
            $url['logoutUrl'] = Module::getStatic("logoutUrl");
            return $url;
        }
        public static function register($request, Renderer $renderer) {
            $js = '<script src="/assets/modules/Users/users.js"></script>';
            $css = '<link rel="stylesheet" href="/assets/modules/Users/users.css">';
            $viewcode = $renderer->module(self::modulename())
                        ->setView("index")
                        ->setViewVar("seo",static::seoVar())
                        ->setViewVar("url",static::url())
                        ->setViewVar("js",$js)
                        ->setViewVar("css",$css)
                        ->setLayout("register")->renderView();
            return $viewcode;
        }

        public static function registerPost($request, Renderer $renderer) {
            if ($request->crcCheck()) {
                $answer = $request->form(['POST'],"CSRF", function($request) {
                    // User registration logic here
                    $answer = [];
                    $email = $request->data(true)['data']['email'];
                    if (Validator::isEmail($email)) {
                        // $username je povinny, takze aj ked planujeme prihlasovanie cez EMAIL musime ho vyplnit. 
                        $username = md5($email.bin2hex(random_bytes(16)));
                        $password = $request->data(true)['data']['password'];
                        if (Validator::isStrongPassword($password)) {
                            // Vytvorime uzivatela...
                            $userSettings = [];
                            $userSettings['tfa_email'] = 1; // Vyzadujeme 2 Faktor, povolujeme metodu emailom
                            $userSettings['tfa_auth'] = 1; // Vyzadujeme 2 Faktor, povolujeme metodu google authenticator
                            $userSettings['tfa_auth_secret'] = TOTP::newSecret(); // generujeme novy secret
                            $userSettings['tfa_auth_secret_confirmed'] = 0; // 2_fa este nebol potvrdeny
                            // Vytvorime uzivatela
                            $user = Auth::createUser($username, $password, $email, $userSettings);
                            switch ($user['error']) {
                                case 0:
                                    $answer['code'] = 200;
                                    $body = [];
                                    $body['status'] = 1;
                                    $body['message'] = "User registered successfully! Now you can login using your email and password.";
                                    $body['redirectTo'] = Module::getStatic("loginUrl");
                                    $answer['body'] = $body;                                    
                                    break;
                                case 1:
                                    $body = [];
                                    $body['status'] = 0;
                                    $body['error'] = 1;
                                    $body['errorNo'] = 3;
                                    $body['message'] = "Email already exists in database!";
                                    $answer['code'] = 200;
                                    $answer['body'] = $body;
                                    break;
                                case 99:
                                    $body = [];
                                    $body['status'] = 0;
                                    $body['error'] = 1;
                                    $body['errorNo'] = 4;
                                    $body['message'] = "Unknown error, try later !";
                                    $answer['code'] = 200;
                                    $answer['body'] = $body;
                                    break;
                            }                           
                        } else {
                            $body = [];
                            $body['status'] = 0;
                            $body['error'] = 1;
                            $body['errorNo'] = 2;
                            $body['message'] = "Enter strong password !";
                            $answer['code'] = 200;
                            $answer['body'] = $body;
                        }

                    } else {
                        $body = [];
                        $body['status'] = 0;
                        $body['error'] = 1;
                        $body['errorNo'] = 1;
                        $body['message'] = "Enter valid email address !";
                        $answer['code'] = 200;
                        $answer['body'] = $body;
                    }
                    return $answer;
                }, function() {
                    // CSRF check failed
                    $body = [];
                    $body['status'] = 0;
                    $body['error'] = 1;
                    $body['errorNo'] = 99;
                    $body['message'] = "CSRF check failed !";
                    $answer['code'] = 403;
                    $answer['body'] = $body;
                    return $answer;
                });
                return DotApp::DotApp()->ajaxReply($answer['body'], $answer['code']);
            }
        }          	
                
    }
?>