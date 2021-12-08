@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ $order->patient->name }}</td>
                <td><strong>Patient ID:</strong></td>
                <td>{{ $order->patient->patient_id }}</td>
            </tr>
        </table>

        <hr>

        <div class="row justify-content-end">
            <div class="col-sm-6">
                <h4>Receipt</h4>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <button onclick="printJS('{{ $receiptPath }}')" class="btn btn-outline-primary"><i class="feather icon-printer"></i> Print</button>
                </div>
            </div>
        </div>

        <table class="table">

        </table>

    </div>
</div>

@endsection
