@extends('layouts.app')

@section('title', 'Purchase Create')

@section('body')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-white shadow p-4">
                <form action="{{ route('purchase.store') }}" method="POST">
                    @csrf

                    <div class="mb-3 col-md-6">
                        <label class="form-label">Supplier</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Select Supplier</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <table class="table table-bordered" id="product_table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Discount (%)</th>
                                <th>Line Total</th>
                                <th>
                                    <button type="button" id="add_row" class="btn btn-sm btn-success">+</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="product_rows">
                            <tr>
                                <td>
                                    <select name="product_id[]" class="form-control product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-brand="{{ $product->brand_id }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="brand_name[]" class="form-control brand-display" readonly>
                                    <input type="hidden" name="brand_id[]" class="brand-id">
                                </td>
                                <td><input type="number" name="price[]" class="form-control price" step="0.01" required></td>
                                <td><input type="number" name="quantity[]" class="form-control quantity" required></td>
                                <td><input type="number" name="discount[]" class="form-control discount" step="0.01" value="0"></td>
                                <td><input type="number" name="line_total[]" class="form-control line_total" step="0.01" readonly></td>
                                <td><button type="button" class="btn btn-sm btn-danger remove_row">×</button></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label>Total Amount</label>
                            <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Paid Amount</label>
                            <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Due Amount</label>
                            <input type="number" step="0.01" name="due_amount" id="due_amount" class="form-control" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="bank">Bank</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label>Note</label>
                            <textarea name="note" class="form-control"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Purchase</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- JS for dynamic rows and calculations --}}
<script>
    const brands = @json($brands);
    const products = @json($products);

    function calculateRowTotal(row) {
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const discount = parseFloat(row.querySelector('.discount').value) || 0;

        const sub = price * quantity;
        const total = sub - (sub * discount / 100);
        row.querySelector('.line_total').value = total.toFixed(2);

        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.line_total').forEach(el => {
            total += parseFloat(el.value) || 0;
        });
        document.getElementById('total_amount').value = total.toFixed(2);

        const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
        document.getElementById('due_amount').value = (total - paid).toFixed(2);
    }

    document.getElementById('product_rows').addEventListener('input', function(e) {
        if (['price', 'quantity', 'discount'].some(cls => e.target.classList.contains(cls))) {
            const row = e.target.closest('tr');
            calculateRowTotal(row);
        }
    });

    document.getElementById('paid_amount').addEventListener('input', calculateGrandTotal);

    document.getElementById('add_row').addEventListener('click', function () {
        let productOptions = `<option value="">Select Product</option>`;
        products.forEach(p => {
            productOptions += `<option value="${p.id}" data-brand="${p.brand_id}">${p.name}</option>`;
        });

        const newRow = `
            <tr>
                <td>
                    <select name="product_id[]" class="form-control product-select" required>
                        ${productOptions}
                    </select>
                </td>
                <td>
                    <input type="text" name="brand_name[]" class="form-control brand-display" readonly>
                    <input type="hidden" name="brand_id[]" class="brand-id">
                </td>
                <td><input type="number" name="price[]" class="form-control price" step="0.01" required></td>
                <td><input type="number" name="quantity[]" class="form-control quantity" required></td>
                <td><input type="number" name="discount[]" class="form-control discount" step="0.01" value="0"></td>
                <td><input type="number" name="line_total[]" class="form-control line_total" step="0.01" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger remove_row">×</button></td>
            </tr>
        `;
        document.getElementById('product_rows').insertAdjacentHTML('beforeend', newRow);
    });

    document.getElementById('product_rows').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove_row')) {
            e.target.closest('tr').remove();
            calculateGrandTotal();
        }
    });

    document.getElementById('product_rows').addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const row = e.target.closest('tr');
            const brandId = e.target.selectedOptions[0].dataset.brand;
            const brand = brands.find(b => b.id == brandId);
            row.querySelector('.brand-display').value = brand ? brand.brand_name : '';
            row.querySelector('.brand-id').value = brandId || '';
        }
    });
</script>
@endsection
