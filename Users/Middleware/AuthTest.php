<?php	
namespace Dotsystems\App\Modules\Users\Middleware;

use Dotsystems\App\DotApp;
use Dotsystems\App\Parts\Middleware;
use Dotsystems\App\Parts\Response;
use Dotsystems\App\Parts\Auth;
use Dotsystems\App\Parts\Config;
use Dotsystems\App\Modules\Users\Module;

class AuthTest extends \Dotsystems\App\Parts\ModuleMiddleware {

	public static function register() {
        // Definujeme si middleware pre zistenie ci sme prihlaseni alebo nie...
            Middleware::define("auth:loginTest",function($request,$next){
                if (Auth::isLogged()) {
                    // Continue in logic to reach $next($request);
                } else if (Auth::loggedStage() == 2) {
                    header("Location: ".Module::getStatic("loginUrl2fa"));
                    exit();
                } else {
                    header("Location: ".Module::getStatic("loginUrl"));
                    exit();
                }

                // User is authenticated, proceed to the next middleware or controller
                $next($request);
            });

	}
}
?>
