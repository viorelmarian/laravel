<html>
    <head>
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/cart.css">
    </head>
    <body>
        <a href="index.php">
            <button><?= __('Go to index') ?></button>
        </a>
        <a href="cart.php?id=all">
            <button><?= __('Remove all') ?></button>
        </a>
        
        <?php foreach ($products as $product) : ?>

            <div class="product">
                <img src="<?= asset('storage/'. $product['image']) ?>" alt="">
                <div class="product_info">
                    <h1><?= $product["title"] ?></h1>
                    <p><?= $product["description"] ?></p>
                    <p><?= __('Price: ') ?><?= $product["price"] ?> <?= __('$') ?></p>
                    <a href="cart.php?id=<?= $product["id"] ?>"><?= __('Remove') ?></a>
                </div>
            </div>
        
        <?php endforeach; ?>

        <form action="cart.php" method="post">
            {{ csrf_field() }}
            <fieldset>
                <input type="text" name="name" placeholder="<?= __('Name') ?>" autocomplete="off" value="<?= $formInfo['name'] ?>">
                <p style="color:red"> <?= $errors['name'] ?></p>
                <input type="text" name="contact" placeholder="<?= __('Contact Information') ?>" autocomplete="off" value="<?= $formInfo['contact'] ?>">
                <p style="color:red"> <?= $errors['contact'] ?></p>
                <input type="text" name="comments" placeholder="<?= __('Comments') ?>" value="<?= $formInfo['comments'] ?>" >
                <p style="color:red"> <?= $errors['comments'] ?></p>
                <input type="submit" name="checkout" value="<?= __('Checkout') ?>">
            </fieldset>
        </form>
    </body>

</html>