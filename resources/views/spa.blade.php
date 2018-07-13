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
            function renderList(products, source) {
                
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
                                ? '<a class="add-to-cart" data-id="' + product.id + '"><button><?= __('Add') ?></button></a>' : '', 
                                source == 'cart' 
                                ? '<a class="remove-from-cart" data-id="' + product.id + '"><button><?= __('Remove') ?></button></a>' : '',
                                source == 'products' ?
                                '<a class="edit-product" data-id="' + product.id + '"><button><?= __('Edit') ?></button></a>': '',
                            '</div>',
                        '</div>'
                    ].join('');
                });
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
                        $('.cart .list').html(renderList(response.products, 'cart'));
                    }
                });
            });

            $(document).on('click', '.remove-all', function() {
                $.ajax('/cart', {
                    dataType: 'json',
                    data: {id: $(this).attr('data-id')},
                    success: function (response) {
                        $('.cart .list').html(renderList(response.products, 'cart'));
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
                        checkout: 'send-order'
                    },
                    success: function (response) {
                        $('.cart .list').html(renderList(response.products, 'cart'));
                        if ('name' in response.errors) {
                            document.getElementById('name-err').innerHTML = response.errors.name;
                        } else {
                            document.getElementById('name-err').innerHTML = '';
                        }
                        if ('contact' in response.errors) {
                            document.getElementById('contact-err').innerHTML = response.errors.contact;
                        } else {
                            document.getElementById('contact-err').innerHTML = '';
                        }
                        if ('comments' in response.errors) {
                            document.getElementById('comments-err').innerHTML = response.errors.comments;
                        } else {
                            document.getElementById('comments-err').innerHTML = '';
                        }
                    }
                });
            });
            
            $(document).on('click', '#login', function() {
                $.ajax('/login', {
                    dataType: 'json',
                    data: {
                        username: document.getElementById('username').value,
                        password: document.getElementById('password').value,
                        login: 'login'
                    },
                    success: function (response) {
                        console.log(response);
                        if ('username' in response.errors) {
                            document.getElementById('username-err').innerHTML = response.errors.username;
                        } else {
                            document.getElementById('username-err').innerHTML = '';
                        }
                        if ('password' in response.errors) {
                            document.getElementById('password-err').innerHTML = response.errors.password;
                        } else {
                            document.getElementById('password-err').innerHTML = '';
                        }
                        if ('credentials' in response.errors) {
                            document.getElementById('credentials-err').innerHTML = response.errors.credentials;
                        } else {
                            document.getElementById('credentials-err').innerHTML = '';
                        }
                        if ('login' in response) {
                            window.location.hash = "#products";
                        }
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
                                $('.cart .list').html(renderList(response.products, 'cart'));
                            }
                        });
                        break;
                    case '#login':
                        // Show the login page
                        $('.login').show();
                        break;
                    case '#products':
                        // Show the products page
                        $('.products').show();
                        // Load the products from the server
                        $.ajax('/products', {
                            dataType: 'json',
                            success: function (response) {
                                console.log(response);
                                // Render the products 
                                $('.products .list').html(renderList(response.products, 'products'));
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

            <!-- The cart element where the form and errors are rendered -->
            <fieldset style="width:28%;margin-left:35%;margin-top:5%;">
                <input type="text" id="name" autocomplete="off" placeholder="Name">
                <p id="name-err" style="color:red"></p>
                <input type="text" id="contact" autocomplete="off" placeholder="Contact">
                <p id="contact-err" style="color:red"></p>
                <input type="text" id="comments" autocomplete="off" placeholder="Comments">
                <p id="comments-err" style="color:red"></p>
                <button id="checkout" style="margin-left:10%;width:80%" value=""><?= __('Checkout') ?></button>
            </fieldset>
        </div>

        <!-- The login page -->
        <div class="page login">
            <fieldset style="width:28%;margin-left:35%;margin-top:5%;">
                <p id="credentials-err" style="color:red"></p>
                <input type="text" id="username" autocomplete="off" placeholder="Username">
                <p id="username-err" style="color:red"></p>
                <input type="password" id="password" autocomplete="off" placeholder="Password">
                <p id="password-err" style="color:red"></p>
                <button id="login" style="margin-left:10%;width:80%" value=""><?= __('Login') ?></button>
            </fieldset>
        </div>

        <!-- The products page -->
        <div class="page products">
        
            <a href="#product" class="button"><button><?= __('Add') ?></button></a>
            <a href="#login" class="button"><button><?= __('Logout') ?></button></a>

            <!-- The products element where the products list is rendered -->
            <div class="list"></div>
        </div>
        
    </body>
</html>