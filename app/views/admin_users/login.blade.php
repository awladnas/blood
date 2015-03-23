<!--
app/views/loginForm.php
-->
<!DOCTYPE HTML>
<html>
<head>
    <title>Simple Login Form in Laravel</title>
    <meta charset="UTF-8" />
    <?php
    echo HTML::style('css/reset.css');
    echo HTML::style('css/structure.css');
    ?>
</head>
<body>
<?php echo Form::open(array('url' => '/admin/login', 'method' => 'POST','class' => 'box login')); ?>
<fieldset class="boxBody">
    <label>Email</label>
    <input type="text" tabindex="1" name="email" required>
    <label>
        <a href="#" class="rLink" tabindex="5">Forget your password?</a>Password
    </label>
    <input type="password" name="password" tabindex="2" required>
</fieldset>
<footer>
    {{--<label><input type="checkbox" name="persist" tabindex="3">Remember me</label>--}}
    <input type="submit" class="btnLogin" value="Login" tabindex="4">
</footer>
</form>
<footer id="main">
</footer>
</body>
</html>