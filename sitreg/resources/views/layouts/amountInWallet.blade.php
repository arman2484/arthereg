    <div
        class=" w-full max-w-[70rem] mx-auto flex flex-col lg:flex-row gap-3 lg:gap-5 items-center h-full lg:h-36 mt-8 relative">
        <div class="grid place-content-center gap-3 bg-[#F4F4F4] h-40 py-3 w-full flex-grow rounded-lg">
            <img class="h-10 w-10 object-contain mx-auto" src="/assets/images/wallet.png" alt="">
            <h5 class="font-bold text-center" id="amountinwallet"></h5>
            <div>Amount in wallet</div>
        </div>
        <div class="grid place-content-center gap-3 bg-[#F4F4F4] h-40 py-3 w-full flex-grow rounded-lg">
            <img class="h-10 w-10 object-contain mx-auto" src="/assets/images/orders.png" alt="">
            <h5 class="font-bold text-center" id="totalordersdata">0</h5>
            <div>Total Orders</div>
        </div>
        {{-- <div class="grid place-content-center gap-3 bg-[#F4F4F4] h-40 py-3 w-full flex-grow rounded-lg">
            <img class="h-10 w-10 object-contain mx-auto" src="/assets/images/loyalty_points.png" alt="">
            <h5 class="font-bold text-center" id="aboveloyaltypoints"></h5>
            <div>Loyalty Points</div>
        </div> --}}
    </div>
    <div class="py-1"></div>

    {{-- scripts --}}
    <script src="{{ asset('assets/js/profileabove.js') }}"></script>
