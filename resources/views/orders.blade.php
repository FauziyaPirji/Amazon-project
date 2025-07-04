<!DOCTYPE html>
<html>
  <head>
    <title>Orders</title>

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
    <link rel="stylesheet" href="{{ asset('assets/css/pages/orders.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  </head>
  <body>
    @include('view.navbar');

    
    <div class="main" style="margin-top:40px;">
      <div class="page-title">Your Orders</div>

      <div class="orders-grid">
        @foreach($Orders as $order)
          <div class="order-container">
            
            <div class="order-header">
              <div class="order-header-left-section">
                <div class="order-date">
                  <div class="order-header-label">Order Placed:</div>
                  {{ $order->placeDate->format('F d') }}
                </div>
                <div class="order-total">
                  <div class="order-header-label">Total:</div>
                  ${{ number_format(round($order->total_price/100, 2), 2) }}
                </div>
              </div>

              <div class="order-header-right-section">
                <div class="order-header-label">Order ID:</div>
                  {{ $order->id }}
              </div>
            </div>
            @foreach($OrderItems as $orderItem)
              @if($orderItem->orderId == $order->id)
          
                <div class="order-details-grid">
                  <div class="product-image-container">
                    <img src="{{ asset('/storage/'. $orderItem->product->product_image) }}">
                  </div>

                  <div class="product-details">
                    <div class="product-name">
                      {{ $orderItem->product->name }}
                    </div>
                    <div class="product-delivery-date">
                      Arriving on: {{ $orderItem->arrivalDate->format('F d') }}
                    </div>
                    <div class="product-quantity">
                      Quantity: {{ $orderItem->itemQty }}
                    </div>
                    <form action="{{ route('cart.add', $orderItem->product->id) }}" method="post">
                      @csrf
                      <input type="hidden" value="1" name="quantity">
                      <button class="buy-again-button button-primary" type="submit">
                        <img class="buy-again-icon" src="{{ asset('assets/images/icons/buy-again.png') }}">
                        <span class="buy-again-message">Buy it again</span>
                      </button>
                    </form>
                  </div>

                  <div class="product-actions">
                    <a href="{{ route('tracking', $orderItem->id) }}">
                      <button class="track-package-button button-secondary">
                        Track package
                      </button>
                    </a>
                  </div>
                </div>
              @endif
            @endforeach
          </div>
        @endforeach
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
  </body>
</html>
