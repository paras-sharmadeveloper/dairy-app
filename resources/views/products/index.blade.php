<x-app-layout>
    <div class="container py-4">

        <div class="d-flex justify-content-between mb-3">
            <h4>Products</h4>
            <a href="{{ route('products.create') }}" class="btn btn-dark">
                + Add Product
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">

                <table class="table">
                    <tr>
                        <th>Name</th>
                        <th>Rate</th>
                        <th>Unit</th>
                        <th>Box</th>
                        <th></th>
                    </tr>

                    @foreach ($products as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->current_rate }}</td>
                            <td>{{ $p->unit }}</td>
                            <td>{{ $p->box_size }}</td>

                            <td class="text-end">
                                <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form method="POST" action="{{ route('products.destroy', $p) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach

                </table>

            </div>
        </div>

    </div>
</x-app-layout>
