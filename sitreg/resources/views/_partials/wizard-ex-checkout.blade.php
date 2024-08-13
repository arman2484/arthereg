<!-- Checkout Wizard -->
<div id="wizard-checkout" class="bs-stepper wizard-icons wizard-icons-example mb-5">
    <div class="bs-stepper-header m-auto border-0 py-4">
        <div class="step" data-target="#checkout-cart">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-icon">
                    <svg viewBox="0 0 58 54">
                        <use xlink:href="{{ asset('assets/svg/icons/wizard-checkout-cart.svg#wizardCart') }}"></use>
                    </svg>
                </span>
                <span class="bs-stepper-label">Cart</span>
            </button>
        </div>
        <div class="line">
            <i class="bx bx-chevron-right"></i>
        </div>
        <div class="step" data-target="#checkout-address">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-icon">
                    <svg viewBox="0 0 54 54">
                        <use
                            xlink:href="{{ asset('assets/svg/icons/wizard-checkout-address.svg#wizardCheckoutAddress') }}">
                        </use>
                    </svg>
                </span>
                <span class="bs-stepper-label">Address</span>
            </button>
        </div>
        <div class="line">
            <i class="bx bx-chevron-right"></i>
        </div>
        <div class="step" data-target="#checkout-payment">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-icon">
                    <svg viewBox="0 0 58 54">
                        <use xlink:href="{{ asset('assets/svg/icons/wizard-checkout-payment.svg#wizardPayment') }}">
                        </use>
                    </svg>
                </span>
                <span class="bs-stepper-label">Payment</span>
            </button>
        </div>
        {{-- <div class="line">
            <i class="bx bx-chevron-right"></i>
        </div>
        <div class="step" data-target="#checkout-confirmation">
            <button type="button" class="step-trigger">
                <span class="bs-stepper-icon">
                    <svg viewBox="0 0 58 54">
                        <use
                            xlink:href="{{ asset('assets/svg/icons/wizard-checkout-confirmation.svg#wizardConfirm') }}">
                        </use>
                    </svg>
                </span>
                <span class="bs-stepper-label">Confirmation</span>
            </button>
        </div> --}}
    </div>
    <div class="bs-stepper-content border-top">
        <form id="wizard-checkout-form" onSubmit="return false">

            <!-- Cart -->
            <div id="checkout-cart" class="content">
                <div class="row">
                    <!-- Cart left -->
                    <div class="col-xl-8 mb-3 mb-xl-0">

                        <!-- Shopping bag -->
                        <h5>Order Summary</h5>
                        <ul class="list-group mb-3">
                            @if ($cartItems->isEmpty())
                                <li class="list-group-item p-4">
                                    <p>No products found in your cart.</p>
                                </li>
                            @else
                                @foreach ($cartItems as $item)
                                    <li class="list-group-item p-4">
                                        <div class="d-flex gap-3">
                                            <div class="flex-shrink-0 d-flex align-items-center">
                                                @if ($item->productImages->isNotEmpty())
                                                    <img src="{{ asset('assets/images/product_images/' . $item->productImages[0]->product_image) }}"
                                                        alt="{{ $item->product->product_name }}" class="w-px-100">
                                                @else
                                                    <img src="{{ asset('assets/img/products/default.png') }}"
                                                        alt="Default Image" class="w-px-100">
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <p class="me-3">
                                                        <div class="text-body">{{ $item->product->product_name }}</div>
                                                        </p>
                                                        <div class="text-muted mb-2 d-flex flex-wrap">
                                                            <span class="me-1">Sold by:</span>
                                                            <div href="javascript:void(0)" class="me-3">
                                                                {{ $item->product->store->store_name }}
                                                            </div>
                                                        </div>
                                                        <input type="number"
                                                            class="form-control form-control-sm w-px-100 mt-2 quantity-input"
                                                            value="{{ $item->quantity }}" min="1" max="5"
                                                            data-item-id="{{ $item->id }}"
                                                            data-price="{{ $item->product->product_sale_price }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="text-md-end">
                                                            <button type="button" class="btn-close btn-pinned"
                                                                aria-label="Close" id="closethebuttton"
                                                                data-item-id="{{ $item->id }}"></button>
                                                            <div class="my-2 my-md-4 mb-md-5">
                                                                <span class="text-primary"
                                                                    data-item-price="{{ $item->product->product_sale_price }}">
                                                                    ${{ $item->product->product_sale_price }} /
                                                                </span>
                                                                <s
                                                                    class="text-muted">${{ $item->product->product_price }}</s>
                                                                <div class="item-total"
                                                                    data-item-total="{{ $item->product->product_sale_price * $item->quantity }}">
                                                                    ${{ $item->product->product_sale_price * $item->quantity }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <!-- Cart right -->
                    <div class="col-xl-4">
                        <div class="border rounded p-4 mb-3 pb-3">
                            <!-- Offer -->
                            <h6>Payment Summary</h6>
                            <hr class="mx-n4">
                            <!-- Price Details -->
                            <h6>Price Details</h6>
                            <dl class="row mb-0">
                                <dt class="col-6 fw-normal">Bag Total</dt>
                                <dd id="bag-total" class="col-6 text-end" style="padding-right: 0px;">$0.00</dd>
                                <!-- Placeholder for Bag Total -->
                            </dl>
                            <dl class="row mb-0">
                                <dt class="col-6 fw-normal">Delivery Charges</dt>
                                <dd class="col-6 text-end" style="padding-right: 0px;"><span
                                        class="badge bg-label-success ms-1">Free</span></dd>
                            </dl>
                            <hr class="mx-n4">
                            <dl class="row mb-0">
                                <dt class="col-6">Total</dt>
                                <dd id="order-total" class="col-6 fw-medium text-end mb-0" style="padding-right: 0px;">
                                    $0.00</dd>
                            </dl>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-next">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div id="checkout-address" class="content">
                <div class="row">
                    <!-- Address left -->
                    <div class="col-xl-8  col-xxl-9 mb-3 mb-xl-0">

                        <!-- Select address -->
                        <p>Select your preferable address</p>
                        <div class="row mb-3">
                            @foreach ($addresses as $address)
                                <div class="col-md mb-md-0 mb-2">
                                    <div
                                        class="form-check custom-option custom-option-basic @if ($loop->first) checked @endif">
                                        <label class="form-check-label custom-option-content"
                                            for="customRadioAddress{{ $address->id }}">
                                            <input name="customRadioAddress" class="form-check-input address-select"
                                                type="radio" value="{{ $address->id }}"
                                                id="customRadioAddress{{ $address->id }}"
                                                @if ($loop->first) checked @endif>
                                            <span class="custom-option-header mb-2">
                                                <span class="fw-medium mb-0">{{ $address->first_name }}
                                                    {{ $address->last_name }}</span>
                                                <span class="badge bg-label-primary">{{ $address->type }}</span>
                                            </span>
                                            <span class="custom-option-body">
                                                <small>{{ $address->address }},{{ $address->locality }}
                                                    {{ $address->city }},
                                                    {{ $address->state }}, {{ $address->pincode }}.<br> Mobile:
                                                    {{ $address->mobile }}</small>
                                                <span class="my-2 border-bottom d-block"></span>
                                                <span class="d-flex">
                                                    <a href="javascript:void(0)" class="text-primary delete-address"
                                                        data-address-id="{{ $address->id }}">Remove</a>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-label-primary mb-4" data-bs-toggle="modal"
                            data-bs-target="#addNewAddress">Add new address</button>
                    </div>

                    <!-- Address right -->
                    <div class="col-xl-4 col-xxl-3">
                        <div class="border rounded p-4 pb-3 mb-3">

                            <!-- Estimated Delivery -->
                            <h6>Payment Summary</h6>
                            @foreach ($cartItems as $item)
                                <ul class="list-unstyled">
                                    <li class="d-flex gap-3 align-items-center">
                                        <div class="flex-shrink-0">

                                            @if ($item->productImages->isNotEmpty())
                                                <img src="{{ asset('assets/images/product_images/' . $item->productImages[0]->product_image) }}"
                                                    alt="{{ $item->product->product_name }}" class="w-px-50">
                                            @else
                                                <img src="{{ asset('assets/img/products/default.png') }}"
                                                    alt="Default Image" class="w-px-50">
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-0">
                                            <div class="text-body">{{ $item->product->product_name }}</div>
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach


                            <hr class="mx-n4">

                            <!-- Price Details -->
                            <h6>Price Details</h6>
                            <dl class="row mb-0">
                                <dt class="col-6 fw-normal">Bag Total</dt>
                                <dd id="bag-total-2" class="col-6 text-end" style="padding-right: 0px;">$0.00</dd>
                                <!-- Placeholder for Bag Total -->
                            </dl>
                            <dl class="row mb-0">
                                <dt class="col-6 fw-normal">Delivery Charges</dt>
                                <dd class="col-6 text-end" style="padding-right: 0px;"><span
                                        class="badge bg-label-success ms-1">Free</span></dd>
                            </dl>
                            <hr class="mx-n4">
                            <dl class="row mb-0">
                                <dt class="col-6">Total</dt>
                                <dd id="order-total-2" class="col-6 fw-medium text-end mb-0"
                                    style="padding-right: 0px;">$0.00</dd>
                            </dl>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-next">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div id="checkout-payment" class="content">
                <div class="row mb-3">

                    <!-- Confirmation details -->
                    <div class="col-12">
                        <ul class="list-group list-group-horizontal-md">
                            <li class="list-group-item flex-fill p-4 text-heading" id="selectedAddressDisplay">
                                <h6 class="d-flex align-items-center gap-1"><i class="bx bx-map"></i> Shipping</h6>
                            </li>
                            <li class="list-group-item flex-fill p-4 text-heading">
                                <h6 class="d-flex align-items-center gap-1"><i class="bx bx-user"></i> Select
                                    Customer</h6>
                                <div class="col-md-12" style="padding-bottom: 20px;">
                                    <p class="fw-medium mb-3">Customer:</p>
                                    <select id="module_id" class="select2 form-select" name="module_id"
                                        data-placeholder="Select Customer">
                                        <option value="">Select Customer</option>
                                        @foreach ($customer as $value)
                                            <option value="{{ $value->id }}">
                                                {{ $value->first_name }} {{ $value->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="error">{{ $errors->first('module_id') }}</span>
                                </div>
                                <p class="fw-medium mb-3">Payment Method:</p>
                                <span class="badge bg-label-success">Cash</span>
                                <span class="badge bg-label-primary">Card</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <!-- Confirmation items -->
                    <div class="col-xl-9 mb-3 mb-xl-0">
                        <ul class="list-group">
                            @foreach ($cartItems as $item)
                                <li class="list-group-item p-4">
                                    <div class="d-flex gap-3">
                                        <div class="flex-shrink-0">
                                            @if ($item->productImages->isNotEmpty())
                                                <img src="{{ asset('assets/images/product_images/' . $item->productImages[0]->product_image) }}"
                                                    alt="{{ $item->product->product_name }}" class="w-px-100">
                                            @else
                                                <img src="{{ asset('assets/img/products/default.png') }}"
                                                    alt="Default Image" class="w-px-100">
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="text-body" style="padding-top: 25px;">
                                                        <p>{{ $item->product->product_name }}</p>
                                                    </div>
                                                    <input type="number"
                                                        class="form-control form-control-sm w-px-100 mt-2 quantity-input"
                                                        value="{{ $item->quantity }}" min="1" max="5"
                                                        data-item-id="{{ $item->id }}"
                                                        data-price="{{ $item->product->product_sale_price }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-md-end">
                                                        <div class="my-2 my-lg-4"><span
                                                                class="text-primary">${{ $item->product->product_sale_price }}/</span><s
                                                                class="text-muted">${{ $item->product->product_price }}</s>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Confirmation total -->
                    <div class="col-xl-3">
                        <div class="border rounded p-4 pb-3">
                            <!-- Price Details -->
                            <h6>Price Details</h6>
                            <dl class="row mb-0">

                                <dt class="col-6 fw-normal">Order Total</dt>
                                <dd id="bag-total-3" class="col-6 text-end" style="padding-right: 0px;">$0.00</dd>

                                <dt class="col-sm-6 fw-normal">Delivery Charges</dt>
                                <dd class="col-sm-6 text-end" style="padding-right: 0px;"><span
                                        class="badge bg-label-success ms-1">Free</span></dd>
                            </dl>
                            <hr class="mx-n4">
                            <dl class="row mb-0">
                                <dt class="col-6">Total</dt>
                                <dd id="order-total-3" class="col-6 fw-medium text-end mb-0"
                                    style="padding-right: 0px;">$0.00</dd>
                            </dl>
                        </div>
                        <div class="d-grid">
                            <button id="pay-now-btn" class="btn btn-primary" style="margin-top: 20px;">Pay
                                Now</button>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Confirmation -->
            <div id="checkout-confirmation" class="content">
                <div class="row mb-3">
                    <div class="col-12 col-lg-8 mx-auto text-center mb-3">
                        <h4 class="mt-2">Thank You! 😇</h4>
                        <p>Your order <a href="javascript:void(0)">#1536548131</a> has been placed!</p>
                        <p><span class="fw-medium"><i class="bx bx-time-five me-1"></i> Time placed:&nbsp;</span>
                            25/05/2020 13:35pm</p>
                    </div>
                    <!-- Confirmation details -->
                    <div class="col-12">
                        <ul class="list-group list-group-horizontal-md">
                            <li class="list-group-item flex-fill p-4 text-heading">
                                <h6 class="d-flex align-items-center gap-1"><i class="bx bx-map"></i> Shipping</h6>
                                <address class="mb-0">
                                    John Doe <br />
                                    4135 Parkway Street,<br />
                                    Los Angeles, CA 90017,<br />
                                    USA
                                </address>
                                <p class="mb-0 mt-3">
                                    +123456789
                                </p>
                            </li>
                            <li class="list-group-item flex-fill p-4 text-heading">
                                <h6 class="d-flex align-items-center gap-1"><i class="bx bx-credit-card"></i> Billing
                                    Address</h6>
                                <address class="mb-0">
                                    John Doe <br />
                                    4135 Parkway Street,<br />
                                    Los Angeles, CA 90017,<br />
                                    USA
                                </address>
                                <p class="mb-0 mt-3">
                                    +123456789
                                </p>
                            </li>
                            <li class="list-group-item flex-fill p-4 text-heading">
                                <h6 class="d-flex align-items-center gap-1"><i class="bx bxs-ship"></i> Shipping
                                    Method</h6>
                                <p class="fw-medium mb-3">Preferred Method:</p>
                                Standard Delivery<br />
                                (Normally 3-4 business days)
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <!-- Confirmation items -->
                    <div class="col-xl-9 mb-3 mb-xl-0">
                        <ul class="list-group">
                            <li class="list-group-item p-4">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('assets/img/products/1.png') }}" alt="google home"
                                            class="w-px-75">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <a href="javascript:void(0)" class="text-body">
                                                    <p>Google - Google Home - White</p>
                                                </a>
                                                <div class="text-muted mb-1 d-flex flex-wrap"><span
                                                        class="me-1">Sold by:</span> <a href="javascript:void(0)"
                                                        class="me-3">Apple</a> <span
                                                        class="badge bg-label-success">In Stock</span></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-md-end">
                                                    <div class="my-2 my-lg-4"><span
                                                            class="text-primary">$299/</span><s
                                                            class="text-muted">$359</s></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item p-4">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('assets/img/products/2.png') }}" alt="google home"
                                            class="w-px-75">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <a href="javascript:void(0)" class="text-body">
                                                    <p>Apple iPhone 11 (64GB, Black)</p>
                                                </a>
                                                <div class="text-muted mb-1 d-flex flex-wrap"><span
                                                        class="me-1">Sold by:</span> <a href="javascript:void(0)"
                                                        class="me-3">Apple</a> <span
                                                        class="badge bg-label-success">In Stock</span></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-md-end">
                                                    <div class="my-2 my-lg-4"><span
                                                            class="text-primary">$299/</span><s
                                                            class="text-muted">$359</s></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- Confirmation total -->
                    <div class="col-xl-3">
                        <div class="border rounded p-4 pb-3">
                            <!-- Price Details -->
                            <h6>Price Details</h6>
                            <dl class="row mb-0">

                                <dt class="col-6 fw-normal">Order Total</dt>
                                <dd class="col-6 text-end">$0.00</dd>

                                <dt class="col-sm-6 fw-normal">Delivery Charges</dt>
                                <dd class="col-sm-6 text-end"><s class="text-muted">$5.00</s> <span
                                        class="badge bg-label-success ms-1">Free</span></dd>
                            </dl>
                            <hr class="mx-n4">
                            <dl class="row mb-0">
                                <dt class="col-6">Total</dt>
                                <dd class="col-6 fw-medium text-end mb-0">$0.00</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!--/ Checkout Wizard -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate initial totals when the page loads
        updateTotals();
        const btnCloseList = document.querySelectorAll('#closethebuttton');

        btnCloseList.forEach(btnClose => {
            btnClose.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                Swal.fire({
                    title: 'Remove from Cart?',
                    text: 'Are you sure you want to remove this item from your cart?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform AJAX request to remove item
                        axios.delete(`/cart/${itemId}`)
                            .then(response => {
                                if (response.data.success) {
                                    // Remove the item from the DOM
                                    const cartItem = document.querySelector(
                                        `li[data-cart-item-id="${itemId}"]`);
                                    cartItem.remove();
                                    // Show success message
                                    Swal.fire(
                                        'Error!',
                                        'Failed to remove item from cart.',
                                        'error'
                                    );
                                } else {
                                    Swal.fire(
                                        'Removed!',
                                        'Your item has been removed from the cart.',
                                        'success'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error('Error removing item from cart:',
                                    error);
                                // Show success message
                                Swal.fire(
                                    'Removed!',
                                    'Your item has been removed from the cart.',
                                    'success'
                                ).then(() => {
                                    // Refresh the page after showing the success message
                                    location.reload();
                                });
                            });
                    }
                });
            });
        });
        // Event listener for quantity changes
        // Event listener for quantity changes
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const itemId = this.getAttribute('data-item-id');
                const newQuantity = parseInt(this.value);

                axios.patch(`/cart/${itemId}`, {
                        quantity: newQuantity
                    })
                    .then(response => {
                        if (response.data.success) {
                            // Update the item total price
                            const itemPrice = parseFloat(this.getAttribute('data-price'));
                            const itemTotal = itemPrice * newQuantity;
                            const itemTotalElement = document.querySelector(
                                `.item-total[data-item-id="${itemId}"]`);
                            if (itemTotalElement) {
                                itemTotalElement.textContent = `$${itemTotal.toFixed(2)}`;
                                itemTotalElement.setAttribute('data-item-total', itemTotal
                                    .toFixed(2));
                            }

                            // Show success message
                            Swal.fire('Quantity Updated!',
                                'Your item quantity has been updated.', 'success').then(
                                () => {
                                    // Reload the page after showing the success message
                                    location.reload();
                                });

                            // Update totals after quantity update
                            updateTotals();
                        } else {
                            Swal.fire('Error!', 'Failed to update quantity.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating quantity:', error);
                        Swal.fire('Error!', 'Failed to update quantity.', 'error');
                    });
            });
        });
    });

    // Function to update totals
    function updateTotals() {
        let bagTotal = 0;
        document.querySelectorAll('.item-total').forEach(itemTotal => {
            bagTotal += parseFloat(itemTotal.getAttribute('data-item-total'));
        });
        document.getElementById('bag-total').textContent = `$${bagTotal.toFixed(2)}`;
        document.getElementById('order-total').textContent = `$${bagTotal.toFixed(2)}`;
        document.getElementById('bag-total-2').textContent = `$${bagTotal.toFixed(2)}`;
        document.getElementById('order-total-2').textContent = `$${bagTotal.toFixed(2)}`;
        document.getElementById('bag-total-3').textContent = `$${bagTotal.toFixed(2)}`;
        document.getElementById('order-total-3').textContent = `$${bagTotal.toFixed(2)}`;
    }
