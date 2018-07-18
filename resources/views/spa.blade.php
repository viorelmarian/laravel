<html>
    <head>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/cart.css">
    <!-- Load the jQuery JS library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>  
    
    <!-- Custom JS script -->
    <script type="text/javascript">         
        $(document).ready(function () {
            
            /**
            * A function that takes a products array and renders it's html
            * 
            * The products array must be in the form of
            * [{
            *     "title": "Product 1 title",
            *     "description": "Product 1 desc",
            *     "price": 1
            * },{
            *     "title": "Product 2 title",
            *     "description": "Product 2 desc",
            *     "price": 2
            * }]
            */

            // Function for rendering products
            function renderList(products, source) {
                
                html = '';
                $.each(products, function (key, product) {
                    html += [
                        '<div class="product">',
                            '<img src="/storage/'+ product.image + '" alt="">',
                            '<div class="product_info">',
                                '<h1>' + product.title + '</h1>',
                                '<p>' + product.description + '</p>',
                                '<p><?= __('Price: ') ?>' + product.price + '<?= __(' $') ?></p>',
                                source == 'index' ? 
                                    '<button class="add-to-cart" data-id="' + product.id + '"><?= __('Add') ?></button>'
                                :
                                    ''
                                , 
                                source == 'cart' ?
                                    '<button class="remove-from-cart" data-id="' + product.id + '"><?= __('Remove') ?></button>'
                                :
                                    ''
                                ,
                                source == 'products' ?
                                    '<button class="edit-product" data-id="' + product.id + '"><?= __('Edit') ?></button>' 
                                    + '<button class="delete-product" data-id="' + product.id + '"><?= __('Delete') ?></button>'
                                :
                                    ''
                                ,
                            '</div>',
                        '</div>'
                    ].join('');
                });
                return html;
            }
            
            /*****Cart related events*****/

            // Add a product in cart.
            $(document).on('click', '.add-to-cart', function() {
                $.ajax('/index', {
                    dataType: 'json',
                    data: {id: $(this).attr('data-id')},
                    success: function (response) {
                        $('.index-page .list').html(renderList(response, 'index'));
                    }
                });
            });

            //Remove a product from cart.
            $(document).on('click', '.remove-from-cart', function() {
                $.ajax('/cart', {
                    dataType: 'json',
                    data: {id: $(this).attr('data-id')},
                    success: function (response) {
                        $('.cart-page .list').html(renderList(response.products, 'cart'));
                    }
                });
            });
            
            //Remove all products from cart.
            $(document).on('click', '.remove-all', function() {
                $.ajax('/cart', {
                    dataType: 'json',
                    data: {id: $(this).attr('data-id')},
                    success: function (response) {
                        $('.cart-page .list').html(renderList(response.products, 'cart'));

                        window.location.hash = '#products'; 
                    }
                });
            });
            
            //Checkout event to send order with all the products in the cart and the form data.
            $(document).on('click', '.checkout', function() {           
                $.ajax('/cart', {
                    dataType: 'json',
                    data: {
                        name: $('.name').val(),
                        contact: $('.contact').val(),
                        comments: $('.comments').val(),
                        checkout: true
                    },
                    success: function (response) {
                        $('.cart-page .list').html(renderList(response.products, 'cart'));

                        $('.name-err').html('name' in response.errors ? response.errors.name :''); 
                        $('.contact-err').html('contact' in response.errors ? response.errors.contact :''); 
                        $('.comments-err').html('comments' in response.errors ? response.errors.comments :'');

                        window.location.hash = '#products'; 
                    }
                });
            });
            
            /*****Admin page related events*****/

            //Delete a product from the product list.
            $(document).on('click', '.delete-product', function() {
                $.ajax('/products', {
                    dataType: 'json',
                    data: {id: $(this).attr('data-id')},
                    success: function (response) {
                        $('.products-page .list').html(renderList(response, 'products'));
                    }
                });
            });


            //Edit one of the products.
            $(document).on('click', '.edit-product', function() {
                window.location.hash = '#product?id=' + $(this).attr('data-id');
            });
            
            //Add new product to the product list.
            $(document).on('click', '.add-product', function() {
                window.location.hash = '#product';
            });
            
            //Save the added / edited product
            $(document).on('click', '.save', function() {
                
                var formData = new FormData();
                
                formData.append('title', $('.title').val());
                formData.append('description', $('.description').val());
                formData.append('price', $('.price').val());
                formData.append('save', true);
                
                if ($('.image')[0].files[0]) {
                    var image = $('.image')[0].files[0];
                    formData.append('image', image);
                }

                if (window.location.hash.indexOf('#product?id=') === 0) {
                    formData.append('id', window.location.hash.split('=')[1]);
                }

                $.ajax('/product', {
                    data: formData,
                    cache: false,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (response) {
                        if (response.success) {
                            window.location.hash = "#products";
                        } else {
                            $('.title-err').html('title' in response.errors ? response.errors.title :'');
                            $('.description-err').html('description' in response.errors ? response.errors.description :'');
                            $('.price-err').html('price' in response.errors ? response.errors.price :'');
                            $('.image-err').html('image' in response.errors ? response.errors.image :'');
                        }
                    }
                });
            });
            
            //Logout from the Admin page.
            $(document).on('click', '.logout', function() {

                $.ajax('/logout', {
                    dataType: 'json',
                    success: function() {
                        window.location.hash = '#login';
                    }
                });
            });
            
            //Login to the Admin page.
            $(document).on('click', '.login', function() {
                $.ajax('/login', {
                    dataType: 'json',
                    data: {
                        username: $('.username').val(),
                        password: $('.password').val(),
                        login: true
                    },
                    success: function (response) {
                        $('.username-err').html('username' in response.errors ? response.errors.username :'');
                        $('.password-err').html('password' in response.errors ? response.errors.password :'');
                        $('.credentials-err').html('credentials' in response.errors ? response.errors.credentials :'');
                        if (response.login) {
                            $('.username').val('');
                            $('.password').val('');
                            window.location.hash = "#products";
                        } else {
                            alert('<?= __('Some error') ?>');
                        }
                    }
                });
            });
            /*****Access related events *****/

            //Go to the cart page
            $(document).on('click', '.go-to-cart', function () {
                window.location.hash = "#cart";
            });

            //Got to the index page
            $(document).on('click', '.go-to-index', function () {
                window.location.hash = "#";
            });

            /**
            * URL hash change handler
            */
            window.onhashchange = function () {
                // First hide all the pages
                $('.page').hide();

                switch(window.location.hash) {
                    case '#cart':
                        // Show the cart page
                        $('.cart-page').show();
                        // Load the cart products from the server
                        $.ajax('/cart', {
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the cart list
                                $('.cart-page .list').html(renderList(response.products, 'cart'));
                            }
                        });
                        break;

                    case '#login':
                        // Show the login page
                        $('.login-page').show();
                        break;

                    case '#products':
                        // Load the products from the server
                        $.ajax('/products', {
                            dataType: 'json',
                            success: function (response) {
                                $('.products-page').show();
                                $('.products-page .list').html(renderList(response, 'products'));
                            },
                            error: function () {
                                    window.location.hash = "#login";
                            }
                        });
                        break;
                    default:
                        if (window.location.hash.indexOf('#product?id=') === 0) {
                            $.ajax('/product', {
                                dataType: 'json',
                                data: {id: window.location.hash.split('=')[1]},
                                success: function (response) {
                                    $('.title').val(response.title);
                                    $('.description').val(response.description);
                                    $('.price').val(response.price);
                                    $('.preview').show();
                                    $('.preview img').attr('src', 'storage/' + response.image);

                                    $('.product-page').show();
                                },
                                error: function () {
                                    window.location.hash = "#login";
                                }
                            });
                        } else if (window.location.hash === '#product') {
                            $.ajax('/product', {
                                dataType: 'json',
                                success: function () {
                                    $('.title').val('');
                                    $('.description').val('');
                                    $('.price').val('');
                                    $('.preview').hide();

                                    $('.product-page').show();
                                },
                                error: function () {
                                    window.location.hash = "#login";
                                }
                            });
                            
                        } else {
                            // If all else fails, always default to index
                            // Show the index page
                            $('.index-page').show();
                            // Load the index products from the server
                            $.ajax('/', {
                                dataType: 'json',
                                success: function (response) {
                                    // Render the products in the index list
                                    $('.index-page .list').html(renderList(response, 'index'));
                                }
                            }); 
                        }
                        break;
                }
            }
            
            window.onhashchange();
        });
    </script>
    </head>
    <body>
        <!-- The index page -->
        <div class="page index-page">
            <!-- A link to go to the cart by changing the hash -->
            <button class="go-to-cart buttons"><?= __('Go to cart') ?></button>

            <!-- The index element where the products list is rendered -->
            <div class="list"></div>
        </div>

        <!-- The cart page -->
        <div class="page cart-page">
            <!-- A link to go to the index by changing the hash -->
            <button class="go-to-index buttons"><?= __('Go to index') ?></button>
            <button class="remove-all buttons" data-id="all" class="remove-all"><?= __('Remove all') ?></button>

            <!-- The cart element where the products list is rendered -->
            <div class="list"></div>

            <!-- The cart element where the form and errors are rendered -->
            <fieldset style="width:28%;margin-left:35%;margin-top:5%;">
                <input type="text" class="name" autocomplete="off" placeholder="<?= __('Name') ?>">
                <p class="name-err" style="color:red"></p>
                <input type="text" class="contact" autocomplete="off" placeholder="<?= __('Contact') ?>">
                <p class="contact-err" style="color:red"></p>
                <input type="text" class="comments" autocomplete="off" placeholder="<?= __('Comments') ?>">
                <p class="comments-err" style="color:red"></p>
                <button class="checkout" style="margin-left:10%;width:80%" value=""><?= __('Checkout') ?></button>
            </fieldset>
        </div>

        <!-- The login page -->
        <div class="page login-page">
            <fieldset style="width:28%;margin-left:35%;margin-top:5%;">
                <p class="credentials-err" style="color:red"></p>
                <input type="text" class="username" autocomplete="off" placeholder="<?= __('Username') ?>">
                <p class="username-err" style="color:red"></p>
                <input type="password" class="password" autocomplete="off" placeholder=<?= __('Password') ?>>
                <p class="password-err" style="color:red"></p>
                <button class="login" style="margin-left:10%;width:80%" value=""><?= __('Login') ?></button>
            </fieldset>
        </div>

        <!-- The products page -->
        <div class="page products-page">
        
            <button class="add-product buttons"><?= __('Add') ?></button>
            <button class="logout buttons"><?= __('Logout') ?></button>

            <!-- The products element where the products list is rendered -->
            <div class="list"></div>
        </div>

        <!-- The product page -->
        <div class="page product-page" style="width:40%;margin-left:30%">
            <fieldset style="margin:6%;background-color: #efb2d1">
                <div style="margin:5%;align:left" class="preview">
                    <img src="" alt="image" width="100" height="100">
                </div>
                <input type="text" class="title" autocomplete="off" placeholder="<?= __('Title') ?>" value="">
                <p class="title-err" style="color:red"></p>
                <input type="text" class="description" autocomplete="off" placeholder="<?= __('Description') ?>" value="">
                <p class="description-err" style="color:red"></p>
                <input type="text" class="price" autocomplete="off" placeholder="<?= __('Price') ?>" value="">
                <p class="price-err" style="color:red"></p>
                <input type="file" class="image" style="margin:8%;">
                <p class="image-err" style="color:red"></p>
                <button type="button" class="save" style="margin-left:10%;width:80%" value=""><?= __('Save') ?></button>
            </fieldset>
        </div>
    </body>
</html>