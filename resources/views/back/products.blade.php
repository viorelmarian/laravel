<html>
    <head>
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/cart.css">
    </head>
    <body>
        <a href="product">
            <button class="buttons"><?= __('Add') ?></button>
        </a>
        <a href="logout">
            <button class="buttons"><?= __('Logout') ?></button>
        </a>
        
        <?php foreach ($products as $product) : ?>

            <div class="product">
                <img src="<?= asset('storage/'. $product['image']) ?>" alt="">
                <div class="product_info">
                    <h1><?= $product["title"] ?></h1>
                    <p><?= $product["description"] ?></p>
                    <p><?= __('Price: ') ?><?= $product["price"] ?><?= __('$') ?></p>
                    <a href="product?id=<?= $product["id"] ?>"><button><?= __('Edit') ?></button></a>
                    <a href="products?id=<?= $product["id"] ?>"><button><?= __('Delete') ?></button></a>
                </div>
            </div>
        
        <?php endforeach; ?>
    </body>
</html>