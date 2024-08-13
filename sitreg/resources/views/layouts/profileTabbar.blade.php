<section class="section-register padding-tb-50 px-3">
    <div class="w-full  max-w-[70rem] mx-auto hidden lg:flex flex-wrap gap-3  lg:justify-between items-center">
        <a href="/profile" id="profile" class=" font-medium ">
            Profile Settings
        </a>
        <a href="orders" id="orders" class=" font-medium">
            My Orders
        </a>
        <a href="wallet" id="wallet" class=" font-medium">
            Wallet
        </a>
        <a href="coupons" id="coupons" class=" font-medium">
            Coupons
        </a>
        {{-- <a href="loyalty-points" id="loyaltyPoints" class=" font-medium">
            Loyalty Points
        </a> --}}
        <a href="referralCode" id="referralCode" class=" font-medium">
            Referral Code
        </a>

    </div>
    <div class="lg:hidden">
        <a class="flex justify-between" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
            aria-controls="offcanvasExample">

            <div class="font-bold text-lg" id="current_page"></div>
            <i class="ri-menu-line text-xl"></i>
        </a>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel"></h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body flex flex-col gap-4">
                <a href="/profile" id="profile" class=" font-medium px-3 py-2 ">
                    Profile Settings
                </a>
                <a href="orders" id="orders" class=" font-medium px-3 py-2">
                    My Orders
                </a>
                <a href="wallet" id="wallet" class=" font-medium px-3 py-2">
                    Wallet
                </a>
                <a href="coupons" id="coupons" class=" font-medium px-3 py-2">
                    Coupons
                </a>
                {{-- <a href="loyalty-points" id="loyaltyPoints" class=" font-medium px-3 py-2">
                    Loyalty Points
                </a> --}}
                <a href="referralCode" id="referralCode" class=" font-medium px-3 py-2">
                    Referral Code
                </a>
            </div>
        </div>
    </div>
</section>

<script>
    let current_page = document.getElementById("current_page");
    let profiles = document.querySelectorAll("#profile");
    let orders = document.querySelectorAll("#orders");
    let wallet = document.querySelectorAll("#wallet");
    let coupons = document.querySelectorAll("#coupons");
    let loyaltyPoints = document.querySelectorAll("#loyaltyPoints");
    let referralCode = document.querySelectorAll("#referralCode");

    switch (window.location.pathname) {
        case "/profile":
            profiles.forEach(profile => {
                profile.classList.add("bg-[#00438F]", "cursor-pointer", "rounded-lg", "px-3", "py-2",
                    "text-white");
            });
            current_page.innerText = "Profile Settings"
            break;
        case "/orders":
            orders.forEach(order => {
                order.classList.add("bg-[#00438F]", "cursor-pointer", "rounded-lg", "px-3", "py-2",
                    "text-white");
            });
            current_page.innerText = "My Orders"
            break;
        case "/wallet":
            wallet.forEach(walletItem => {
                walletItem.classList.add("bg-[#00438F]", "cursor-pointer", "rounded-lg", "px-3", "py-2",
                    "text-white");
            });
            current_page.innerText = "Wallet"
            break;
        case "/coupons":
            coupons.forEach(coupon => {
                coupon.classList.add("bg-[#00438F]", "cursor-pointer", "rounded-lg", "px-3", "py-2",
                    "text-white");
            });
            current_page.innerText = "Coupons"
            break;
        case "/loyalty-points":
            loyaltyPoints.forEach(loyaltyPoint => {
                loyaltyPoint.classList.add("bg-[#00438F]", "cursor-pointer", "rounded-lg", "px-3", "py-2",
                    "text-white");
            });
            current_page.innerText = "Loyalty Points"
            break;
        case "/referralCode":
            referralCode.forEach(code => {
                code.classList.add("bg-[#00438F]", "cursor-pointer", "rounded-lg", "px-3", "py-2",
                    "text-white");
            });
            current_page.innerText = "Referral Code"
            break;
        default:
            break;
    }
</script>
