@extends('admin.layout')

@section('content')
<div class="container">
    <h2>Product Details</h2>

    {{-- Product Details Table --}}
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $product->id }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $product->name }}</td>
        </tr>
        <tr>
            <th>Price</th>
            <td>{{ number_format($product->price, 2) }} BDT</td>
        </tr>
        <tr>
            <th>Initial Stock</th>
            <td>{{ number_format($product->stock) }}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $product->description ?? '-' }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $product->created_at->format('d M Y H:i') }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $product->updated_at->format('d M Y H:i') }}</td>
        </tr>
    </table>

    {{-- Notes Section --}}
    <h4>Notes</h4>
    <ul id="notes-list">
        @forelse($notes as $note)
        <li>
            <strong>{{ $note->user->name ?? 'Unknown' }}:</strong>
            {{ $note->note }}
            <small class="text-muted">({{ $note->created_at->format('d M Y H:i') }})</small>
        </li>
        @empty
        <li>No notes yet</li>
        @endforelse
    </ul>

    {{-- Add Note Form --}}
    <div class="mb-3 mt-2">
        <textarea name="note" class="form-control mb-2" placeholder="Add a note"></textarea>
        <button type="button" id="addNoteBtn" class="btn btn-sm btn-primary">Add Note</button>
    </div>

    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addNoteBtn').addEventListener('click', function() {
            let noteTextarea = document.querySelector('textarea[name="note"]');
            let note = noteTextarea.value.trim();
            if (!note) return alert('Note cannot be empty.');

            let formData = new FormData();
            formData.append('note', note);
            formData.append('_token', '{{ csrf_token() }}');

            fetch("{{ route('admin.products.notes.store', $product->id) }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let li = document.createElement('li');
                        li.innerHTML = `<strong>${data.user}:</strong> ${data.note} <small>(just now)</small>`;
                        document.getElementById('notes-list').prepend(li);
                        noteTextarea.value = '';
                    } else {
                        alert(data.message || 'Error adding note.');
                    }
                })
                .catch(err => alert('Error!'));
        });
    });
</script>
@endpush