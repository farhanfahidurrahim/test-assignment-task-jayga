@extends('layouts.master')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between end">
                                <h4 class="card-title">Products List</h4>

                                {{-- seacrh product list algolia --}}
                                <form action="{{ route('product.search') }}" method="GET" class="d-flex">
                                    <input type="text" name="query" class="form-control" placeholder="Search Name/Category" value="{{ request()->query('query') }}">
                                    <button type="submit" class="btn btn-primary btn-sm ms-2">Search</button>
                                </form>

                                <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm">Add New Product</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Attribute</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category->name }}</td>
                                            <td>{{ $product->price }}</td>
                                            <td>
                                                <ul>
                                                    @foreach ($product->attributes as $attribute)
                                                        <li>{{ $attribute->name }}: {{ $attribute->pivot->value }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('product.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
