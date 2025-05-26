<div class="container">
	<!-- Navigation Menu -->
	<nav class="nav-menu">
		<a href="{{ var: $url['defaultUrl'] }}">Home</a>
		<a href="{{ var: $url['registerUrl'] }}">User Register</a>
	</nav>

	<!-- Login Form -->
	<div class="form-box">
		<h2>Log In Example</h2>
		<span class="user-icon">
			<div class="icon-wrapper">
				<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
					<circle cx="12" cy="7" r="4"></circle>
				</svg>
			</div>
		</span>
		<fo-rm method="POST" id="login">
			<div class="form-group">
				<label for="email-login">Email</label>
				<input type="email" name="email" placeholder="Enter your email" required>
			</div>
			<div class="form-group">
				<label for="password-login">Password</label>
				<input type="password" name="password" placeholder="Enter your password" required>
			</div>
			{{ formName(CSRF) }}
			<div class="error-message" id="error-message"></div>
			<button type="submit" class="btn" id="loginbtn">Log In</button>
			<p class="form-link">Don't have an account yet? <a href="{{ var: $url['registerUrl'] }}">Create one</a></p>
		</fo-rm>
	</div>
</div>
