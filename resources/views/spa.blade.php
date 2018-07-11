<html>
    <head>
    
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
                html = [
                    '<tr>',
                        '<th><?= __('Title') ?></th>',
                        '<th>Description</th>',
                        '<th>Price</th>',
                    '</tr>'
                ].join('');
                
                $.each(products, function (key, product) {
                    html += [
                        '<tr>',
                            '<td>' + product.title + '</td>',                       
                            '<td>' + product.description + '</td>',                       
                            '<td>' + product.price + '</td>',
                            '<td>' + (
                                source == 'index' 
                                ? '<a class="add-to-cart" data-id="' + product.id + '"><?= __('Add') ?></a>' 
                                : '<a class="remove-from-cart" data-id="' + product.id + '"><?= __('Remove') ?></a>') + '</td>',
                        '</tr>'                        
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
                        $('.cart .list').html(renderList(response, 'cart'));
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
                                // Render the products in the cart list
                                $('.cart .list').html(renderList(response, 'cart'));
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
            <!-- The index element where the products list is rendered -->
            <table class="list"></table>

            <!-- A link to go to the cart by changing the hash -->
            <a href="#cart" class="button">Go to cart</a>
        </div>

        <!-- The cart page -->
        <div class="page cart">
            <!-- The cart element where the products list is rendered -->
            <table class="list"></table>

            <!-- A link to go to the index by changing the hash -->
            <a href="#" class="button">Go to index</a>
        </div>
    </body>
</html>