<html>
    <head>
        <link rel="stylesheet" href="css/index.css">
    </head>
    <body>
        <a href="product.php">
            <button><?= __('Add') ?></button>
        </a>
        <a href="logout.php">
            <button><?= __('Logout') ?></button>
        </a>
        
        <?php foreach ($products as $product) : ?>

            <div class="product">
                <img src="<?= asset('storage/'. $product['image']) ?>" alt="">
                <div class="product_info">
                    <h1><?= $product["title"] ?></h1>
                    <p><?= $product["description"] ?></p>
                    <p><?= __('Price: ') ?><?= $product["price"] ?> <?= __('$') ?></p>
                    <a href="product.php?id=<?= $product["id"] ?>"><?= __('Edit') ?></a>
                    <a href="products.php?id=<?= $product["id"] ?>"><?= __('Delete') ?></a>
                </div>
            </div>
        
        <?php endforeach; ?>
    </body>
</html>