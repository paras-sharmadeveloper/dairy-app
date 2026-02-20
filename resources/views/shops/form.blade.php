<x-app-layout>
    <div class="container py-4">

        <h4>{{ $shop->exists ? 'Edit Shop' : 'Add Shop' }}</h4>

        <div class="card">
            <div class="card-body">

                <form method="POST"
                    action="{{ $shop->exists ? route('shops.update', $shop) : route('shops.store') }}">
                    @csrf
                    @if ($shop->exists)
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label>Name</label>
                        <input name="name" value="{{ old('name', $shop->name) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Mobile</label>
                        <input name="mobile" value="{{ old('mobile', $shop->mobile) }}" class="form-control">
                    </div>

                    <button class="btn btn-success">
                        {{ $shop->exists ? 'Update' : 'Save' }}
                    </button>

                </form>

            </div>
        </div>

    </div>
</x-app-layout>
