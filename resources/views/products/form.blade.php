<x-app-layout>
    <div class="container py-4">

        <h4>
            {{ $product->exists ? 'Edit Product' : 'Add Product' }}
        </h4>

        <div class="card">
            <div class="card-body">

                <form method="POST"
                    action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}">

                    @csrf
                    @if ($product->exists)
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label>Name</label>
                        <input name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Price per unit</label>
                        <input name="current_rate" type="number" step="0.01"
                            value="{{ old('current_rate', $product->current_rate) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Unit (pkt / kg / pcs)</label>
                        <input name="unit" value="{{ old('unit', $product->unit) }}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Packets per crate (optional)</label>
                        <input name="box_size" type="number" value="{{ old('box_size', $product->box_size) }}"
                            class="form-control">
                    </div>

                    <button class="btn btn-success">
                        {{ $product->exists ? 'Update' : 'Save' }}
                    </button>

                </form>

            </div>
        </div>

    </div>
</x-app-layout>
