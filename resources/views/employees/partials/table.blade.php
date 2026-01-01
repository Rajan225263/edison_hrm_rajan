@forelse($employees as $emp)
<tr>
    <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
    <td>{{ $emp->email }}</td>
    <td>{{ $emp->department->name }}</td>
    <td>{{ $emp->skills->pluck('name')->join(', ') }}</td>
    <td>
        <a href="{{ route('employees.show',$emp) }}" class="btn btn-sm btn-info">View</a>
        <a href="{{ route('employees.edit',$emp) }}" class="btn btn-sm btn-warning">Edit</a>
       {{-- DELETE --}}
                <form action="{{ route('employees.destroy',$emp) }}"
                      method="POST"
                      style="display:inline"
                      onsubmit="return confirm('Are you sure you want to delete this employee?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center">No employees found</td>
</tr>
@endforelse
