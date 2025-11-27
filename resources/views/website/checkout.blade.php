<x-guest-layout>

    <div class="wrapper">
        <!-- Header -->
        <div class="checkout-header text-center">
            <h1><i class="fas fa-shopping-cart me-3"></i>Checkout</h1>
        </div>

        <div class="row">
            <!-- Left Column - Cart Items -->
            <div class="col-lg-8">
                <!-- Cart Items -->
                <div class="checkout-card">
                    <h3 class="section-title">
                        <i class="fas fa-list"></i>
                        Your Cart (<span id="itemCount">3</span> items)
                    </h3>

                    <div id="cartItems">
                        <!-- Cart Item 1 -->
                        <div class="cart-item" data-price="89.99">
                            <div class="item-thumbnail">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="item-details">
                                <h5 class="item-title">Complete Calculus Mastery</h5>
                                <p class="item-educator">
                                    <i class="fas fa-user me-1"></i>Dr. Sarah Johnson
                                </p>
                                <div class="item-meta">
                                    <span><i class="fas fa-clock"></i> 25 hours</span>
                                    <span><i class="fas fa-play-circle"></i> 48 lessons</span>
                                    <span><i class="fas fa-star text-warning"></i> 4.9</span>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="current-price">$89.99</div>
                                <div class="original-price">$179.99</div>
                            </div>
                            <button class="remove-btn" onclick="removeItem(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Cart Item 2 -->
                        <div class="cart-item" data-price="79.99">
                            <div class="item-thumbnail"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-atom"></i>
                            </div>
                            <div class="item-details">
                                <h5 class="item-title">Physics Fundamentals</h5>
                                <p class="item-educator">
                                    <i class="fas fa-user me-1"></i>Dr. Sarah Johnson
                                </p>
                                <div class="item-meta">
                                    <span><i class="fas fa-clock"></i> 20 hours</span>
                                    <span><i class="fas fa-play-circle"></i> 35 lessons</span>
                                    <span><i class="fas fa-star text-warning"></i> 4.8</span>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="current-price">$79.99</div>
                                <div class="original-price">$159.99</div>
                            </div>
                            <button class="remove-btn" onclick="removeItem(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Cart Item 3 -->
                        <div class="cart-item" data-price="69.99">
                            <div class="item-thumbnail"
                                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <i class="fas fa-square-root-alt"></i>
                            </div>
                            <div class="item-details">
                                <h5 class="item-title">Linear Algebra Made Easy</h5>
                                <p class="item-educator">
                                    <i class="fas fa-user me-1"></i>Dr. Sarah Johnson
                                </p>
                                <div class="item-meta">
                                    <span><i class="fas fa-clock"></i> 18 hours</span>
                                    <span><i class="fas fa-play-circle"></i> 30 lessons</span>
                                    <span><i class="fas fa-star text-warning"></i> 4.9</span>
                                </div>
                            </div>
                            <div class="item-price">
                                <div class="current-price">$69.99</div>
                                <div class="original-price">$139.99</div>
                            </div>
                            <button class="remove-btn" onclick="removeItem(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
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

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Original Price</span>
                            <span id="originalTotal">$479.97</span>
                        </div>
                        <div class="summary-row">
                            <span>
                                Discount
                                <span class="discount-badge">50% OFF</span>
                            </span>
                            <span style="color: var(--accent-pink);" id="discountAmount">-$240.00</span>
                        </div>
                        <div class="summary-row" id="promoRow" style="display: none;">
                            <span>Promo Code</span>
                            <span style="color: var(--accent-pink);" id="promoAmount">-$20.00</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span class="amount" id="finalTotal">$239.97</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <button class="checkout-btn" onclick="proceedCheckout()" id="checkoutBtn">
                        <i class="fas fa-lock me-2"></i>
                        Complete Purchase
                    </button>

                    <!-- Guarantee -->
                    <div class="guarantee-box">
                        <h6><i class="fas fa-undo me-2"></i>30-Day Money-Back Guarantee</h6>
                        <p>Full refund if you're not satisfied</p>
                    </div>

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
                            <li style="padding: 8px 0; color: #666;">
                                <i class="fas fa-check-circle me-2" style="color: var(--primary-cyan);"></i>
                                Certificate of completion
                            </li>
                            <li style="padding: 8px 0; color: #666;">
                                <i class="fas fa-check-circle me-2" style="color: var(--primary-cyan);"></i>
                                Access on mobile and desktop
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            let selectedPaymentMethod = 'stripe';

            // Select payment method
            function selectPayment(method) {
                selectedPaymentMethod = method;

                document.getElementById('stripeOption').classList.remove('selected');
                document.getElementById('paypalOption').classList.remove('selected');

                if (method === 'stripe') {
                    document.getElementById('stripeOption').classList.add('selected');
                } else {
                    document.getElementById('paypalOption').classList.add('selected');
                }
            }

            // Remove item from cart
            function removeItem(button) {
                const item = button.closest('.cart-item');
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';

                setTimeout(() => {
                    item.remove();
                    updateCart();
                }, 300);
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

            // Proceed to checkout
            function proceedCheckout() {
                const items = document.querySelectorAll('.cart-item').length;

                if (items === 0) {
                    alert('Your cart is empty!');
                    return;
                }

                const btn = document.getElementById('checkoutBtn');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                btn.disabled = true;

                setTimeout(() => {
                    if (selectedPaymentMethod === 'stripe') {
                        // Redirect to Stripe Checkout
                        alert(
                            'Redirecting to Stripe Checkout...\n\nIn production, this would redirect to:\nhttps://checkout.stripe.com/...');

                        // In real implementation:
                        // window.location.href = 'https://checkout.stripe.com/c/pay/...';
                        // OR use Stripe.js to create a checkout session
                    } else {
                        // Redirect to PayPal
                        alert(
                            'Redirecting to PayPal...\n\nIn production, this would redirect to:\nhttps://www.paypal.com/checkoutnow...');

                        // In real implementation:
                        // window.location.href = 'https://www.paypal.com/checkoutnow?token=...';
                    }

                    // Reset button
                    btn.innerHTML = '<i class="fas fa-lock me-2"></i>Complete Purchase';
                    btn.disabled = false;
                }, 2000);
            }

            // Browse courses
            function browseCourses() {
                alert('Redirecting to courses page...');
                // window.location.href = '/courses';
            }

            // Initialize
            updateCart();
        </script>
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

            .empty-cart i {
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
                padding: 12px 30px;
                border-radius: 10px;
                font-weight: 600;
                transition: all 0.3s;
            }

            .browse-btn:hover {
                background: var(--dark-cyan);
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
