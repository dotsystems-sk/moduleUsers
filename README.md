# Users Module for DotApp PHP Framework

The Users module is a **FREE** module for the DotApp PHP framework, providing a complete authentication system. Installation is simple and quick: download the framework, create a database, and extract the module into the appropriate directory.

## Installation

Follow these steps to install the module:

1. **Download the Framework**  
   Download the DotApp PHP framework from [dotapp.dev](https://dotapp.dev) or [https://github.com/dotsystems-sk/DotApp](https://github.com/dotsystems-sk/DotApp).

2. **Create the Database**  
   Create a database for your project and generate the SQL file using the framework's command. Run the following command in the terminal:  
   ```
   php dotapper.php --prepare-database
   ```  
   This command will create an SQL file with the necessary tables using your configured database prefix. Import this file into your database.

3. **Install the Module**  
   Download the Users module repository as a ZIP file and extract it into the `/app/modules/` directory of your DotApp project. That's it!

## Configuration

You can configure the module in the `/app/config.php` file. Available options include:

- **`autologin`**  
  Set to `true` to enable automatic login after the session expires.  
  **Example:** `Config::module("Users", "autologin", true);`

- **`allowRegistration`**  
  Set to `true` to allow user registration.  
  **Example:** `Config::module("Users", "allowRegistration", true);`

- **`allowLogin`**  
  Set to `true` to allow user login.  
  **Example:** `Config::module("Users", "allowLogin", true);`

- **`defaultUrl`**  
  The URL to redirect users to after successful login.  
  **Example:** `Config::module("Users", "defaultUrl", "/logged");`

- **`registerUrl`**  
  The URL for user registration.  
  **Example:** `Config::module("Users", "registerUrl", "/register");`

- **`loginUrl`**  
  The URL where users will log in.  
  **Example:** `Config::module("Users", "loginUrl", "/login");`

- **`loginUrl2fa`**  
  The URL for 2FA verification via authenticator app (if enabled).  
  **Example:** `Config::module("Users", "loginUrl2fa", "/login-2fa");`

- **`loginUrl2faEmail`**  
  The URL for 2FA verification via email code (if enabled).  
  **Example:** `Config::module("Users", "loginUrl2faEmail", "/logged");`

- **`logoutUrl`**  
  The URL for user logout.  
  **Example:** `Config::module("Users", "logoutUrl", "/logout");`

### Example Configuration in `/app/config.php`

```php
Config::module("Users", "autologin", true);
Config::module("Users", "allowRegistration", true);
Config::module("Users", "allowLogin", true);
Config::module("Users", "defaultUrl", "/logged");
Config::module("Users", "registerUrl", "/register");
Config::module("Users", "loginUrl", "/login");
Config::module("Users", "loginUrl2fa", "/login-2fa");
Config::module("Users", "loginUrl2faEmail", "/logged");
Config::module("Users", "logoutUrl", "/logout");
```

After configuring these settings, the module is ready to use! The Users module is designed to be reusable across projects, saving time and ensuring consistency in your authentication systems.