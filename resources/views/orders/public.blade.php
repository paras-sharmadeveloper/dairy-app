<!DOCTYPE html>
<html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .qty-btn {
            width: 35px;
            height: 35px;
            border-radius: 8px;
        }

        .qty-input {
            text-align: center;
        }
    </style>

</head>

<body class="bg-light">

    <div class="container py-3">

        <h5 class="mb-1">{{ $shop->name }}</h5>
        <small class="text-muted">
            {{ now()->format('d M Y h:i A') }}
        </small>

        @if (session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" id="orderForm">
            @csrf

            <div class="mt-3">

                @foreach ($products as $p)
                    <div class="card mb-2">
                        <div class="card-body p-2">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <b>{{ $p->name }}</b>
                                    <br>
                                    <small class="text-muted">
                                        ₹{{ $p->current_rate }} / {{ $p->unit }}
                                    </small>
                                </div>

                                <div class="d-flex align-items-center">

                                    <button type="button" class="btn btn-outline-dark qty-btn"
                                        onclick="changeQty({{ $p->id }},-1)">-</button>

                                    <input type="number" step="0.1" min="0" value="0"
                                        data-rate="{{ $p->current_rate }}" name="qty[{{ $p->id }}]"
                                        id="qty{{ $p->id }}" class="form-control mx-2 qty-input"
                                        style="width:70px" oninput="calcTotal()">

                                    <button type="button" class="btn btn-outline-dark qty-btn"
                                        onclick="changeQty({{ $p->id }},1)">+</button>

                                </div>

                            </div>

                        </div>
                    </div>
                @endforeach

            </div>

            <hr>

            <div class="d-flex justify-content-between">

                <h5>Total Payable</h5>
                <h5 id="totalAmt">₹0</h5>

            </div>

            <button type="button" onclick="confirmOrder()" class="btn btn-dark w-100 mt-3">

                Submit Order

            </button>

        </form>

    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeQty(id, delta) {
            let input = document.getElementById('qty' + id);
            let val = parseFloat(input.value) || 0;
            val += delta;
            if (val < 0) val = 0;
            input.value = val.toFixed(1);
            calcTotal();
        }

        function calcTotal() {
            let total = 0;

            document.querySelectorAll('.qty-input').forEach(el => {
                let qty = parseFloat(el.value) || 0;
                let rate = parseFloat(el.dataset.rate) || 0;
                total += qty * rate;
            });

            document.getElementById('totalAmt').innerHTML = "₹" + total.toFixed(2);
        }

        function confirmOrder() {

            let total = document.getElementById('totalAmt').innerText;

            if (total == "₹0") {
                alert("Please add at least one item");
                return;
            }

            if (confirm("Confirm order?\nTotal: " + total)) {
                document.getElementById('orderForm').submit();
            }
        }

        function confirmOrder() {

            let rows = "";
            let total = 0;
            let hasItem = false;

            document.querySelectorAll('.qty-input').forEach(el => {
                let qty = parseFloat(el.value) || 0;

                if (qty > 0) {

                    hasItem = true;

                    let name = el.closest('.card-body').querySelector('b').innerText;
                    let rate = parseFloat(el.dataset.rate) || 0;
                    let amt = qty * rate;
                    total += amt;

                    rows += `
            <tr>
                <td>${name}</td>
                <td>${qty}</td>
                <td>${rate}</td>
                <td>${amt.toFixed(2)}</td>
            </tr>`;
                }
            });

            if (!hasItem) {
                alert("Please add items");
                return;
            }

            let html = `
    <div class="text-center mb-3">
        <h5>{{ $shop->name }}</h5>
        <small>${new Date().toLocaleString()}</small>
    </div>

    <table class="table table-bordered">
    <tr>
        <th>Item</th>
        <th>Qty</th>
        <th>Rate</th>
        <th>Total</th>
    </tr>
    ${rows}
    </table>

    <div class="text-end">
        <h5>Total: ₹${total.toFixed(2)}</h5>
    </div>
    `;

            document.getElementById('invoicePreview').innerHTML = html;

            new bootstrap.Modal(document.getElementById('invoiceModal')).show();
        }

        function submitOrder() {
            document.getElementById('orderForm').submit();
        }
    </script>


    <div class="modal fade" id="invoiceModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Order Confirmation</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div id="invoicePreview"></div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Edit
                    </button>

                    <button class="btn btn-dark" onclick="submitOrder()">
                        Confirm Order
                    </button>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
