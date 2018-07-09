<html>
    <head>
        <link rel="stylesheet" href="css/login.css">
    </head>
    <body>
        <div>
            <form action="login.php" method="post">
                <fieldset>
                    <input type="text" name="username" placeholder="<?= __('Username') ?>" autocomplete="off">
                    <p style="color:red">{{ $errors['username'] }}</p>
                    <input type="password" name="password" placeholder="<?= __('Password') ?>" autocomplete="off">
                    <p style="colorZ:red">{{ $errors['password'] }}</p>
                    <input type="submit" name="login" value="<?= __('Login') ?>">
                </fieldset>
            </form>
        </div>
    </body>
</html>
