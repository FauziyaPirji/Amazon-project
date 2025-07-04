<div class="amazon-header">
    <div class="amazon-header-left-section">
    <a href="/" class="header-link">
        <img class="amazon-logo"
        src="{{ asset('assets/images/amazon-logo-white.png') }}">
        <img class="amazon-mobile-logo"
        src="{{ asset('assets/images/amazon-mobile-logo-white.png') }}">
    </a>
    </div>

    <form class="amazon-header-middle-section" action="{{ route('product.search') }}" method="get">
    <!-- <div class="amazon-header-middle-section"> -->
        <input class="search-bar" type="text" placeholder="Search" name="search_text">

        <button class="search-button" type="submit">
            <img class="search-icon" src="{{ asset('assets/images/icons/search-icon.png') }}">
        </button>   
    <!-- </div> -->
    </form>
        
        <div class="amazon-header-right-section" id="login">
            <ul>
                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown header-link">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                        Welcome 
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- <a class="dropdown-item" href="">Profile</a> -->
                        <a class="dropdown-item" id="logout">Logout</a>
                    </div>
                </li>
            </ul>
        
            <a class="orders-link header-link" href="/orders">
                <span class="returns-text">Returns</span>
                <span class="orders-text">& Orders</span>
            </a>
        </div>

        
        <div class="amazon-header-right-section" id="no_login">
            <a class="signUp-link header-link" href="/login">
                <span class="returns-text">Hello, sign in</span>
                <span class="orders-text">Account & Lists</span>
            </a>
        </div>
        
    

    @php $total = 0 @endphp
    @foreach((array) session('cart') as $id => $details)
        @php $total += $details['itemQty'] @endphp
    @endforeach
    <a class="cart-link header-link" href="/cart">
        <img class="cart-icon" src="{{ asset('assets/images/icons/cart-icon.png') }}">
        <div class="cart-quantity">{{ $total }}</div>
        <div class="cart-text">Cart</div>
    </a>
    </div>
</div>
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
   -->
<script>
    var token = localStorage.getItem('user_token');

    if(token == null) {
        $("#login").hide();
    }
    if(token != null) {
        $("#no_login").hide();
    }

    if(window.location.pathname == '/login' || window.location.pathname == '/signup') {
        if(token != null) {
            window.open('/', '_self');
        }
    }
</script>