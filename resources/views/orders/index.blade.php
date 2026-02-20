<x-app-layout>

    <div class="container py-4">

        <div class="d-flex justify-content-between mb-3">
            <h4>Orders</h4>

            <a href="{{ route('orders.vendorCreate') }}" class="btn btn-dark">
                New Order
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                <table class="table">

                    <tr>
                        <th>Date</th>
                        <th>Shop</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Invoice</th>
                    </tr>

                    @foreach ($orders as $order)
                        <tr>

                            <td>{{ $order->date }}</td>

                            <td>{{ $order->shop->name }}</td>

                            <td>
                                @if ($order->source == 'vendor')
                                    <span class="badge bg-primary">Vendor</span>
                                @else
                                    <span class="badge bg-info">Shop</span>
                                @endif
                            </td>

                            <td>
                                @if ($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-success">Delivered</span>
                                @endif
                            </td>

                            <td>

                                @if ($order->status == 'pending')
                                    <a href="{{ route('orders.deliver', $order) }}" class="btn btn-sm btn-success">
                                        Deliver
                                    </a>
                                @endif

                            </td>
                            <td class="text-end">

                                {{-- DELIVERY BUTTON --}}
                                    <a href="{{ route('orders.deliver', $order) }}" class="btn btn-sm btn-success">
                                        Deliver
                                    </a>

                                {{-- INVOICE BUTTON --}}
                                <a href="{{ route('orders.invoice', $order) }}" class="btn btn-sm btn-dark">
                                    Invoice
                                </a>

                            </td>


                        </tr>
                    @endforeach

                </table>

            </div>
        </div>

    </div>

</x-app-layout>
