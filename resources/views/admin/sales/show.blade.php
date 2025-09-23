@extends('admin.layout')

@section('content')
<div class="container">
    <h3>Sale #{{ $sale->id }} Details</h3>

    <div class="mb-3">
        <strong>Customer:</strong> {{ $sale->user->name ?? 'N/A' }}<br>
        <strong>Sale Date:</strong> {{ $sale->sale_date ?? 'N/A' }}<br>
        <strong>Grand Total:</strong> {{ number_format($sale->grand_total, 2) }} BDT
    </div>

    <h4>Items</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Line Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sale->itemsWithTrashed ?? collect() as $item)
            <tr id="item-row-{{ $item->id }}">
                <td>{{ $item->product->name ?? 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price,2) }}</td>
                <td>{{ number_format($item->discount,2) }}</td>
                <td>{{ number_format($item->line_total,2) }}</td>
                <td>
                    @if(!$item->trashed())
                    <button class="btn btn-sm btn-danger soft-delete-item" data-id="{{ $item->id }}">Delete</button>
                    @else
                    <button class="btn btn-sm btn-success restore-item" data-id="{{ $item->id }}">Restore</button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No items found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h4>Notes</h4>
    
    <ul id="notes-list">
        @forelse($notes ?? collect() as $note)
        <li>
            <strong>{{ $note->user->name ?? 'Unknown' }}:</strong>
            {{ $note->note }}
            <small class="text-muted">({{ $note->created_at->format('d M Y H:i') }})</small>
        </li>
        @empty
        <li>No notes available</li>
        @endforelse
    </ul>


    <div class="mt-3">
        <h5>Add Note</h5>
        <form id="noteForm">
            @csrf
            <input type="hidden" name="sale_id" value="{{ $sale->id }}">
            <textarea name="note" class="form-control mb-2" required></textarea>
            <button class="btn btn-primary btn-sm">Add Note</button>
        </form>
        <div id="note-message" class="mt-2"></div>
    </div>

    <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add Note
        document.getElementById('noteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = this;
            fetch("{{ route('admin.sales.notes.store') }}", {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let li = document.createElement('li');
                        li.innerHTML = `<strong>${data.user}:</strong> ${data.note} <small class="text-muted">(just now)</small>`;
                        document.getElementById('notes-list').prepend(li);
                        form.reset();
                        document.getElementById('note-message').innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    } else {
                        document.getElementById('note-message').innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    }
                })
                .catch(err => {
                    document.getElementById('note-message').innerHTML = `<div class="alert alert-danger">Error!</div>`;
                });
        });

        // Soft-delete & Restore
        document.addEventListener('click', function(e) {
            // Soft Delete
            if (e.target.classList.contains('soft-delete-item')) {
                if (!confirm('Are you sure?')) return;
                let id = e.target.dataset.id;
                fetch(`/admin/sales/items/${id}/soft-delete`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            e.target.closest('td').innerHTML = `<button class="btn btn-sm btn-success restore-item" data-id="${id}">Restore</button>`;
                        } else alert(data.message || 'Error');
                    });
            }

            // Restore
            if (e.target.classList.contains('restore-item')) {
                let id = e.target.dataset.id;
                fetch(`/admin/sale-items/${id}/restore`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            e.target.closest('td').innerHTML = `<button class="btn btn-sm btn-danger soft-delete-item" data-id="${id}">Delete</button>`;
                        } else alert(data.message || 'Error');
                    });
            }
        });
    });
</script>
@endpush