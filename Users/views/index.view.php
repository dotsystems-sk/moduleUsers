<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ var: $seo['title'] }}</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="{{ var: $seo['description'] }}"/>
	<meta name="keywords" content="{{ var: $seo['keywords'] }}"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSS files -->
	<link rel="stylesheet" href="/assets/modules/Users/users.css">
</head>
<body>
    {{ content }}
	<script src="/assets/dotapp/dotapp.js"></script>
	<script src="/assets/modules/Users/users.js"></script>
</body>
</html>