<div class="container">
    <!-- Navigation Menu -->
    <nav class="nav-menu">
        <a href="{{ var: $url['defaultUrl'] }}">Home</a>
        <a href="{{ var: $url['logoutUrl'] }}">Logout</a>
    </nav>

    <div class="form-box">
        <h2>Login Successful</h2>
        <span class="user-icon">
            <div class="icon-wrapper">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a10 10 0 0 1 10 10 10 10 0 0 1-10 10A10 10 0 0 1 2 12 10 10 0 0 1 12 2z"></path>
                    <path d="M9 12l2 2 4-4"></path>
                </svg>
            </div>
        </span>
        <p class="form-description">
            You have successfully logged in to this test module. Below are your account details:
        </p>
		<hr>
        <div class="form-group">
            <p class="form-description"><b>Logged as:</b> {{ var: $email }}</p>
        </div>
		<hr>
        <p class="form-description explanation">
            This module exemplifies the philosophy of the dotapp framework: create once, reuse everywhere. With dotapp, you build a robust login and registration module with QR code authentication just once. For all your future projects, simply copy this module, and you instantly have a fully functional authentication system ready to go. This approach saves an incredible amount of time, streamlining development and ensuring consistency across your applications. Whether you're building a small prototype or a large-scale platform, dotapp empowers you to focus on innovation by eliminating repetitive setup tasks.
        </p>
        <div class="btn">Thats all for this Example !</div>
    </div>
</div>