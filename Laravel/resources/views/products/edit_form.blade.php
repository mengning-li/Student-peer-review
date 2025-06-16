@extends('layouts.master')

@section('content')
<h1>Edit Product</h1>
<form action="{{ url("product/$product->id") }}" method="POST">
    @csrf
    @method('patch')
    <p>
        <label>Name: <input type="text" name="name" value="{{ old('name', $product->name) }}"></label>
        <span class="alert">
            {{$errors->first('name')}}
        </span>
    </p>
    
    <p>
        <label>Price: <input type="text" name="price" value="{{ old('price', $product->price) }}"></label>
        <span class="alert">
            {{$errors->first('price')}}
        </span>
    </p>
    
    <p>
        <label>Manufacturer
            <select name="manufacturer">
                @foreach ($manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->id }}" {{ $manufacturer->id == $product->manufacturer_id ? 'selected' : '' }}>{{ $manufacturer->name }}</option>
                @endforeach
            </select>
        </label>
        <span class="alert">
            {{$errors->first('manufacturer')}}
        </span>
    </p>
    
    <button type="submit">edit</button>
</form>
@endsection