</script>



<script>
    $(document).ready(function() {
        $('.address-select').change(function() {
            let addressId = $(this).val();
            Swal.fire({
                title: 'Select Address?',
                text: 'Are you sure you want to select this address for your order?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, select it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('updateCartAddress') }}',
                        method: 'POST',
                        data: {
                            address_id: addressId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Address Selected!',
                                    text: 'Address has been successfully selected for your order.',
                                    showConfirmButton: true,
                                    timer: null,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong! Please try again.',
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong! Please try again.',
                            });
                        }
                    });
                } else {
                    // Revert the address selection if not confirmed
                    $(this).val($(this).data('previousValue'));
                }
            });
        }).each(function() {
            // Save the initial value of the select element
            $(this).data('previousValue', $(this).val());
        });
    });
</script>




<script>
    // Wait for the document to be fully loaded
    document.addEventListener("DOMContentLoaded", function() {
        // Add event listener to all delete address links
        const deleteButtons = document.querySelectorAll('.delete-address');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const addressId = this.getAttribute('data-address-id');

                // Show SweetAlert confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, proceed with deletion
                        fetch(`/delete-address/${addressId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                        }).then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        }).then(data => {
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message,
                                icon: 'success'
                            }).then(() => {
                                // Optionally reload the page to reflect changes
                                location.reload();
                            });
                            // Optionally, update the UI (remove the deleted address)
                            // Replace this with your logic to update the UI
                        }).catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete address.',
                                icon: 'error'
                            });
                        });
                    }
                });
            });
        });
    });
</script>




<script>
    function updateSelectedAddress(addressId) {
        // Fetch details of the selected address from your addresses array
        var selectedAddress = {!! json_encode($addresses->toArray()) !!}.find(function(address) {
            return address.id == addressId;
        });

        // Update the display area with selected address details
        $('#selectedAddressDisplay').html(`
  <h6 class="d-flex align-items-center gap-1"><i class="bx bx-map"></i> Shipping</h6>
            <p>${selectedAddress.first_name} ${selectedAddress.last_name}</p>
            <p>${selectedAddress.address}, ${selectedAddress.locality}, ${selectedAddress.city}, ${selectedAddress.state}, ${selectedAddress.pincode}</p>
            <p>Mobile: ${selectedAddress.mobile}</p>
        `);
    }

    $(document).ready(function() {
        // Fetch the initially selected address ID
        var initiallySelectedAddressId = $('input[name="customRadioAddress"]:checked').val();

        // Call updateSelectedAddress on page load with the initially selected ID
        updateSelectedAddress(initiallySelectedAddressId);

        // Listen for change in radio buttons
        $('input[name="customRadioAddress"]').change(function() {
            // Get the selected address ID
            var selectedAddressId = $(this).val();

            // Call updateSelectedAddress function with the selected ID
            updateSelectedAddress(selectedAddressId);
        });
    });
</script>
{{--

<script>
    $(document).ready(function() {
        $('#pay-now-btn').on('click', function() {
            const selectedCustomerId = $('#module_id').val();
            const selectedAddressId = $('input[name="customRadioAddress"]:checked').val();
            const cartItems = []; // Collect your cart items as an array

            // Example of collecting cart items
            $('.quantity-input').each(function() {
                const item = {
                    id: $(this).data('item-id'),
                    product_id: $(this).data('product-id'),
                    quantity: $(this).val(),
                    price: $(this).data('price')
                };
                cartItems.push(item);
            });

            const data = {
                module_id: selectedCustomerId,
                address_id: selectedAddressId,
                cartItems: cartItems,
                total_amount: $('#order-total-3').text().replace('$', '') // Extracting total amount
            };

            $.ajax({
                url: '/checkout/pay',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });
    });
</script> --}}
<script>
    $(document).ready(function() {
        $('#pay-now-btn').on('click', function() {
            const selectedCustomerId = $('#module_id').val(); // Fetch selected customer's user_id
            const paymentMode = 'COD'; // Example: Change as per your form input

            if (!selectedCustomerId) {
                Swal.fire({
                    text: 'Please Select a Customer.',
                    icon: 'info',
                    showCloseButton: true, // Adding close button
                    confirmButtonText: 'OK'
                })
                return;
            }

            const data = {
                user_id: selectedCustomerId,
                payment_mode: paymentMode,
            };

            $.ajax({
                url: '/checkout/pay',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        text: 'Order Placed Successfully',
                        icon: 'success',
                        showCloseButton: true,
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = '/app/ecommerce/order/list';
                    });
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                    // Handle errors or provide user feedback
                }
            });
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#module_id').on('change', function() {
            const selectedCustomerId = $(this).val(); // Fetch selected customer's user_id

            if (!selectedCustomerId) {
                return;
            }

            // AJAX request to update cart_items user_id for products with admin_cart as 1
            const data = {
                user_id: selectedCustomerId,
            };

            $.ajax({
                url: '/update-cart-user-id', // Replace with your endpoint to update cart_items
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Optionally handle success response
                    console.log('User ID updated in cart_items successfully');
                },
                error: function(xhr) {
                    // Handle errors or provide user feedback
                    console.error('Error updating user ID in cart_items:', xhr.responseJSON
                        .message);
                }
            });
        });
    });
</script>
