@extends('layouts.master')
@section('title')
    Products
@endsection
@section('content')
    <h1>{{$product->name}}</h1>
    <p>{{$product->price}}</p>
    <p>{{$product->manufacturer->name}}</p>
    <!-- <p><a href='{{url("product/$product->id/edit")}}'>Edit</a></p> -->
    @auth
        <a href="{{ url('product/' . $product->id . '/edit') }}">Edit Product</a>
    @endauth
    <p>
        <form method="POST" action='{{url("product/$product->id")}}'>
            {{csrf_field()}}
            {{ method_field('DELETE') }}
            <input type="submit" value="Delete">
        </form>
    </p>
@endsection