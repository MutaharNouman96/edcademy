@extends('layouts.student')

@section('content')
    <div class="container">
        <h1>Wishlist</h1>
        <p>This page will display your wished courses.</p>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Static Course 3">
                    <div class="card-body">
                        <h5 class="card-title">Static Course 3</h5>
                        <p class="card-text">Subject: Design</p>
                        <p class="card-text">Price: $19.99</p>
                        <a href="#" class="btn btn-primary">View Course</a>
                        <button class="btn btn-danger btn-sm">Remove</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Static Course 4">
                    <div class="card-body">
                        <h5 class="card-title">Static Course 4</h5>
                        <p class="card-text">Subject: Marketing</p>
                        <p class="card-text">Price: $39.99</p>
                        <a href="#" class="btn btn-primary">View Course</a>
                        <button class="btn btn-danger btn-sm">Remove</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
