<x-app-layout>

    <div class="container py-4">

        <h4 class="mb-3">Vendor Order Entry</h4>

        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('orders.vendorStore') }}" id="orderForm">
                    @csrf

                    {{-- SHOP SELECT --}}
                    <div class="mb-3">
                        <label>Select Shop</label>
                        <select name="shop_id" id="shopSelect" class="form-select" required>
                            <option value="">Choose shop</option>
                            @foreach ($shops as $shop)
                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr>

                    <h5 class="mb-3">Products</h5>

                    @foreach ($products as $p)
                        <div class="card mb-2 p-2">


                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <b>{{ $p->name }}</b>
                                    <br>
                                    <small class="text-muted">
                                        ₹{{ $p->current_rate }} / {{ $p->unit }}
                                    </small>
                                </div>

                                <div class="d-flex align-items-center">

                                    <button type="button" class="btn btn-outline-dark"
                                        onclick="changeQty({{ $p->id }},-1)">-</button>

                                    <input type="number" step="0.1" min="0" value="0"
                                        data-rate="{{ $p->current_rate }}" data-name="{{ $p->name }}"
                                        name="qty[{{ $p->id }}]" id="qty{{ $p->id }}"
                                        class="form-control mx-2 qty-input" style="width:80px" oninput="calcTotal()">

                                    <button type="button" class="btn btn-outline-dark"
                                        onclick="changeQty({{ $p->id }},1)">+</button>

                                </div>

                            </div>
                        </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between">
                        <h5>Total</h5>
                        <h5 id="totalAmt">₹0</h5>
                    </div>

                    <button type="button" onclick="showInvoice()" class="btn btn-dark w-100 mt-3">

                        Submit Order

                    </button>

                </form>

            </div>
        </div>

    </div>

    {{-- INVOICE POPUP --}}
    <div id="invoicePopup"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
background:rgba(0,0,0,0.6); z-index:9999;">

        <div style="background:#fff; max-width:650px; margin:40px auto; padding:20px; border-radius:10px">

            <div id="invoicePreview"></div>

            <div class="text-end mt-3">
                <button class="btn btn-secondary" onclick="closePopup()">Edit</button>
                <button class="btn btn-dark" onclick="submitOrder()">Confirm Order</button>
            </div>

        </div>
    </div>

    {{-- JS --}}
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

        function showInvoice() {

            let rows = "";
            let total = 0;
            let hasItem = false;
            let shopSelect=document.getElementById('shopSelect');
            if(!shopSelect.value){
                alert("Please select shop");
                return;
            }
            let shopName=shopSelect.options[shopSelect.selectedIndex].text;
            document.querySelectorAll('.qty-input').forEach(el => {
                let qty = parseFloat(el.value) || 0;

                if (qty > 0) {

                    hasItem = true;

                    let name = el.dataset.name;
                    let rate = parseFloat(el.dataset.rate);
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
        <h5>Vendor Order Preview</h5>
         <h5><b>Shop: ${shopName}</b></h5>
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
            document.getElementById('invoicePopup').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('invoicePopup').style.display = 'none';
        }

        function submitOrder() {
            document.getElementById('orderForm').submit();
        }
    </script>

</x-app-layout>
