<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Stripe integration tutorial</title>
    <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"-->
</head>
<body>

<div class="container">

    <h1 class="my-5 text-center">Laravel Stripe integration tutorial</h1>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card" style="width: 18rem;">
                <img src="{{ asset('images/watch.jpg') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text">{{ $product['description'] }}</p>
                    <a href="{{ route('stripe.checkout', ['price' => $product['price'] , 'product' => $product['name']]) }}" class="btn btn-primary">Pay ${{ $product['price'] }}</a>
                </div>
            </div>
        </div>

    </div>

</div>

</body>

</html>