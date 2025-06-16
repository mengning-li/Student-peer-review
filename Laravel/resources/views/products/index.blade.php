@extends('layouts.master')
@section('title')
    Products
@endsection
@section('content')
<ul>
    @foreach ($products as $product)
        <a href="product/{{$product->id}}"><li>{{ $product->name }}</li></a>
    @endforeach
</ul>
<a href="{{ url('product/create') }}">
    <button type="button">Create</button>
</a>
@endsection
