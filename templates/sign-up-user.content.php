<div id="sign-up-user" class="container">
    <form class="form-horizontal sign-up-user-form" action="sign-up-user.logic.php" method="post">
        <div class="form-group username-group">
            <label class="col-sm-4 control-label required">Username</label>
            <div class="col-sm-7 has-feedback">
                <input type="text" class="form-control" name="username" placeholder="username" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>

        </div>
        <div class="form-group password-group">
            <label class="col-sm-4 control-label required">Password</label>
            <div class="col-sm-7 has-feedback">
                <input id="inputPassword" type="password" class="form-control" data-minlength="6" name="password" placeholder="password" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>
        <div class="form-group password-group">
            <label class="col-sm-4 control-label required">Confirm password</label>
            <div class="col-sm-7 has-feedback">
                <input type="password" class="form-control" data-match="#inputPassword" data-minlength="6" name="confirmPassword" placeholder="confirm password" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>
        <div class="form-group firstname-group">
            <label class="col-sm-4 control-label required">First name</label>
            <div class="col-sm-7 has-feedback">
                <input type="text" class="form-control" name="firstName" placeholder="first name" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>
        <div class="form-group lastname-group">
            <label class="col-sm-4 control-label required">Last name</label>
            <div class="col-sm-7 has-feedback">
                <input type="text" class="form-control" name="lastName" placeholder="last name" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>
        <div class="form-group email-group">
            <label class="col-sm-4 control-label required">Email</label>
            <div class="col-sm-7 has-feedback">
                <input type="email" class="form-control" name="email" placeholder="email" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>
        <div class="form-group phone-group">
            <label class="col-sm-4 control-label required">Phone</label>
            <div class="col-sm-7 has-feedback">
                <input type="phone" class="form-control" name="phone" placeholder="phone number" required>
                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            </div>
        </div>
        <hr>
        <div class="row text-center">
            <input type="reset" class="btn btn-danger" value="Reset form">
            <input type="submit" class="btn btn-primary" name="sign-up-user-submit" value="Sign up">
        </div>
    </form>
    <div class="row sign-up-user-alert">

    </div>
</div>
