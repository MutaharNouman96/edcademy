@extends('layouts.student')

@section('content')
    <div class="container">
        <h1>Payments</h1>
        <p>This page will display your payment history.</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Course</th>
                    <th>Method</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2024-11-01</td>
                    <td>Static Course 1</td>
                    <td>Credit Card</td>
                    <td>$49.99</td>
                </tr>
                <tr>
                    <td>2024-10-15</td>
                    <td>Static Course 2</td>
                    <td>PayPal</td>
                    <td>$29.99</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
