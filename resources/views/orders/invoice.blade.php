<x-app-layout>

    <div class="container py-4">

        <div class="card">
            <div class="card-body">

                <h4>{{ $order->shop->name }}</h4>
                <small>{{ now()->format('d M Y h:i A') }}</small>

                <hr>

                <table class="table">

                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Total</th>
                    </tr>

                    @php $total=0; @endphp

                    @foreach ($order->items as $item)
                        @php
                            $qty = $item->delivered_qty ?? $item->ordered_qty;
                            $amt = $qty * $item->rate_snapshot;
                            $total += $amt;
                        @endphp

                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $qty }}</td>
                            <td>{{ $item->rate_snapshot }}</td>
                            <td>₹{{ number_format($amt, 2) }}</td>
                        </tr>
                    @endforeach

                </table>

                <hr>

                <h4 class="text-end">Total: ₹{{ number_format($total, 2) }}</h4>

                @php
                    $msg =
                        'Invoice for ' .
                        $order->shop->name .
                        "\n" .
                        'Total: ₹' .
                        number_format($total, 2) .
                        "\n" .
                        "View Invoice:\n" .
                        route('orders.publicInvoice',$order->public_token);

                @endphp

                @php
                    $upi = $order->user->upi_id ?? null;
                @endphp

                @if ($publicEnable && $upi)
                    <hr>

                    <h5 class="mb-3">Pay Now</h5>

                    @php
                        $amount = number_format($total, 2, '.', '');
                        $upiLink =
                            'upi://pay?pa=' .
                            $upi .
                            '&pn=' .
                            urlencode($order->user->name) .
                            '&am=' .
                            $amount .
                            '&cu=INR' .
                            '&tn=' .
                            urlencode('Invoice for ' . $order->shop->name);

                    @endphp

                    <a href="{{ $upiLink }}" class="btn btn-success w-100">
                        Pay via UPI
                    </a>

                @else
                 <a target="_blank" href="https://wa.me/{{ $order->shop->mobile }}?text={{ urlencode($msg) }}"
                    class="btn btn-success w-100 mt-3">

                    Send on WhatsApp

                </a>
                @endif



            </div>
        </div>

    </div>

</x-app-layout>
