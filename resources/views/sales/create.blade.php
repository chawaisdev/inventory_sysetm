@extends('layouts.app')

@section('title', 'Create Sale')

@section('body')
<div class="container-fluid">
    <h2 class="mb-4">Create Sale</h2>

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <div class="mb-3 col-md-6">
            <label>Customer</label>
            <select name="user_id" class="form-control" required>
                <option value="">Select Customer</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <table class="table table-bordered" id="product_table">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Product</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Line Total</th>
                    <th><button type="button" id="add_row" class="btn btn-sm btn-success">+</button></th>
                </tr>
            </thead>
            <tbody id="product_rows">
                <tr>
                    <td>
                        <select name="brand_id[]" class="form-control brand-select" required>
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="product_id[]" class="form-control product-select" required>
                            <option value="">Select Product</option>
                        </select>
                    </td>
                    <td><input type="number" name="available_stock[]" class="form-control stock" readonly></td>
                    <td><input type="number" step="0.01" name="price[]" class="form-control price" required></td>
                    <td><input type="number" name="quantity[]" class="form-control quantity" required></td>
                    <td><input type="number" step="0.01" name="line_total[]" class="form-control line_total" readonly></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove_row">×</button></td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="mb-3 col-md-4">
                <label>Total Amount</label>
                <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Paid Amount</label>
                <input type="number" step="0.01" name="paid_amount" id="paid_amount" class="form-control" required>
            </div>
            <div class="mb-3 col-md-4">
                <label>Due Amount</label>
                <input type="number" step="0.01" name="due_amount" id="due_amount" class="form-control" readonly>
            </div>
            <div class="mb-3 col-md-4">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    <option value="">Select Method</option>
                    <option value="cash">Cash</option>
                    <option value="bank">Bank</option>
                </select>
            </div>
            <div class="mb-3 col-md-4">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}" required>
            </div>
            <div class="mb-3 col-md-12">
                <label>Note</label>
                <textarea name="note" class="form-control"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create Sale</button>
    </form>
</div>

<script>
    function calculateRowTotal(row) {
        let price = parseFloat(row.querySelector('.price')?.value) || 0;
        let qty = parseFloat(row.querySelector('.quantity')?.value) || 0;
        row.querySelector('.line_total').value = (price * qty).toFixed(2);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.line_total').forEach(el => {
            total += parseFloat(el.value) || 0;
        });
        document.getElementById('total_amount').value = total.toFixed(2);
        let paid = parseFloat(document.getElementById('paid_amount').value) || 0;
        document.getElementById('due_amount').value = (total - paid).toFixed(2);
    }

    document.getElementById('product_rows').addEventListener('input', function(e) {
        const row = e.target.closest('tr');
        if (e.target.classList.contains('quantity')) {
            const stock = parseInt(row.querySelector('.stock').value) || 0;
            const qty = parseInt(e.target.value) || 0;
            if (qty > stock) {
                alert('Stock limit exceeded!');
                e.target.value = stock;
            }
        }
        if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
            calculateRowTotal(row);
        }
    });

    document.getElementById('paid_amount').addEventListener('input', calculateGrandTotal);

    document.getElementById('add_row').addEventListener('click', function () {
        let brands = @json($brands);
        let brandOptions = '<option value="">Select Brand</option>';
        brands.forEach(b => {
            brandOptions += `<option value="${b.id}">${b.name}</option>`;
        });

        let newRow = `
            <tr>
                <td>
                    <select name="brand_id[]" class="form-control brand-select" required>
                        ${brandOptions}
                    </select>
                </td>
                <td>
                    <select name="product_id[]" class="form-control product-select" required>
                        <option value="">Select Product</option>
                    </select>
                </td>
                <td><input type="number" name="available_stock[]" class="form-control stock" readonly></td>
                <td><input type="number" step="0.01" name="price[]" class="form-control price" required></td>
                <td><input type="number" name="quantity[]" class="form-control quantity" required></td>
                <td><input type="number" step="0.01" name="line_total[]" class="form-control line_total" readonly></td>
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

    document.getElementById('product_rows').addEventListener('change', async function (e) {
        const row = e.target.closest('tr');

        if (e.target.classList.contains('brand-select')) {
            const brandId = e.target.value;
            const productSelect = row.querySelector('.product-select');
            productSelect.innerHTML = `<option value="">Loading...</option>`;
            const res = await fetch(`/get-products-by-brand/${brandId}`);
            const data = await res.json();
            productSelect.innerHTML = `<option value="">Select Product</option>`;
            data.forEach(product => {
                productSelect.innerHTML += `<option value="${product.id}">${product.name}</option>`;
            });
        }

        if (e.target.classList.contains('product-select')) {
            const productId = e.target.value;
            const res = await fetch(`/get-product-details/${productId}`);
            const data = await res.json();
            row.querySelector('.stock').value = data.stock;
            row.querySelector('.price').value = data.sale_price;
            calculateRowTotal(row);
        }
    });
</script>
@endsection
