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
            function renderList(products, source, errors, old) {
                
                if (source != 'login') {
                    html = [].join('');
                    $.each(products, function (key, product) {
                        html += [
                            '<div class="product">',
                                '<img src="/storage/'+ product.image + '" alt="">',
                                '<div class="product_info">',
                                    '<h1>' + product.title + '</h1>',
                                    '<p>' + product.description + '</p>',
                                    '<p><?= __('Price: ') ?>' + product.price + '<?= __('$') ?></p>',
                                    source == 'index' 
                                    ? '<a class="add-to-cart" data-id="' + product.id + '"><button><?= __('Add') ?></button></a>' 
                                    : '<a class="remove-from-cart" data-id="' + product.id + '"><button><?= __('Remove') ?></button></a>',
                                '</div>',
                            '</div>'
                        ].join('');
                    });
                    if (source == 'cart') {
                        html += [
                            '<form action="#cart">',
                                '{{ csrf_field() }}',
                                '<fieldset>',
                                    '<input type="text" id="name" placeholder="<?= __('Name') ?>" autocomplete="off" value="', 
                                        old != null && old.name != null ? '' + old.name + '' : '' ,
                                    '">',
                                    '<p style="color:red">',
                                        errors != null && errors.name != null ? '' + errors.name + '' : '' ,
                                    '</p>',
                                    '<input type="text" id="contact" placeholder="<?= __('Contact Information') ?>" autocomplete="off" value="', 
                                        old != null && old.contact != null ? '' + old.contact + '' : '' ,
                                    '">',
                                    '<p style="color:red">',
                                        errors != null && errors.contact != null ? '' + errors.contact + '' : '' ,
                                    '</p>',
                                    '<input type="text" id="comments" placeholder="<?= __('Comments') ?>" autocomplete="off" value="', 
                                        old != null && old.comments != null ? '' + old.comments + '' : '' ,
                                    '">',
                                    '<p style="color:red">',
                                        errors != null && errors.comments != null ? '' + errors.comments + '' : '' ,
                                    '</p>',
                                    '<input id="checkout" name="checkout" type="submit" value="<?= __('Checkout') ?>">',
                                '</fieldset>',
                            '</form>',
                        ].join('');
                    }
                } else {
                    html = [
                        '<form action="#login" method="get">',
                            '{{ csrf_field() }}',
                            '<fieldset>',
                                '<input type="text" name="username" placeholder="<?= __('Username') ?>" autocomplete="off" >',
                                '<p style="color:red">',
                                    errors != null && errors.username != null ? '' + errors.username + '' : '' ,
                                '</p>',
                                '<input type="password" name="password" placeholder="<?= __('Password') ?>" autocomplete="off" >',
                                '<p style="color:red">',
                                    errors != null && errors.password != null ? '' + errors.password + '' : '' ,
                                '</p>',
                                '<input type="submit" id="login" name="login" value="<?= __('Login') ?>">',
                            '</fieldset>',
                        '</form>',
                    ].join('');
                }
                return html;
            }

            $(document).on('click', '.add-to-cart', function() {
                $.ajax('/index', {
                    dataType: 'json',
                    data: {id: $(this).attr('data-id')},
                    success: function (response) {
                        $('.index .list').html(renderList(response, 'index'));
                    }
                });
            });
            
            $(document).on('click', '.remove-from-cart', function() {
                $.ajax('/cart', {
                    dataType: 'json',
                    data: {id: $(this).attr('data-id')},
                    success: function (response) {
                        $('.cart .list').html(renderList(response, 'cart'));
                    }
                });
            });

            $(document).on('click', '.remove-all', function() {
                $.ajax('/cart', {
                    dataType: 'json',
                    data: {id: $(this).attr('data-id')},
                    success: function (response) {
                        $('.cart .list').html(renderList(response, 'cart'));
                    }
                });
            });
            
            $(document).on('click', '#checkout', function() {               
                $.ajax('/cart', {
                    dataType: 'json',
                    data: {
                        name: document.getElementById('name').value,
                        contact: document.getElementById('contact').value,
                        comments: document.getElementById('comments').value,
                        checkout: document.getElementById('checkout').value
                    },
                    success: function (response) {
                        $('.cart .list').html(renderList(response.products, 'cart', response.errors, response.old));
                    }
                });
            });

            $(document).on('click', '#login', function() {               
                $.ajax('/login', {
                    dataType: 'json',
                    data: {
                        username: document.getElementById('username').value,
                        password: document.getElementById('password').value
                    },
                    success: function (response) {
                        $('.login .list').html(renderList(response, 'login', response.errors, response.old));
                    }
                });
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
                        $('.cart').show();
                        // Load the cart products from the server
                        $.ajax('/cart', {
                            dataType: 'json',
                            success: function (response) {
                                console.log(response);
                                // Render the products in the cart list
                                $('.cart .list').html(renderList(response, 'cart'));
                            }
                        });
                        break;
                    case '#login':
                        $('.login').show();
                        
                        $.ajax('/login', {
                            dataType: 'json',
                            success: function (response) {
                                $('.login .list').html(renderList(response, 'login'));
                            }
                        });
                        break;
                    default:
                        // If all else fails, always default to index
                        // Show the index page
                        $('.index').show();
                        // Load the index products from the server
                        $.ajax('/', {
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the index list
                                $('.index .list').html(renderList(response, 'index'));
                            }
                        }); 
                        break;
                }
            }
            
            window.onhashchange();
        });
    </script>
    </head>
    <body>
        <!-- The index page -->
        <div class="page index">
            <!-- A link to go to the cart by changing the hash -->
            <a href="#cart" class="button"><button><?= __('Go to cart') ?></button></a>

            <!-- The index element where the products list is rendered -->
            <div class="list"></div>
        </div>

        <!-- The cart page -->
        <div class="page cart">
            <!-- A link to go to the index by changing the hash -->
            <a href="#" class="button"><button><?= __('Go to index') ?></button></a>
            <a href="#cart" data-id="all" class="remove-all"><button><?= __('Remove all') ?></button></a>

            <!-- The cart element where the products list is rendered -->
            <div class="list"></div>
        </div>
        <div class="page login">
            <!-- The login element where the products list is rendered -->
            <div class="list"></div>
        </div>
    </body>
</html>