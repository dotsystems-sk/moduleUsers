<?php
	namespace Dotsystems\App\Modules\Users;
	use \Dotsystems\App\DotApp;
	use \Dotsystems\App\Parts\Router;
	use \Dotsystems\App\Parts\Request;
	use \Dotsystems\App\Parts\Middleware;
	use \Dotsystems\App\Parts\Response;
	use \Dotsystems\App\Parts\Input;
	use \Dotsystems\App\Parts\DB;

	class Listeners extends \Dotsystems\App\Parts\Listeners {

		public function register($dotApp) {
			
			/*
				Tips:
				
				Do not forget to register your middleware ! For example:
				Middleware\Middleware::register();
				
				// Configure the module to serve the default "/" route if no other module has claimed it
				// Wait until all modules are loaded, then check if the "/" route is defined
				$dotApp->on("dotapp.modules.loaded", function($moduleObj) use ($dotApp) {
					if (!$dotApp->router->hasRoute("get", "/")) {
						// No default route is defined, so set this module's route as the default
						$dotApp->router->get("/", function() {
							header("Location: /users/", true, 301);
							exit();
						});
					}
				});
			*/
			
			// Add your custom logic here
			
		}
		
	}
	
	new Listeners($dotApp);
?>