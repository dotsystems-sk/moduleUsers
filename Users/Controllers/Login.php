<?php	
    namespace Dotsystems\App\Modules\Users\Controllers;
    use Dotsystems\App\DotApp;
	use Dotsystems\App\Parts\Middleware;
	use Dotsystems\App\Parts\Response;
	use Dotsystems\App\Parts\Renderer;
	use Dotsystems\App\Parts\Router;
    use Dotsystems\App\Parts\Validator;
    use Dotsystems\App\Parts\TOTP;
    use Dotsystems\App\Parts\QR;
    use Dotsystems\App\Parts\Auth;
    use Dotsystems\App\Parts\Config;
    use Dotsystems\App\Parts\DB;
    use Dotsystems\App\Modules\Users\Module;
    
    class Login extends \Dotsystems\App\Parts\Controller {

        public static function drop($request, Renderer $renderer) {
            // Drop anything :) God for form error callbacks
            return false;
        }

        public static function index($request, Renderer $renderer) {
            $viewcode = $renderer->module(self::modulename())
                        ->setView("index")
                        ->setViewVar("seo",static::seoVar())
                        ->setViewVar("url",self::call("Users:CreateUser@url"))
                        ->setViewVar("email",Auth::attributes()['email'])
                        ->setLayout("index.logged")->renderView();
            return $viewcode;
        }

        private static function seoVar() {
            $seo = [];
            $seo['title'] = "DotBridge Example: Seamless Backend-to-Frontend Integration with DotApp Framework";
            $seo['description'] = "This example showcases how to use DotBridge in the DotApp Framework to effortlessly connect PHP backend with JavaScript frontend using secure AJAX requests. Learn how to implement simple tags like {{ dotbridge:on(click) }} for dynamic, secure, and efficient form handling.";
            $seo['keywords'] = "DotBridge, DotApp Framework, secure AJAX, PHP JavaScript integration, dynamic forms, dotapp.js, secure communication, template tags, backend frontend bridge";
            return $seo;
       }
        
        public static function login($request, Renderer $renderer) {
            if (Auth::loggedStage() == 2) {
                header("Location: ".Module::getStatic("loginUrl2fa"));
                exit();
            }
            if (Auth::isLogged()) {
                header("Location: ".Module::getStatic("defaultUrl"));
                exit();
            }
            $viewcode = $renderer->module(self::modulename())
                        ->setView("index")
                        ->setViewVar("seo",static::seoVar())
                        ->setViewVar("url",self::call("Users:CreateUser@url"))
                        ->setLayout("login")->renderView();
            return $viewcode;
        }

        public static function loginPost($request) {
            if (Auth::loggedStage() == 2 || Auth::isLogged()) {
                $answer['code'] = 200;
                $body = [];
                $body['status'] = 0;
                $body['error'] = 1;
                $body['errorNo'] = 1;
                $body['message'] = "User already logged in or two-factor authentication is not finished. Please log out and try again.";
                $body['redirectTo'] = Module::getStatic("loginUrl");
                $answer['body'] = $body;                                    
            } else {
                if ($request->crcCheck()) {
                    $answer = $request->form(['POST'],"CSRF", function($request) {
                        $answer = [];
                        $email = $request->data(true)['data']['email'];
                        if (Validator::isEmail($email)) {
                            $data = array();
                            // $data['username'] = 'admin'; // - Toto nepouzivame kedze my sa prihalsujeme emailom v tomto priklade
                            $data['email'] = $email;
                            $data['password'] = $request->data(true)['data']['password'];
                            $login = Auth::login($data,Module::getStatic("autologin"));
                            if ($login['logged'] == true) {
                                $body = [];
                                if (Auth::loggedStage() == 2) {
                                    $body['redirectTo'] = Module::getStatic("loginUrl2fa");
                                }
                                if (Auth::isLogged()) {
                                    $body['redirectTo'] = Module::getStatic("defaultUrl");
                                }                            
                                $body['status'] = 1;
                                $body['error'] = 0;
                                $body['message'] = "Login successful.";
                                $answer['code'] = 200;
                                $answer['body'] = $body;
                                return $answer;
                            } else {
                                $body = [];
                                $body['status'] = 0;
                                $body['error'] = 1;
                                $body['errorNo'] = 2;
                                $body['message'] = "Invalid email or password.";
                                $answer['code'] = 200;
                                $answer['body'] = $body;
                                return $answer;
                            }
                        } else {
                            $body = [];
                            $body['status'] = 0;
                            $body['error'] = 1;
                            $body['errorNo'] = 1;
                            $body['message'] = "Enter valid email address !";
                            $answer['code'] = 200;
                            $answer['body'] = $body;
                            return $answer;
                        }
                    });
                }
            }
            return DotApp::DotApp()->ajaxReply($answer['body'], $answer['code']);
        }

        public static function login2fa($request, Renderer $renderer) {
            // Najprv skontroluejme ci nemame povinny auth ktory este nie je nastaveny
            $userAttr = Auth::attributes();
            // 
            if ($userAttr['tfa_auth'] == 1 && $userAttr['tfa_auth_secret_confirmed'] == 0) {
                // Vyzadujeme auth aplikaciu, ale nemame este potvrdeny auth kod...
                return self::confirmTFA_QR($request, $renderer);
            } else if ($userAttr['tfa_auth'] == 1 && $userAttr['tfa_auth_secret_confirmed'] == 1 && Auth::loggedStage() == 2) {
                return self::confirmTFA($request, $renderer);
            }
            header("Location: ".Module::getStatic("loginUrl"));
            exit();
        }

        public static function login2faEmail($request, Renderer $renderer) {
            // Najprv skontroluejme ci nemame povinny auth ktory este nie je nastaveny
            $userAttr = Auth::attributes();
            if ($userAttr['tfa_auth'] == 1 && $userAttr['tfa_auth_secret_confirmed'] == 0) {
                header("Location: ".Module::getStatic("loginUrl2fa"));
                exit();
            } else if ($userAttr['tfa_auth'] == 1 && $userAttr['tfa_auth_secret_confirmed'] == 1 && Auth::loggedStage() == 2) {
                $viewcode = $renderer->module(self::modulename())
                            ->setView("index")
                            ->setViewVar("seo",static::seoVar())
                            ->setViewVar("url",self::call("Users:CreateUser@url"))
                            ->setViewVar("emailcode",Auth::tfaEmail())
                            ->setLayout("2fa.email")->renderView();
                return $viewcode;
                return self::confirmTFA($request, $renderer);
            }
            header("Location: ".Module::getStatic("loginUrl"));
            exit();
        }

        public static function confirmTFA_QR($request, $renderer) {
            $userAttr = Auth::attributes();
            $qrIMG = QR::imageToBase64(QR::generate(TOTP::otpauth($userAttr['email'],$userAttr['tfa_auth_secret']),['bg' => '536592', 'fg' => 'FFFFFF'])->outputPNG());
            $data = Auth::getAuthData();
            $viewcode = $renderer->module(self::modulename())
                        ->setView("index")
                        ->setViewVar("seo",static::seoVar())
                        ->setViewVar("url",self::call("Users:CreateUser@url"))
                        ->setViewVar("qrIMG", $qrIMG)
                        ->setLayout("2fa.auth.confirm")->renderView();
            return $viewcode;
        }

        public static function confirmTFA($request, $renderer) {
            $userAttr = Auth::attributes();
            $viewcode = $renderer->module(self::modulename())
                        ->setView("index")
                        ->setViewVar("seo",static::seoVar())
                        ->setViewVar("url",self::call("Users:CreateUser@url"))
                        ->setLayout("2fa.auth")->renderView();
            return $viewcode;
        }

        public static function login2faPost($request, Renderer $renderer) {
            // Vyriesime situaciu kedy ma uzivatel v dvoch oknach uz zadany kod a v jednom uz potvrdil a druhe ostalo otvorene. Preto presmerujeme.
            if (Auth::isLogged()) {
                $body = [];
                $body['redirectTo'] = Module::getStatic("defaultUrl");
                $body['status'] = 1;
                $body['error'] = 0;
                $body['message'] = "You already confirmed two-factor authentication in another window.";
                $answer['code'] = 200;
                $answer['body'] = $body;
                return DotApp::DotApp()->ajaxReply($answer['body'], $answer['code']);
            }
            $userAttr = Auth::attributes();
            if ($request->crcCheck()) {
                    $answer = $request->form(['POST'],"ConfirmAuthCode", function($request) {
                        $confirmed = Auth::confirmTwoFactor(['tfa' => $request->data()['data']['code']]);
                        if ($confirmed['confirmed'] === true) {                            
                            $body = [];
                            if (Auth::isLogged()) {
                                $body['redirectTo'] = Module::getStatic("defaultUrl");
                            } 
                            $body['status'] = 1;
                            $body['error'] = 0;
                            $body['message'] = "Two-factor authentication successful.";
                            $answer['code'] = 200;
                            $answer['body'] = $body;
                            DB::module("RAW")
                                ->q(function ($qb) use ($userAttr) {
                                    $qb->update(Config::get("db","prefix").'users')->set(['tfa_auth_secret_confirmed' => 1])->where('id', '=', $userAttr['id']);      
                                })
                                ->execute();
                            return $answer;
                        } else {
                            $body = [];
                            $body['status'] = 0;
                            $body['error'] = 1;
                            $body['errorNo'] = 1;
                            $body['message'] = "Invalid two-factor authentication code.";
                            $answer['code'] = 200;
                            $answer['body'] = $body;
                            return $answer;
                        }
                    },"Users:Login@drop"); // Drop vrati nic len false, aby sme zabranili nepovolanim pristupom
                    if ($answer !== null) return DotApp::DotApp()->ajaxReply($answer['body'], $answer['code']);

                    $answer = $request->form(['POST'],"TwoFactor", function($request) {
                        $confirmed = Auth::confirmTwoFactor(['tfa' => $request->data()['data']['code']]);
                        if ($confirmed['confirmed'] === true) {                            
                            $body = [];
                            if (Auth::isLogged()) {
                                $body['redirectTo'] = Module::getStatic("defaultUrl");
                            } 
                            $body['status'] = 1;
                            $body['error'] = 0;
                            $body['message'] = "Two-factor authentication successful.";
                            $answer['code'] = 200;
                            $answer['body'] = $body;
                            return $answer;
                        } else {
                            $body = [];
                            $body['status'] = 0;
                            $body['error'] = 1;
                            $body['errorNo'] = 1;
                            $body['message'] = "Invalid two-factor authentication code.";
                            $answer['code'] = 200;
                            $answer['body'] = $body;
                            return $answer;
                        }
                    },"Users:Login@drop"); // Drop vrati nic len false, aby sme zabranili nepovolanim pristupom
                    if ($answer !== null) return DotApp::DotApp()->ajaxReply($answer['body'], $answer['code']);

                    $answer = $request->form(['POST'],"TwoFactorEmail", function($request) {
                        $confirmed = Auth::confirmTwoFactor(['tfa_email' => $request->data()['data']['code']]);
                        if ($confirmed['confirmed'] === true) {
                            $body = [];
                            if (Auth::isLogged()) {
                                $body['redirectTo'] = Module::getStatic("defaultUrl");
                            } 
                            $body['status'] = 1;
                            $body['error'] = 0;
                            $body['message'] = "Two-factor authentication successful.";
                            $answer['code'] = 200;
                            $answer['body'] = $body;
                            return $answer;
                        } else {
                            $body = [];
                            $body['status'] = 0;
                            $body['error'] = 1;
                            $body['errorNo'] = 1;
                            $body['message'] = "Invalid two-factor authentication code.";
                            $answer['code'] = 200;
                            $answer['body'] = $body;
                            return $answer;
                        }
                    },"Users:Login@drop",Module::getStatic("loginUrl2fa"));
                    /* 
                        Module::getStatic("loginUrl2fa") - Pretoze v sablone pouzivame <fo-rm id="twofaform" method="POST" action="{{ var: $url['loginUrl2fa'] }}">
                        namiesto textom specifikovanej URL, tak by zlyhala kontrola formulara. Preto ako posledny argument je rewriteAction. A my teda tymto argumentom
                        teraz specifikujeme ze pre aku URL je tento formular platny. Defaultne je platny pre action="zadanaUrl" ale kedze URL vkladame v sablone cez premennu
                        tak ju musime teraz tu specifikovat.
                    */
                    if ($answer !== null) return DotApp::DotApp()->ajaxReply($answer['body'], $answer['code']);
                }
        }

        public static function login2fa1($request, Renderer $renderer) { 
            if (!Auth::loggedStage() == 2) {
                header("Location: ".Module::getStatic("loginUrl"));
                exit();
            }
            $viewcode = $renderer->module(self::modulename())
                        ->setView("index")
                        ->setViewVar("seo",static::seoVar())
                        ->setViewVar("url",self::call("Users:CreateUser@url"))
                        ->setLayout("2fa.email")->renderView();
            return $viewcode;
        }

        public static function logout($request, Renderer $renderer) {
            Auth::logout();
            header("Location: ".Module::getStatic("loginUrl"));
            exit();
        }
                
    }
?>