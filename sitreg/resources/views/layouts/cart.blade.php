<!-- Cart -->
<div class="cr-cart-overlay"></div>
<div class="cr-cart-view">
    <div class="cr-cart-inner" style="overflow: auto;">
        <div class="cr-cart-top">
            <div class="cr-cart-title">
                <h6>My Cart</h6>
                <button type="button" class="close-cart">×</button>
            </div>
            <ul class="crcart-pro-items">
                <!-- Cart items will be dynamically added here -->
            </ul>
        </div>
        <div class="cr-cart-bottom">
            <div class="cart-sub-total">
                <table class="table cart-table">
                    <tbody>
                        {{-- <tr>
                            <td class="text-left">Sub-Total :</td>
                            <td class="text-right">$<span class="sub-total">0.00</span></td>
                        </tr>
                        <tr>
                            <td class="text-left">VAT (20%) :</td>
                            <td class="text-right">$<span class="vat">0.00</span></td>
                        </tr> --}}
                        <tr>
                            <td class="text-left">Total :</td>
                            <td class="text-right primary-color">$<span class="total">0.00</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="cart_btn" style="display: none;">
                <a href="/view-cart" class="cr-button">View Cart</a>
                <a href="/cart-checkout" class="cr-btn-secondary">Checkout</a>
            </div>
        </div>
    </div>
</div>


{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="{{ asset('assets/js/cart-list.js') }}"></script>
