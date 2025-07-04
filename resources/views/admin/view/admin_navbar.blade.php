<div class="amazon-header">
    <div class="amazon-header-left-section">
    <a href="/" class="header-link">
        <img class="amazon-logo"
        src="{{ asset('assets/images/amazon-logo-white.png') }}">
        <img class="amazon-mobile-logo"
        src="{{ asset('assets/images/amazon-mobile-logo-white.png') }}">
    </a>
    </div>

    <!-- <div class="amazon-header-middle-section">
    <input class="search-bar" type="text" placeholder="Search">

    <button class="search-button">
        <img class="search-icon" src="{{ asset('assets/images/icons/search-icon.png') }}">
    </button>
    </div>

    <div class="amazon-header-right-section">
    <a class="signUp-link header-link" href="/login">
        <span class="returns-text">Hello, sign in</span>
        <span class="orders-text">Account & Lists</span>
    </a>
    
    <a class="orders-link header-link" href="/orders">
        <span class="returns-text">Returns</span>
        <span class="orders-text">& Orders</span>
    </a>

    @php $total = 0 @endphp
    @foreach((array) session('cart') as $id => $details)
        @php $total += $details['itemQty'] @endphp
    @endforeach -->
    <!-- <a class="cart-link header-link" href="/cart">
        <img class="cart-icon" src="{{ asset('assets/images/icons/cart-icon.png') }}">
        <div class="cart-quantity">{{ $total }}</div>
        <div class="cart-text">Cart</div>
    </a> -->
    <div class="amazon-header-middle-section">
        <a class="products-link header-link" href="">
            <span class="orders-text">Products</span>
        </a>
    </div>
    <div class="amazon-header-right-section">
        <a class="products-link header-link" href="{{ route('logout') }}">
            <span class="orders-text">Logout</span>
        </a>
    </div>
</div>