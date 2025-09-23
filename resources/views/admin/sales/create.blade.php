@extends('admin.layout')

@section('content')
<div class="container">
    <h2>Create Sale</h2>

    <form id="saleForm">
        @csrf

        {{-- Customer --}}
        <div class="mb-3">
            <label>Customer</label>
            <select name="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Items Table --}}
        <div id="items">
            <div class="row font-weight-bold mb-2">
                <div class="col">Product</div>
                <div class="col">Quantity</div>
                <div class="col">Price</div>
                <div class="col">Discount</div>
                <div class="col">Action</div>
            </div>

            {{-- First Item Row --}}
            <div class="row item mb-2">
                <div class="col">
                    <select name="items[0][product_id]" class="form-control product">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->availability->stock ?? 0 }}">
                                {{ $product->name }} (Stock: {{ $product->availability->stock ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <input type="number" name="items[0][quantity]" class="form-control qty" value="1" min="1">
                    <small class="text-danger stock-warning" style="display:none;">Quantity exceeds stock!</small>
                </div>
                <div class="col"><input type="number" name="items[0][price]" class="form-control price" value="{{ $products[0]->price }}"></div>
                <div class="col"><input type="number" name="items[0][discount]" class="form-control discount" value="0"></div>
                <div class="col"><button type="button" class="btn btn-danger remove">X</button></div>
            </div>
        </div>

        {{-- Add Item --}}
        <button type="button" id="addItem" class="btn btn-secondary mt-2">+ Add Item</button>

        {{-- Grand Total --}}
        <div class="mt-3">
            <strong>Grand Total: </strong> <span id="grandTotal">0.00</span> BDT
        </div>

        {{-- Notes --}}
        <div class="mb-3 mt-3">
            <label>Notes</label>
            <textarea name="note" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Sale</button>
    </form>

    <div id="message" class="mt-3"></div>
</div>
@endsection

@push('scripts')
<script>
let index = 1;

// Calculate grand total
function calculateTotal() {
    let total = 0;
    document.querySelectorAll('#items .item').forEach(row => {
        let qty = parseFloat(row.querySelector('.qty').value) || 0;
        let price = parseFloat(row.querySelector('.price').value) || 0;
        let discount = parseFloat(row.querySelector('.discount').value) || 0;
        total += (price * qty - discount);
    });
    document.getElementById('grandTotal').innerText = total.toFixed(2);
}

// Add new item
$('#addItem').on('click', function() {
    let template = `
    <div class="row item mb-2">
        <div class="col">
            <select name="items[${index}][product_id]" class="form-control product">
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->availability->stock ?? 0 }}">
                        {{ $product->name }} (Stock: {{ $product->availability->stock ?? 0 }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <input type="number" name="items[${index}][quantity]" class="form-control qty" value="1" min="1">
            <small class="text-danger stock-warning" style="display:none;">Quantity exceeds stock!</small>
        </div>
        <div class="col"><input type="number" name="items[${index}][price]" class="form-control price" value="0"></div>
        <div class="col"><input type="number" name="items[${index}][discount]" class="form-control discount" value="0"></div>
        <div class="col"><button type="button" class="btn btn-danger remove">X</button></div>
    </div>`;
    $('#items').append(template);
    index++;
});

// Remove item row
$(document).on('click', '.remove', function() {
    $(this).closest('.item').remove();
    calculateTotal();
});

// Stock validation
$(document).on('input', '.qty', function() {
    let qty = parseInt($(this).val()) || 0;
    let stock = parseInt($(this).closest('.item').find('.product option:selected').data('stock')) || 0;

    if (qty > stock) {
        $(this).closest('.item').find('.stock-warning').show();
    } else {
        $(this).closest('.item').find('.stock-warning').hide();
    }
    calculateTotal();
});

// On product change -> update price and check stock
$(document).on('change', '.product', function() {
    let price = $(this).find(':selected').data('price');
    $(this).closest('.item').find('.price').val(price);

    let stock = $(this).find(':selected').data('stock') || 0;
    let qtyInput = $(this).closest('.item').find('.qty');

    if (parseInt(qtyInput.val()) > stock) {
        qtyInput.val(stock > 0 ? stock : 1);
        qtyInput.trigger('input');
    }

    calculateTotal();
});

// AJAX submit
$('#saleForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: "{{ route('admin.sales.store') }}",
        method: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function(res) {
            $('#message').html('<div class="alert alert-success">' + res.message + '</div>');
            $('#saleForm')[0].reset();
            $('#items .item').not(':first').remove();
            calculateTotal();
        },
        error: function(err) {
            $('#message').html('<div class="alert alert-danger">Error creating sale!</div>');
        }
    });
});

// Initial total
calculateTotal();
</script>
@endpush
