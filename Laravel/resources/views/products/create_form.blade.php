@extends('layouts.master')

@section('content')
<h1>Create a new Product</h1>
<!-- Display global error messages (if any) -->
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert">
            <li>{{$error}}</li>
        </div>
    @endforeach  
@endif

<form method="POST" action="{{ url('product') }}">
    {{ csrf_field() }}
    <p><label>Name:</label>
        <input type="text" name="name" value="{{ old('name') }}"></p>
    <p><label>Price:</label>
        <input type="text" name="price" value="{{ old('price') }}"></p>
    <p>
        <label for="manufacturer">Manufacturer:</label>
        <select name="manufacturer">
            @foreach ($manufacturers as $manufacturer)
                @if($manufacturer->id == old('manufacturer'))
                    <option value="{{$manufacturer->id}}" selected="selected">{{$manufacturer->name}}</option>
                @else
                    <option value="{{$manufacturer->id}}">{{$manufacturer->name}}</option>
                @endif
            @endforeach
        </select></p>
    <p>
        <input type="submit" value="Create">
    </p>
</form>


@endsection