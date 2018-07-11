<html>
    <head>
    </head>
    <body>
        <div>
            <form action="login" method="post">
            {{ csrf_field() }}
                <fieldset>
                    <input type="text" name="username" placeholder="<?= __('Username') ?>" autocomplete="off" value="{{ old('username') }}">
                    <p style="color:red"><?= $errors->first('username') ?></p>
                    <input type="password" name="password" placeholder="<?= __('Password') ?>" autocomplete="off" value="{{ old('password') }}">
                    <p style="color:red"><?= $errors->first('password') ?></p>
                    <input type="submit" name="login" value="<?= __('Login') ?>">
                </fieldset>
            </form>
        </div>
    </body>
</html>
