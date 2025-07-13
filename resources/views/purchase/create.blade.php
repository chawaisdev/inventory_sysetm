@extends('layouts.app')

@section('title')
    Purchase Create
@endsection

@section('body')
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Purchase</li>
                </ol>
            </nav>
        </div>

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
                                    <th>Product Name</th>
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
                                    <td><input type="text" name="product_name[]" class="form-control" required></td>
                                    <td style="min-width: 180px;">
                                        <select name="brand_id[]" class="form-control" required>
                                            <option value="">Select Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td><input type="number" step="0.01" name="price[]" class="form-control price"
                                            required></td>
                                    <td><input type="number" name="quantity[]" class="form-control quantity" required></td>
                                    <td><input type="number" step="0.01" name="discount[]" class="form-control discount"
                                            value="0"></td>
                                    <td><input type="number" step="0.01" name="line_total[]"
                                            class="form-control line_total" readonly></td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove_row">×</button></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Total Amount</label>
                                <input type="number" step="0.01" name="total_amount" id="total_amount"
                                    class="form-control" readonly>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Paid Amount</label>
                                <input type="number" step="0.01" name="paid_amount" id="paid_amount"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Due Amount</label>
                                <input type="number" step="0.01" name="due_amount" id="due_amount" class="form-control"
                                    readonly>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="">Select Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank">Bank</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control"
                                    value="{{ now()->toDateString() }}" required>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Create Purchase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Pass brands to JavaScript --}}
    <script>
        const brands = @json($brands);
    </script>

    <script>
        function calculateRowTotal(row) {
            let price = parseFloat(row.querySelector('.price')?.value) || 0;
            let quantity = parseFloat(row.querySelector('.quantity')?.value) || 0;
            let discountPercent = parseFloat(row.querySelector('.discount')?.value) || 0;

            let subTotal = price * quantity;
            let discountAmount = (subTotal * discountPercent) / 100;
            let total = subTotal - discountAmount;

            row.querySelector('.line_total').value = total.toFixed(2);
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.line_total').forEach(el => {
                total += parseFloat(el.value) || 0;
            });

            document.getElementById('total_amount').value = total.toFixed(2);

            let paid = parseFloat(document.getElementById('paid_amount').value) || 0;
            let due = total - paid;
            document.getElementById('due_amount').value = due.toFixed(2);
        }

        document.getElementById('product_rows').addEventListener('input', function(e) {
            if (
                e.target.classList.contains('price') ||
                e.target.classList.contains('quantity') ||
                e.target.classList.contains('discount')
            ) {
                let row = e.target.closest('tr');
                calculateRowTotal(row);
            }
        });

        document.getElementById('paid_amount').addEventListener('input', calculateGrandTotal);

        document.getElementById('add_row').addEventListener('click', function() {
            let brandOptions = '<option value="">Select Brand</option>';
            brands.forEach(brand => {
                brandOptions += `<option value="${brand.id}">${brand.brand_name}</option>`;
            });

            let newRow = `
            <tr>
                <td><input type="text" name="product_name[]" class="form-control" required></td>
                <td>
                    <select name="brand_id[]" class="form-control" required>
                        ${brandOptions}
                    </select>
                </td>
                <td><input type="number" step="0.01" name="price[]" class="form-control price" required></td>
                <td><input type="number" name="quantity[]" class="form-control quantity" required></td>
                <td><input type="number" step="0.01" name="discount[]" class="form-control discount" value="0"></td>
                <td><input type="number" step="0.01" name="line_total[]" class="form-control line_total" readonly></td>
                <td><button type="button" class="btn btn-sm btn-danger remove_row">×</button></td>
            </tr>
        `;
            document.getElementById('product_rows').insertAdjacentHTML('beforeend', newRow);
        });

        document.getElementById('product_rows').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove_row')) {
                let row = e.target.closest('tr');
                row.remove();
                calculateGrandTotal();
            }
        });
    </script>
@endsection
