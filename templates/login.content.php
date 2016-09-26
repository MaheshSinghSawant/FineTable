<div id="login-block" class="container">
    <form class="login-form" action="login.logic.php" method="post">
        <div class="form-group">
            <label>User name</label>
            <input type="text" class="form-control" placeholder="Username" name="username">
        </div>
        <div class="form-group">
            <label>Password

            </label>
            <input type="password" class="form-control" placeholder="Password" name="password">
        </div>
        <input type="submit" class="btn btn-primary" name="login-submit" value="Login">
        <span class="text-info"><span class="forgot-password"><a href="reset-password.php">Forgot password?</a></span>&nbsp;|&nbsp;<a class="new-user-link" href="#">New user?</a></span>
    </form>
    <div class="row login-alert">
    </div>
</div>
