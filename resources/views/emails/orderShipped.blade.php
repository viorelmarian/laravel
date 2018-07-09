<html>
    <body>
        <div>
        <h2>Name:</h2>
        <h4>{{ $formInfo['name'] }}</h4>
        <h2>Contact Information:</h2>
        <h4>{{ $formInfo['contact'] }}</h4>
        <h2>Comments:</h2>
        <h4>{{ $formInfo['comments'] }}</h4>
        </div>
        <?php foreach($products as $product) : ?>
        <div>
            <div>
                <img src="{{ $message->embed(asset('storage/'. $product['image'])) }}" width="100" height= "100" align="left" style="border:2px solid black;margin:10px;">
                <h1>Product title: {{ $product->title }}</h1>
                <h3>Product description: {{ $product->description }}</h3>
                <h3>Price: {{ $product->price }} $</h3>
            </div>
            <br>
            <hr>
        </div>
        <?php endforeach ?>
    </body>
</html>