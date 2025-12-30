<x-guest-layout>
    <div class="container my-3">
        <!-- Header -->
        <div class="checkout-header text-center">
            <h1><i class="fas fa-shopping-cart me-3"></i>Checkout</h1>
        </div>
        @if ($myCart && $myCart->items->count() > 0)
            <div class="row">
                <!-- Left Column - Cart Items -->
                <div class="col-lg-8">
                    <!-- Cart Items -->
                    <div class="checkout-card">
                        <h3 class="section-title">
                            <i class="fas fa-list"></i>
                            Your Cart (<span id="itemCount">{{ $myCart->items->count() }}</span> items)
                        </h3>

                        <div id="cartItems">
                            <!-- Cart Item 1 -->
                            @foreach ($myCart->items as $cart)
                                @if ($cart->model == 'App\Models\Course')
                                    <div class="cart-item" data-price="{{ $cart->price }}">
                                        <div class="item-thumbnail">
                                            <i class="fas fa-calculator"></i>
                                        </div>
                                        <div class="item-details">
                                            <h5 class="item-title">
                                                {{ $cart->item_details->title }}
                                            </h5>
                                            <p class="item-educator">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $cart->item_details->educator->full_name }}
                                            </p>
                                            <div class="item-meta">
                                                <span><i class="fas fa-clock"></i> {{ $cart->item_details->duration }}
                                                    hours</span>
                                                @if ($cart->item_details->lessons->count())
                                                    <span><i class="fas fa-play-circle"></i>
                                                        {{ $cart->item_details->lessons->count() }} lessons</span>
                                                @endif

                                                <span><i class="fas fa-star text-warning"></i>
                                                    {{ $cart->item_details->reviews->avg('rating') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="item-price">
                                            <div class="current-price">${{ $cart->price }}</div>
                                            {{-- <div class="original-price">$179.99</div> --}}
                                        </div>
                                        <button class="remove-btn" onclick="removeItem('{{ $cart->id }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                                @if ($cart->model == 'App\Models\Lesson')
                                    <div class="cart-item" data-price="{{ $cart->price }}">
                                        <div class="item-thumbnail">
                                            <i class="fas fa-calculator"></i>
                                        </div>
                                        <div class="item-details">
                                            <h5 class="item-title">
                                                {{ $cart->item_details->title }}
                                            </h5>
                                            <p class="item-educator">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $cart->item_details->course->educator->full_name }}
                                            </p>
                                            <div class="item-meta">

                                                <span><i class="fas fa-book"></i>
                                                    <a
                                                        href="{{ route('web.course.show', ['slug'=>$cart->item_details->course->slug  , 'id' => $cart->item_details->course->id] ) }}">
                                                        {{ $cart->item_details->course->title }}
                                                    </a>
                                                </span>
                                                {{-- <span><i class="fas fa-star text-warning"></i>
                                                    {{ $cart->item_details->reviews->avg('rating') }}
                                                </span> --}}

                                            </div>
                                        </div>
                                        <div class="item-price">
                                            <div class="current-price">${{ $cart->price }}</div>
                                            {{-- <div class="original-price">$179.99</div> --}}
                                        </div>
                                        <button class="remove-btn" onclick="removeItem('{{ $cart->id }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                            @endforeach


                        </div>

                        <!-- Empty Cart State -->
                        <div id="emptyCart" style="display: none;">
                            <div class="empty-cart">
                                <i class="fas fa-shopping-cart"></i>
                                <h3>Your cart is empty</h3>
                                <p class="text-muted mb-4">Add some courses to get started!</p>
                                <button class="browse-btn" onclick="browseCourses()">
                                    <i class="fas fa-search me-2"></i>Browse Courses
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-card" id="paymentSection">
                        <h3 class="section-title">
                            <i class="fas fa-credit-card"></i>
                            Choose Payment Method
                        </h3>

                        <div class="payment-methods">
                            <!-- Stripe Option -->
                            <div class="payment-option selected" onclick="selectPayment('stripe')" id="stripeOption">
                                <div class="payment-logo stripe-logo">
                                    <i class="fab fa-cc-stripe"></i>
                                </div>
                                <div class="payment-name">Credit / Debit Card</div>
                                <p class="payment-desc">Secure payment via Stripe</p>
                            </div>

                            <!-- PayPal Option -->
                            <div class="payment-option" onclick="selectPayment('paypal')" id="paypalOption">
                                <div class="payment-logo paypal-logo">
                                    <i class="fab fa-paypal"></i>
                                </div>
                                <div class="payment-name">PayPal</div>
                                <p class="payment-desc">Fast & secure checkout</p>
                            </div>
                        </div>

                        <div class="security-note">
                            <i class="fas fa-shield-alt"></i>
                            Your payment information is encrypted and secure
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="col-lg-4">
                    <div class="checkout-card" style="position: sticky; top: 20px;">
                        <h3 class="section-title">
                            <i class="fas fa-receipt"></i>
                            Order Summary
                        </h3>

                        <!-- Promo Code -->
                        <div class="promo-section">
                            <input type="text" class="promo-input" placeholder="Enter promo code" id="promoInput">
                            <button class="promo-btn" onclick="applyPromo()">Apply</button>
                        </div>

                        <div id="promoSuccess" style="display: none;">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Promo code applied successfully!
                            </div>
                        </div>
                        @php
                            $totalPrice = cartTotal();
                            $totalTaxAmount = taxAmountOfPrice($totalPrice, env('APP_TAX', 5));
                            $finalAmount = $totalPrice + $totalTaxAmount;
                            $finalAmount = number_format($finalAmount, 2, '.', '');
                        @endphp
                        <!-- Order Summary -->
                        <div class="order-summary">
                            <div class="summary-row">
                                <span>Total Price</span>
                                <span id="originalTotal">$ {{ $totalPrice }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Tax</span>
                                <span id="taxAmount">$ {{ $totalTaxAmount }}</span>
                            </div>
                            {{-- <div class="summary-row">
                            <span>
                                Discount
                                <span class="discount-badge">50% OFF</span>
                            </span>
                            <span style="color: var(--accent-pink);" id="discountAmount">-$240.00</span>
                        </div>
                        <div class="summary-row" id="promoRow" style="display: none;">
                            <span>Promo Code</span>
                            <span style="color: var(--accent-pink);" id="promoAmount">-$20.00</span>
                        </div> --}}
                            <div class="summary-row total">
                                <span>Total</span>
                                <span class="amount" id="finalTotal">$ {{ $finalAmount }}</span>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button type="button" class="checkout-btn" onclick="proceedCheckout()" id="checkoutBtn">
                            <i class="fas fa-lock me-2"></i>
                            Complete Purchase
                        </button>

                        <a href="{{ route('web.courses') }}" class="btn btn-outline-primary mt-3 w-100"
                            id="continueShopping" class="href">
                            <i class="fas fa-arrow-left me-2"></i>
                            Continue Shopping
                        </a>



                        <!-- What You Get -->
                        <div class="mt-4">
                            <h6 style="font-weight: 700; margin-bottom: 15px;">What you'll get:</h6>
                            <ul style="list-style: none; padding: 0;">
                                <li style="padding: 8px 0; color: #666;">
                                    <i class="fas fa-check-circle me-2" style="color: var(--primary-cyan);"></i>
                                    Lifetime access to all courses
                                </li>
                                <li style="padding: 8px 0; color: #666;">
                                    <i class="fas fa-check-circle me-2" style="color: var(--primary-cyan);"></i>
                                    Downloadable resources & materials
                                </li>
                                {{-- <li style="padding: 8px 0; color: #666;">
                                <i class="fas fa-check-circle me-2" style="color: var(--primary-cyan);"></i>
                                Certificate of completion
                            </li> --}}
                                <li style="padding: 8px 0; color: #666;">
                                    <i class="fas fa-check-circle me-2" style="color: var(--primary-cyan);"></i>
                                    Access on mobile and desktop
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted mb-4">Add some courses to get started!</p>
                <a class="btn btn-lg browse-btn" href="{{ route('web.courses') }}">
                    <i class="fas fa-search"></i>Browse Courses
                </a>
            </div>
        @endif
    </div>


    <form id="stripeCheckoutForm" method="POST" action="{{ url('/stripe/checkout') }}" style="display:none;">
        @csrf
        <input type="hidden" name="order_user_id" value="{{ $myCart->user_id ?? '' }}">
        <input type="hidden" name="order_id" value="{{ $myCart->id ?? '' }}">
    </form>



    <!-- Login Modal -->
    <div class="modal fade" id="cartLoginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Login to Continue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="loginError" class="alert alert-danger d-none"></div>

                    <form id="cartLoginForm">
                        @csrf

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>




    @push('scripts')
        <script>
            let selectedPayment = "stripe"; // Default

            let loginStatus = {{ Auth::check() ? 1 : 0 }};

            function selectPayment(method) {
                document.getElementById("checkoutBtn").style.display = "block";
                selectedPayment = method;

                document.getElementById("stripeOption").classList.remove("selected");
                document.getElementById("paypalOption").classList.remove("selected");

                if (method === "stripe") {
                    document.getElementById("stripeOption").classList.add("selected");
                } else {
                    document.getElementById("paypalOption").classList.add("selected");
                }
            }

            function proceedCheckout() {
                if (!loginStatus) {
                    window.location.href = "{{ route('login', ['redirect_url' => route('web.cart')]) }}";
                } else {

                    if (selectedPayment === "stripe") {
                        // Submit hidden form for Stripe
                        document.getElementById("stripeCheckoutForm").submit();
                    } else if (selectedPayment === "paypal") {
                        // You can define PayPal logic later
                        startPayPalPayment();
                    }
                }
            }



            $("#cartLoginForm").on("submit", function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('cart.login') }}", // <-- define this route
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        $("#loginError").addClass("d-none");

                        // Close modal
                        var modal = bootstrap.Modal.getInstance(document.getElementById('cartLoginModal'));
                        modal.hide();

                        // Show success message (optional)
                        Swal.fire({
                            icon: "success",
                            title: "Logged in!",
                            text: "You can now continue checkout",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Optional: refresh the page (so that cart changes reflect)
                        location.reload();
                    },

                    error: function(xhr) {
                        // Show error message
                        $("#loginError").removeClass("d-none").text(xhr.responseJSON.message);
                    }
                });
            });


            // Remove item from cart
            function removeItem(item_id) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('order.removeOrderItem') }}";
                form.style.display = 'none';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'item_id';
                input.value = item_id;

                const csrfToken = "{{ csrf_token() }}";
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;

                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
            }

            // Update cart totals
            function updateCart() {
                const items = document.querySelectorAll('.cart-item');
                const itemCount = items.length;

                document.getElementById('itemCount').textContent = itemCount;

                if (itemCount === 0) {
                    document.getElementById('cartItems').style.display = 'none';
                    document.getElementById('emptyCart').style.display = 'block';
                    document.getElementById('paymentSection').style.display = 'none';
                    document.getElementById('checkoutBtn').disabled = true;
                    return;
                }

                let total = 0;
                let originalTotal = 0;

                items.forEach(item => {
                    const price = parseFloat(item.dataset.price);
                    total += price;
                    originalTotal += price * 2; // Assuming 50% discount
                });

                document.getElementById('originalTotal').textContent = '$' + originalTotal.toFixed(2);
                document.getElementById('discountAmount').textContent = '-$' + (originalTotal - total).toFixed(2);

                // Check if promo is applied
                const promoRow = document.getElementById('promoRow');
                let finalTotal = total;

                if (promoRow.style.display !== 'none') {
                    finalTotal -= 20; // $20 promo discount
                }

                document.getElementById('finalTotal').textContent = '$' + finalTotal.toFixed(2);
            }

            // Apply promo code
            function applyPromo() {
                const promoInput = document.getElementById('promoInput');
                const promoCode = promoInput.value.trim().toUpperCase();

                if (promoCode === 'SAVE20') {
                    document.getElementById('promoSuccess').style.display = 'block';
                    document.getElementById('promoRow').style.display = 'flex';

                    setTimeout(() => {
                        document.getElementById('promoSuccess').style.display = 'none';
                    }, 3000);

                    updateCart();
                } else if (promoCode) {
                    alert('Invalid promo code. Try "SAVE20" for $20 off!');
                }
            }



            // Browse courses
            function browseCourses() {
                alert('Redirecting to courses page...');
                // window.location.href = '/courses';
            }

            // Initialize
            // updateCart();
        </script>



        <script>
            $("#cartLoginForm").on("submit", function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('cart.login') }}", // <-- define this route
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        $("#loginError").addClass("d-none");

                        // Close modal
                        var modal = bootstrap.Modal.getInstance(document.getElementById('cartLoginModal'));
                        modal.hide();

                        // Show success message (optional)
                        Swal.fire({
                            icon: "success",
                            title: "Logged in!",
                            text: "You can now continue checkout",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Optional: refresh the page (so that cart changes reflect)
                        location.reload();
                    },

                    error: function(xhr) {
                        // Show error message
                        $("#loginError").removeClass("d-none").text(xhr.responseJSON.message);
                    }
                });
            });
        </script>
        @if (env('PAYPAL_MODE') == 'live')
            <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency=USD"></script>
        @else
            <script src="https://www.sandbox.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency=USD"></script>
        @endif
        @if ($myCart)
            <script>
                function startPayPalPayment() {
                    document.getElementById("checkoutBtn").style.display = "none";

                    const container = document.createElement("div");
                    container.id = "paypal-button-container";
                    document.getElementById("paymentSection").appendChild(container);

                    paypal.Buttons({
                        createOrder: function() {
                            return fetch("{{ url('/paypal/create') }}", {
                                    method: "POST",
                                    body: JSON.stringify({
                                        note: "Order ID {{ $myCart->id }}",
                                        status: "capture",
                                        order_id: "{{ $myCart->id }}"
                                    }),
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (!data.success) {
                                        throw new Error(data.message);
                                        Swal.fire({
                                            title: "Error",
                                            text: data.message +
                                                " Unable to process the payment. Please try again.",
                                            icon: "error",
                                            confirmButtonColor: "#d33"
                                        })
                                    }
                                    if (!data.id) {
                                        throw new Error("Order ID not returned");
                                        Swal.fire({
                                            title: "Error",
                                            text: "Unable to process the payment. Please try again.",
                                            icon: "error",
                                            confirmButtonColor: "#d33"
                                        });
                                    }
                                    return data.id; //
                                });
                        },

                        onApprove: function(data) {
                            return fetch("{{ url('/paypal/capture') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    },
                                    body: JSON.stringify({
                                        orderID: data.orderID
                                    })
                                }).then(res => res.json())
                                .then(details => {
                                    console.log("Payment successful", details);
                                });
                        }
                    }).render("#paypal-button-container");

                    window.scrollTo({
                        top: document.getElementById("paymentSection").offsetTop,
                        behavior: "smooth"
                    });
                }
            </script>
        @endif


    @endpush


    @push('styles')
        <style>
            .checkout-header {
                background: linear-gradient(135deg, var(--primary-cyan) 0%, var(--dark-cyan) 100%);
                color: white;
                padding: 30px 0;
                margin-bottom: 40px;
                border-radius: 15px;
                box-shadow: 0 5px 20px rgba(0, 131, 143, 0.3);
            }

            .checkout-header h1 {
                font-weight: 700;
                margin: 0;
            }

            .checkout-card {
                background: white;
                border-radius: 15px;
                padding: 30px;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
                margin-bottom: 25px;
            }

            .section-title {
                font-size: 1.4rem;
                font-weight: 700;
                color: #333;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .section-title i {
                color: var(--primary-cyan);
            }

            .cart-item {
                display: flex;
                gap: 20px;
                padding: 20px;
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                margin-bottom: 15px;
                transition: all 0.3s;
                position: relative;
            }

            .cart-item:hover {
                border-color: var(--light-cyan);
                box-shadow: 0 5px 15px rgba(0, 131, 143, 0.1);
            }

            .item-thumbnail {
                width: 120px;
                height: 90px;
                border-radius: 10px;
                background: linear-gradient(135deg, var(--light-cyan) 0%, var(--primary-cyan) 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .item-thumbnail i {
                font-size: 2.5rem;
                color: rgba(255, 255, 255, 0.5);
            }

            .item-details {
                flex: 1;
            }

            .item-title {
                font-weight: 700;
                color: #333;
                margin-bottom: 5px;
                font-size: 1.1rem;
            }

            .item-educator {
                color: #666;
                font-size: 0.9rem;
                margin-bottom: 10px;
            }

            .item-meta {
                display: flex;
                gap: 15px;
                font-size: 0.85rem;
                color: #999;
            }

            .item-price {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                justify-content: center;
            }

            .current-price {
                font-size: 1.8rem;
                font-weight: 700;
                color: var(--primary-cyan);
            }

            .original-price {
                font-size: 1rem;
                color: #999;
                text-decoration: line-through;
            }

            .remove-btn {
                position: absolute;
                top: 15px;
                right: 15px;
                width: 30px;
                height: 30px;
                border: none;
                background: #fff;
                color: #999;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .remove-btn:hover {
                background: var(--accent-pink);
                color: white;
                transform: rotate(90deg);
            }

            .payment-methods {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-bottom: 25px;
            }

            .payment-option {
                border: 3px solid #e0e0e0;
                border-radius: 15px;
                padding: 30px 20px;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s;
                position: relative;
                background: white;
            }

            .payment-option:hover {
                border-color: var(--light-cyan);
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(0, 131, 143, 0.1);
            }

            .payment-option.selected {
                border-color: var(--primary-cyan);
                background: rgba(0, 131, 143, 0.05);
            }

            .payment-option.selected::after {
                content: '\f00c';
                font-family: 'Font Awesome 6 Free';
                font-weight: 900;
                position: absolute;
                top: 15px;
                right: 15px;
                width: 30px;
                height: 30px;
                background: var(--primary-cyan);
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.85rem;
            }

            .payment-logo {
                font-size: 4rem;
                margin-bottom: 15px;
            }

            .stripe-logo {
                color: #635bff;
            }

            .paypal-logo {
                color: #0070ba;
            }

            .payment-name {
                font-weight: 700;
                font-size: 1.2rem;
                color: #333;
                margin-bottom: 5px;
            }

            .payment-desc {
                color: #666;
                font-size: 0.9rem;
            }

            .order-summary {
                background: #f8f9fa;
                border-radius: 12px;
                padding: 25px;
                margin-bottom: 25px;
            }

            .summary-row {
                display: flex;
                justify-content: space-between;
                padding: 12px 0;
                color: #666;
            }

            .summary-row.total {
                border-top: 2px solid #e0e0e0;
                margin-top: 10px;
                padding-top: 15px;
                font-size: 1.4rem;
                font-weight: 700;
                color: #333;
            }

            .summary-row.total .amount {
                color: var(--primary-cyan);
            }

            .discount-badge {
                background: var(--accent-yellow);
                color: #333;
                padding: 3px 10px;
                border-radius: 15px;
                font-size: 0.8rem;
                font-weight: 700;
                margin-left: 10px;
            }

            .promo-section {
                display: flex;
                gap: 10px;
                margin-bottom: 20px;
            }

            .promo-input {
                flex: 1;
                border: 2px solid #e0e0e0;
                border-radius: 10px;
                padding: 12px 15px;
                transition: all 0.3s;
            }

            .promo-input:focus {
                outline: none;
                border-color: var(--primary-cyan);
            }

            .promo-btn {
                background: var(--primary-cyan);
                color: white;
                border: none;
                padding: 12px 25px;
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.3s;
            }

            .promo-btn:hover {
                background: var(--dark-cyan);
            }

            .checkout-btn {
                width: 100%;
                padding: 18px;
                background: var(--primary-cyan);
                color: white;
                border: none;
                border-radius: 12px;
                font-weight: 700;
                font-size: 1.2rem;
                transition: all 0.3s;
                margin-top: 10px;
            }

            .checkout-btn:hover {
                background: var(--dark-cyan);
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 131, 143, 0.3);
            }

            .checkout-btn:disabled {
                background: #ccc;
                cursor: not-allowed;
                transform: none;
            }

            .security-note {
                text-align: center;
                color: #666;
                font-size: 0.9rem;
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid #e0e0e0;
            }

            .security-note i {
                color: var(--primary-cyan);
                margin-right: 5px;
            }

            .guarantee-box {
                background: #e8f5e9;
                border-left: 4px solid #4caf50;
                padding: 15px;
                border-radius: 8px;
                margin-top: 20px;
            }

            .guarantee-box h6 {
                font-weight: 700;
                color: #2e7d32;
                margin-bottom: 5px;
            }

            .guarantee-box p {
                color: #2e7d32;
                margin: 0;
                font-size: 0.9rem;
            }

            .empty-cart {
                text-align: center;
                padding: 60px 20px;
            }

            .empty-cart i.fa-shopping-cart {
                font-size: 5rem;
                color: #ccc;
                margin-bottom: 20px;
            }

            .empty-cart h3 {
                color: #666;
                margin-bottom: 15px;
            }

            .browse-btn {
                background: var(--primary-cyan);
                color: white;
                border: none;
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.3s;
                font-size: 1rem !important;
            }

            .browse-btn:hover {
                background: var(--dark-cyan);
            }

            .browse-btn i {
                margin-right: 5px;
            }

            .alert-success {
                background: #e8f5e9;
                border: 1px solid #4caf50;
                color: #2e7d32;
                border-radius: 10px;
                padding: 15px;
                margin-bottom: 20px;
            }

            @media (max-width: 768px) {
                .cart-item {
                    flex-direction: column;
                }

                .item-thumbnail {
                    width: 100%;
                    height: 150px;
                }

                .item-price {
                    align-items: flex-start;
                    flex-direction: row;
                    gap: 10px;
                }

                .payment-methods {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush
</x-guest-layout>
