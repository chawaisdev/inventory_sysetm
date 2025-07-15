@extends('layouts.app')

@section('title')
    User Index
@endsection

@section('body')
    <div style="font-family: monospace; font-size: 12px;">
        <h5 style="text-align: center;">My Shop</h5>
        <p>Date: {{ $sale->date->format('Y-m-d') }}</p>
        <p>Invoice: {{ $sale->invoice_no }}</p>
        <p>Customer: {{ $sale->user->name ?? 'N/A' }}</p>
        <hr>
        <table style="width:100%;">
            @foreach ($sale->items as $item)
                <tr>
                    <td colspan="2">{{ $item->product->name ?? 'Product' }}</td>
                </tr>
                <tr>
                    <td>{{ $item->quantity }} x {{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align:right;">
                        {{ number_format($item->total_price - ($item->total_price * ($item->discount ?? 0)) / 100, 2) }}
                    </td>
                </tr>
            @endforeach
        </table>
        <hr>
        <p>Total: <strong>{{ number_format($sale->total_amount, 2) }}</strong></p>
        <p>Paid: <strong>{{ number_format($sale->paid_amount, 2) }}</strong></p>
        <p>Due: <strong>{{ number_format($sale->due_amount, 2) }}</strong></p>
        <p>Payment: {{ ucfirst($sale->payment_method) }}</p>
        <p>Note: {{ $sale->note }}</p>
        <hr>
        <p style="text-align:center;">Thanks for your purchase!</p>
    </div>
    <script>
        setTimeout(() => window.print(), 300);
    </script>
@endsection
