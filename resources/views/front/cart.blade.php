<html>
    <head>
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/cart.css">
    </head>
    <body>
        <a href="index">
            <button  class="buttons"><?= __('Go to index') ?></button>
        </a>
        <a href="cart?id=all">
            <button  class="buttons"><?= __('Remove all') ?></button>
        </a>
        
        <?php foreach ($products as $product) : ?>

            <div class="product">
                <img src="<?= asset('storage/'. $product['image']) ?>" alt="">
                <div class="product_info">
                    <h1><?= $product["title"] ?></h1>
                    <p><?= $product["description"] ?></p>
                    <p><?= __('Price: ') ?><?= $product["price"] ?> <?= __('$') ?></p>
                    <a href="cart?id=<?= $product["id"] ?>"><button><?= __('Remove') ?></button></a>
                </div>
            </div>
        
        <?php endforeach; ?>

        <form action="cart" method="post">
            {{ csrf_field() }}
            <fieldset>
                <input type="text" name="name" placeholder="<?= __('Name') ?>" autocomplete="off" value="{{ old('name') }}">
                <p style="color:red"> <?= $errors->first('name') ?></p>
                <input type="text" name="contact" placeholder="<?= __('Contact Information') ?>" autocomplete="off" value="{{ old('contact') }}">
                <p style="color:red"> <?= $errors->first('contact') ?></p>
                <input type="text" name="comments" placeholder="<?= __('Comments') ?>" autocomplete="off" value="{{ old('comments') }}">
                <p style="color:red"> <?= $errors->first('comments') ?></p>
                <input type="submit" name="checkout" value="<?= __('Checkout') ?>">
            </fieldset>
        </form>
    </body>

</html>