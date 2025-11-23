@extends('layouts.student')

@section('content')
    <div class="container">
        <h1>Wishlist</h1>
        <p>This page will display your wished courses.</p>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Course Name</th>
                                <th>Subject</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wishlistCourses as $c)
                                <tr>
                                    <td><img src="{{ $c['thumb'] }}" class="img-fluid d-block" style="height: 50px; width: 50px;" /> </td>
                                    <td>{{ $c['title'] }}</td>
                                    <td>{{ $c['subject'] }}</td>
                                    <td>${{ $c['price'] }}</td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm">View Course</a>
                                        <button class="btn btn-danger btn-sm">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
