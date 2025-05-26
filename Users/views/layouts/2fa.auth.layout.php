<div class="container">
	<!-- Navigation Menu -->
	<nav class="nav-menu">
		<a href="{{ var: $url['defaultUrl'] }}">Home</a>
		<a href="{{ var: $url['logoutUrl'] }}">Logout</a>
	</nav>

	<div class="form-box">
		<h2>2FA Verification</h2>
		<span class="user-icon">
			<div class="icon-wrapper">
				<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
					<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
					<path d="M10 16l2 2 4-4"></path>
				</svg>
			</div>
        </span>
		<fo-rm id="twofaform" method="POST">
			<div class="form-group">
				<label for="two-fa">Enter 6-digit code</label>
				<div class="two-fa-inputs">
					<input type="text" maxlength="1" placeholder="-" id="first">
                    <input type="text" maxlength="1" placeholder="-">
                    <input type="text" maxlength="1" placeholder="-">
                    <input type="text" maxlength="1" placeholder="-">
                    <input type="text" maxlength="1" placeholder="-">
                    <input type="text" maxlength="1" placeholder="-">
				</div>
			</div>
			<input type="hidden" name="code" value="">
			{{ formName(TwoFactor) }}
			<div class="error-message" id="error-message"></div>
			<div class="btn" id="confirm2fa">Verify</div>
			<p class="form-link">Don't have your phone for 2FA?<br>
			<a href="{{ var: $url['loginUrl2faEmail'] }}">Switch to email verification</a></p>
		</fo-rm>
	</div>
</div>