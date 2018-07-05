<html>
    <head>
        <link rel="stylesheet" href="css/index.css">
    </head>
    <body>
        <a href="cart.php">
            <button><?= __('Go to cart') ?></button>
        </a>
        
        <?php foreach ($products as $product) : ?>

            <div class="product">
                <img src="images/<?= $product["image"] ?>" alt="">
                <div class="product_info">
                    <h1><?= $product["title"] ?></h1>
                    <p><?= $product["description"] ?></p>
                    <p><?= __('Price: ') ?><?= $product["price"] ?> <?= __('$') ?></p>
                    <a href="index.php?id=<?= $product["id"] ?>"><?= __('Add to cart') ?></a>
                </div>
            </div>
        
        <?php endforeach; ?>
    </body>

</html>