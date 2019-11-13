@extends('layouts.app')

@section('content')
<div class="container d-flex flex-wrap">
    @foreach($products as $product)
    @isset($product['productversion'][0]['image'][0]['url'])
    <div class="col-md-4 col-12">
        <div class="card mb-4">
            <img class="card-img-top" src="images/{{$product['productversion'][0]['image'][0]['url']}}" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">{{$product['name']}}</h5>
                <p class="card-text">price: {{$product['productversion'][0]['price']}}</p>
                <a href="/products/{{$product['id']}}" class="btn btn-primary">Product</a>
            </div>
        </div>
    </div>
    @endisset
    @endforeach
</div>
@endsection