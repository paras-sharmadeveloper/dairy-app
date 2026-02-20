<x-app-layout>
    <div class="container py-4">

        <h3 class="mb-4">🥛 Vendor Dashboard</h3>

        <div class="row g-3">

            <div class="col-md-3">
                <a href="/shops" class="btn btn-primary w-100 p-3">Shops</a>
            </div>

            <div class="col-md-3">
                <a href="/products" class="btn btn-success w-100 p-3">Products</a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('orders.vendorCreate') }}" class="btn btn-warning w-100 p-3">New Order</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('orders.index') }}" class="btn btn-dark w-100 p-3">
                    Orders
                </a>
            </div>




            <div class="col-md-3">
                <a href="#" class="btn btn-dark w-100 p-3">Reports</a>
            </div>

        </div>

    </div>
</x-app-layout>
