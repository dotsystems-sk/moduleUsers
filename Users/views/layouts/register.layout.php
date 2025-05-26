<div class="container">
	<!-- Navigation Menu -->
	<nav class="nav-menu">
		<a href="{{ var: $url['defaultUrl'] }}">Home</a>
		<a href="{{ var: $url['loginUrl'] }}">User Login</a>
	</nav>

	<!-- Registration Form -->
	<div class="form-box">
		<h2>Registration EXAMPLE</h2>
		<span class="user-icon">
			<div class="icon-wrapper">
				<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M12 20h9"></path>
					<path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
				</svg>
			</div>
        </span>
		<fo-rm method="POST" id="registration">
			<div class="form-group">
				<label for="email-reg">Email</label>
				<input type="text" name="email" placeholder="Enter your email">
			</div>
			<div class="form-group">
				<label for="password-reg">Password</label>
				<input type="password" name="password" placeholder="Enter your password">
			</div>
			{{ formName(CSRF) }}
			<div class="error-message" id="error-message"></div>
			<button type="submit" class="btn" id="registrationbtn">Sign Up</button>
			<p class="form-link">Already have an account? <a href="{{ var: $url['loginUrl'] }}">Log in</a></p>
		</fo-rm>
	</div>

</div>
