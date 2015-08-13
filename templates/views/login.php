<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">
    <title>Dashboard</title>
    <!-- Bootstrap core CSS -->
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="public/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="public/css/style.css" rel="stylesheet">
    <link href="public/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-body">
    <div class="container">
      <form class="form-signin" action="" id="form" method="post">
        <h2 class="form-signin-heading">Welcome</h2>
        <div class="login-wrap">
            <input type="text" class="form-control" placeholder="Email" name="username" id="username" autofocus>
            <input type="password" class="form-control" placeholder="Password" name="password" id="password">
            <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
                <span class="pull-right"> <a href="#"> Forgot Password?</a></span>
            </label>
            <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
            <p>
            	<?php                          
                if (! empty ( $error_array ["password"] ))
                    echo "<label class='error'>{$error_array["password"]}.</label>";
                if (! empty ( $error_array ["username"] ))
                    echo "<label class='error'>{$error_array["username"]}.</label>";
            	  ?>
            </p>
            <!--
            <div class="login-social-link">
                <a href="index.html" class="facebook">
                    <i class="icon-facebook"></i>
                    Facebook
                </a>
                <a href="index.html" class="twitter">
                    <i class="icon-twitter"></i>
                    Twitter
                </a>
            </div>
			-->
        </div>
      </form>
    </div>
</body>
</html>
