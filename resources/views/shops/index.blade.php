<x-app-layout>
    <div class="container py-4">

        <div class="d-flex justify-content-between mb-3">
            <h4>Shops</h4>
            <a href="{{ route('shops.create') }}" class="btn btn-dark">
                + Add Shop
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
                        <th>Mobile</th>
                        <th>Order Link</th>
                        <th></th>
                    </tr>

                    @foreach ($shops as $shop)
                        <tr>
                            <td>{{ $shop->name }}</td>
                            <td>{{ $shop->mobile }}</td>

                            <td style="width:250px">


                                <input class="form-control" value="{{ url('/order/' . $shop->token) }}" readonly>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('shops.edit', $shop) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form method="POST" action="{{ route('shops.destroy', $shop) }}" class="d-inline">
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
