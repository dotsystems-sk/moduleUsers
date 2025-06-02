<?php	
	namespace Dotsystems\App\Modules\Users\Api;
	use Dotsystems\App\DotApp;
	
	class Api extends \Dotsystems\App\Parts\Controller {
		
		/*
			If you use the automatic router dispatcher in the controller (e.g., in module.init.php) with:
			$dotApp->router->apiPoint("1", "users", "Dotsystems\App\Modules\Users\Api\Api@apiDispatch");
			
			The following routes will be created:
			- GET /api/v1/users/test - Calls the getTest method.
			- POST /api/v1/users/test - Calls the postTest method.

			Dependency injection is supported by default. Example with DotApp injection:
			
			public static function getTest($request, DotApp $dotApp) {
				// Handles GET /api/v1/users/test
			}
			
			public static function postTest($request, DotApp $dotApp) {
				// Handles POST /api/v1/users/test
			}
		*/		
				
	}
?>