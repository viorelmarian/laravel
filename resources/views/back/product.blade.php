<html>
    <head>
        <link rel="stylesheet" href="css/product.css">
    </head>
    <body>
        <form action="product.php<?= request()->has('id') ? '?id=' . request()->get('id') : '' ?>" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <fieldset>
                <input type="text" name="title"  autocomplete="off" placeholder="Title" value="{{ $productInfo['title'] }}">
                <p style="color:red"><?= $errors->first('title') ?></p>
                <input type="text" name="description"  autocomplete="off" placeholder="Description" value="{{ $productInfo['description'] }}">
                <p style="color:red"><?= $errors->first('description') ?></p>
                <input type="text" name="price"  autocomplete="off" placeholder="Price" value="{{ $productInfo['price'] }}">
                <p style="color:red"><?= $errors->first('price') ?></p>
                <input type="file" name="image" style="align:left;">
                <br>
                <p style="color:red"><?= $errors->first('image') ?></p>
                <br>
                <div class="preview" >
                    <?php if ($productInfo['image']) : ?>
                        <img style="width:100px;height:100px;" src="<?= asset('storage/'. $productInfo['image']) ?>" alt="">
                    <?php endif; ?>
                </div>
                <input type="submit" name="save" value="<?= __('Save') ?>">
            </fieldset>
        </form>
    </body>
</html>