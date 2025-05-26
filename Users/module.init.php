<?php
	namespace Dotsystems\App\Modules\Users;
    use \Dotsystems\App\DotApp;
	use \Dotsystems\App\Parts\Router;
	use \Dotsystems\App\Parts\Middleware;
	use \Dotsystems\App\Parts\Request;
	use \Dotsystems\App\Parts\Response;
	use \Dotsystems\App\Parts\Input;
	use \Dotsystems\App\Parts\DB;
	use \Dotsystems\App\Parts\Renderer;
    use \Dotsystems\App\Parts\Auth;
    use \Dotsystems\App\Parts\Config;
    
	

	class Module extends \Dotsystems\App\Parts\Module {
        private static $allowRegistration;
        private static $allowLogin;
        private static $defaultUrl;
        private static $registerUrl;
        private static $loginUrl;
        private static $loginUrl2fa;
        private static $loginUrl2faEmail;
        private static $logoutUrl;
        private static $autologin;
		
		public function initialize($dotApp) {

            // Zadefinujeme si middleware pre registraciu a prihlasovanie
            self::call("Users:Middleware\AuthTest@register");
            
            Router::get(static::$defaultUrl, "Users:Login@index", Router::STATIC_ROUTE)
                        ->before( Middleware::use("auth:loginTest")); // Add authentication middleware before accessing the form4 routes

            if (static::$allowRegistration === true) {
                // Registration form page
                Router::get(static::$registerUrl, "Users:CreateUser@register", Router::STATIC_ROUTE);

                // Registration form page SUBMIT NEW REGISTRATION
                Router::post(static::$registerUrl, "Users:CreateUser@registerPost", Router::STATIC_ROUTE);
            }

            if (static::$allowLogin === true) {
                // Display the forms page
                Router::get(static::$loginUrl, "Users:Login@login", Router::STATIC_ROUTE);
                // Logion form submit handler
                Router::post(static::$loginUrl, "Users:Login@loginPost", Router::STATIC_ROUTE);

                Router::get(static::$loginUrl2fa, "Users:Login@login2fa", Router::STATIC_ROUTE);
                Router::post(static::$loginUrl2fa, "Users:Login@login2faPost", Router::STATIC_ROUTE);

                // 2 Faktor emailom, na post pouzijeme static::$loginUrl2fa
                Router::get(static::$loginUrl2faEmail, "Users:Login@login2faEmail", Router::STATIC_ROUTE);

                Router::get(static::$logoutUrl, "Users:Login@logout", Router::STATIC_ROUTE);
            }            
		
		}
		
		public function initializeRoutes() {
            $initializeRoutes = [];

            static::$autologin = Config::module("Users","autologin") ?? true;
            static::$defaultUrl = Config::module("Users","defaultUrl") ?? "/documentation/examples/run/forms4";
            $initializeRoutes[] = static::$defaultUrl;

            static::$allowRegistration = Config::module("Users","allowRegistration") ?? true;
            static::$registerUrl = Config::module("Users","registerUrl") ?? "/documentation/examples/run/forms4-register";
            if (static::$allowRegistration === true) {
                $initializeRoutes[] = static::$registerUrl;
            }

            static::$allowLogin = Config::module("Users","allowLogin") ?? true;
            static::$loginUrl = Config::module("Users","loginUrl") ?? "/documentation/examples/run/forms4-login";
            static::$loginUrl2fa = Config::module("Users","loginUrl2fa") ?? "/documentation/examples/run/forms4-login-2fa";
            static::$logoutUrl = Config::module("Users","logoutUrl") ?? "/documentation/examples/run/forms4-logout";
            static::$loginUrl2faEmail = Config::module("Users","loginUrl2faEmail") ?? "/documentation/examples/run/forms4-login-2fa-email";
            if (static::$allowLogin === true) {
                $initializeRoutes[] = static::$logoutUrl;
                $initializeRoutes[] = static::$loginUrl;
                $initializeRoutes[] = static::$loginUrl2fa;
                $initializeRoutes[] = static::$loginUrl2faEmail;
            }            
			return $initializeRoutes; 
		}

		public function initializeCondition($routeMatch) {
			return $routeMatch; // Always initialize if the route matched (default behavior)
		}
	}
	
	new Module($dotApp);
?>
