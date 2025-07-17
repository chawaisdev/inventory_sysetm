@extends('layouts.app')

@section('title')
    Edit Sale
@endsection

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow p-4">
                    <form method="POST" action="{{ route('sales.update', $sale->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Customer</label>
                            <select name="user_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $sale->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <table class="table table-bordered" id="product_table">
                            <thead>
                                <tr>
                                    <th>Brand</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Discount (%)</th>
                                    <th>Line Total</th>
                                    <th><button type="button" id="add_row" class="btn btn-sm btn-success">+</button></th>
                                </tr>
                            </thead>
                            <tbody id="product_rows">
                                @foreach ($sale->saleItems as $item)
                                    @php
                                        $product = $products->firstWhere('id', $item->product_id);
                                        $brandId = optional($product)->brand_id;
                                    @endphp
                                    <tr>
                                        <td>
                                            <select name="brand_id[]" class="form-control brand-select" required>
                                                <option value="">Select Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ $brandId == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->brand_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="product_id[]" class="form-control product-select" required>
                                                <option value="">Select Product</option>
                                                @foreach ($products->where('brand_id', $brandId) as $productOption)
                                                    <option value="{{ $productOption->id }}"
                                                        {{ $productOption->id == $item->product_id ? 'selected' : '' }}>
                                                        {{ $productOption->name }} (Sale: {{ $productOption->sale_price }},
                                                        Purchase: {{ $productOption->purchase_price }})
                                                    </option>
                                                @endforeach
                                            </select>

                                        </td>
                                        <td><input type="number" step="0.01" name="price[]" class="form-control price"
                                                value="{{ $item->unit_price }}" required></td>
                                        <td><input type="number" name="quantity[]" class="form-control quantity"
                                                value="{{ $item->quantity }}" required></td>
                                        <td><input type="number" step="0.01" name="discount[]"
                                                class="form-control discount" value="{{ $item->discount }}"></td>
                                        <td><input type="number" step="0.01" name="line_total[]"
                                                class="form-control line_total" value="{{ $item->total_price }}" readonly>
                                        </td>
                                        <td><button type="button" class="btn btn-sm btn-danger remove_row">×</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-4">
                                <label>Total Amount</label>
                                <input type="number" step="0.01" name="total_amount" id="total_amount"
                                    class="form-control" value="{{ $sale->total_amount }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Paid Amount</label>
                                <input type="number" step="0.01" name="paid_amount" id="paid_amount"
                                    class="form-control" value="{{ $sale->paid_amount }}" required>
                            </div>
                            <div class="col-md-4">
                                <label>Due Amount</label>
                                <input type="number" step="0.01" name="due_amount" id="due_amount" class="form-control"
                                    value="{{ $sale->due_amount }}" readonly>
                            </div>

                            <div class="col-md-6 mt-3">
                                <label>Payment Method</label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="cash" {{ $sale->payment_method == 'cash' ? 'selected' : '' }}>Cash
                                    </option>
                                    <option value="bank" {{ $sale->payment_method == 'bank' ? 'selected' : '' }}>Bank
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" value="{{ $sale->date }}"
                                    required>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label>Note</label>
                                <textarea name="note" class="form-control">{{ $sale->note }}</textarea>
                            </div>
                        </div>

                        <button class="btn btn-primary mt-4">Update Sale</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Pass products to JS --}}
    <script>
        const products = @json($products);

        // Update product dropdown when brand changes
        function updateProductDropdown(row, brandId) {
            let select = row.querySelector('.product-select');
            select.innerHTML = '<option value="">Select Product</option>';
            products.forEach(product => {
                if (product.brand_id == brandId) {
                    select.innerHTML += `<option value="${product.id}">
                    ${product.name} (Sale: ${product.sale_price}, Purchase: ${product.purchase_price})
                </option>`;
                }
            });
        }


        // Calculate line total for a row
        function calculateRowTotal(row) {
            let price = parseFloat(row.querySelector('.price')?.value) || 0;
            let quantity = parseFloat(row.querySelector('.quantity')?.value) || 0;
            let discount = parseFloat(row.querySelector('.discount')?.value) || 0;

            let total = price * quantity;
            total -= (total * discount / 100);
            row.querySelector('.line_total').value = total.toFixed(2);

            calculateGrandTotal();
        }

        // Calculate total, due based on all rows and paid amount
        function calculateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.line_total').forEach(el => {
                total += parseFloat(el.value) || 0;
            });

            document.getElementById('total_amount').value = total.toFixed(2);

            let paid = parseFloat(document.getElementById('paid_amount').value) || 0;
            document.getElementById('due_amount').value = (total - paid).toFixed(2);
        }

        // Handle brand change and product change events in the product rows
        document.getElementById('product_rows').addEventListener('change', function(e) {
            if (e.target.classList.contains('brand-select')) {
                updateProductDropdown(e.target.closest('tr'), e.target.value);
                // Clear price and line total when brand changes
                const row = e.target.closest('tr');
                row.querySelector('.price').value = '';
                row.querySelector('.line_total').value = '';
                calculateGrandTotal();
            }

            if (e.target.classList.contains('product-select')) {
                const row = e.target.closest('tr');
                const selectedProductId = e.target.value;
                const selectedProduct = products.find(p => p.id == selectedProductId);
                if (selectedProduct) {
                    const priceInput = row.querySelector('.price');
                    priceInput.value = selectedProduct.sale_price || 0;
                    calculateRowTotal(row);
                }
            }
        });

        // Listen for input on price, quantity, discount to recalc line total
        document.getElementById('product_rows').addEventListener('input', function(e) {
            if (
                e.target.classList.contains('price') ||
                e.target.classList.contains('quantity') ||
                e.target.classList.contains('discount')
            ) {
                calculateRowTotal(e.target.closest('tr'));
            }
        });

        // Recalculate due when paid amount changes
        document.getElementById('paid_amount').addEventListener('input', calculateGrandTotal);

        // Add new row with blank inputs
        document.getElementById('add_row').addEventListener('click', function() {
            let brandOptions = '<option value="">Select Brand</option>';
            @foreach ($brands as $brand)
                brandOptions += `<option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>`;
            @endforeach

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
            <td><input type="number" step="0.01" name="price[]" class="form-control price" required></td>
            <td><input type="number" name="quantity[]" class="form-control quantity" required></td>
            <td><input type="number" step="0.01" name="discount[]" class="form-control discount" value="0"></td>
            <td><input type="number" step="0.01" name="line_total[]" class="form-control line_total" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger remove_row">×</button></td>
        </tr>`;
            document.getElementById('product_rows').insertAdjacentHTML('beforeend', newRow);
        });

        // Remove row button
        document.getElementById('product_rows').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove_row')) {
                e.target.closest('tr').remove();
                calculateGrandTotal();
            }
        });

        // Initial calculation for existing rows
        window.addEventListener('load', () => {
            document.querySelectorAll('#product_rows tr').forEach(row => {
                calculateRowTotal(row);
            });
        });
    </script>
@endsection
