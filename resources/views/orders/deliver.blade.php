<x-app-layout>

    <div class="container py-4">

        <h4 class="mb-3">
            Delivery - {{ $order->shop->name }}
        </h4>

        <div class="card">
            <div class="card-body">
                <a href="{{ route('orders.invoice', $order) }}" class="btn btn-sm btn-info float-end">
                    View Invoice
                </a>
                <form method="POST" action="{{ route('orders.saveDelivery', $order) }}">
                    @csrf

                    <table class="table">

                        <tr>
                            <th>Product</th>
                            <th>Ordered</th>
                            <th>Delivered</th>
                            <th>Rate</th>
                            <th>Amount</th>
                        </tr>

                        @php $total=0; @endphp

                        @foreach ($order->items as $item)
                            @php
                                $delivered = $item->delivered_qty ?? $item->ordered_qty;
                                $amt = $delivered * $item->rate_snapshot;
                                $total += $amt;
                            @endphp

                            <tr>

                                <td>{{ $item->product->name }}</td>

                                <td>{{ $item->ordered_qty }}</td>

                                <td style="width:120px">
                                    <input type="number" step="0.1" name="delivered[{{ $item->id }}]"
                                        value="{{ $delivered }}" class="form-control qty-input"
                                        data-rate="{{ $item->rate_snapshot }}" oninput="calcTotal()">
                                </td>

                                <td>₹{{ $item->rate_snapshot }}</td>

                                <td class="amt">₹{{ number_format($amt, 2) }}</td>


                            </tr>
                        @endforeach

                    </table>

                    <hr>

                    <div class="d-flex justify-content-between">

                        <h5>Total Amount</h5>
                        <h5 id="totalAmt">₹{{ number_format($total, 2) }}</h5>

                    </div>

                    <button class="btn btn-dark w-100 mt-3">
                        Save Delivery
                    </button>

                </form>

            </div>
        </div>

    </div>

    <script>
        function calcTotal() {

            let total = 0;

            document.querySelectorAll('.qty-input').forEach(el => {
                let qty = parseFloat(el.value) || 0;
                let rate = parseFloat(el.dataset.rate) || 0;

                let amt = qty * rate;
                total += amt;

                el.closest('tr').querySelector('.amt')
                    .innerHTML = "₹" + amt.toFixed(2);
            });

            document.getElementById('totalAmt')
                .innerHTML = "₹" + total.toFixed(2);
        }
    </script>

</x-app-layout>
