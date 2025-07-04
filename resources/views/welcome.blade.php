<!DOCTYPE html>
<html>
  <head>
    <title>Amazon Project</title>

    <!-- This code is needed for responsive design to work.
      (Responsive design = make the website look good on
      smaller screen sizes like a phone or a tablet). -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Load a font called Roboto from Google Fonts. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Here are the CSS files for this page. -->
    <link rel="stylesheet" href="{{ asset('assets/css/shared/general.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/shared/amazon-header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/amazon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  </head>
  <body>
    @include('view.navbar');
    
    <div class="main">
      @if(Session::has('success')) 
        <div class="alert alert-success">
          <img src="{{ asset('assets/images/icons/checkmark.png') }}">
          {{ Session::get('success') }}
        </div>
      @endif
      <div class="products-grid">
        @foreach($Products as $product)
        <div class="product-container">
          <div class="product-image-container">
            <img class="product-image"
              src="{{ asset('/storage/'. $product->product_image) }}">
          </div>

          <div class="product-name limit-text-to-2-lines">
            {{ $product->name }}
          </div>

          <!-- <div class="product-rating-container">
            <img class="product-rating-stars"
              src="{{ asset('assets/images/ratings/rating-45.png') }}">
            <div class="product-rating-count link-primary">
              87
            </div>
            @if (Auth::check())
              <div class="product-spacer" style="margin-left: 10px;">
                <a href="" data-toggle="modal" data-target="#editProduct{{ $product->id }}">Review</a>
              </div>
            @endif
          </div> -->
          </tr>
          <div class="product-price">
            ${{ number_format(round($product->price/100, 2), 2) }}
          </div>

          <form action="{{ route('cart.add', $product->id) }}" method="post">
            @csrf
            <div class="product-quantity-container">
              <select name="quantity">
                <option selected value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
              </select>
            </div>

            <input type="hidden" name="session_id" value="{{ $session_id }}">

            <button class="add-to-cart-button button-primary">
              Add to Cart
            </button>
          </form>
        </div>
        @endforeach
      </div>
    </div>
    
    <script>
      $(document).ready(function(){
        $("#logout").click(function(){
            $.ajax({
                url:"http://127.0.0.1:8000/api/logout",
                type:"GET",
                headers:{ 'Authorization':localStorage.getItem("user_token") },
                success:function(data) {
                    if(data.success == true) {
                        localStorage.removeItem('user_token');
                        window.open("/login", "_self");
                    }
                    else {
                        alert(data.msg);
                    }
                }
            });
        });
    });
    </script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
  </body>
</html>
