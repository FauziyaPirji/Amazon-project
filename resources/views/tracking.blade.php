<!DOCTYPE html>
<html>
  <head>
    <title>Tracking</title>

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
    <link rel="stylesheet" href="{{ asset('assets/css/pages/tracking.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  </head>
  <body>
    @include('view.navbar');

    <div class="main">
      <div class="order-tracking">
        <a class="back-to-orders-link link-primary" href="/orders">
          View all orders
        </a>

        @foreach($OrderItems as $orderItem)
        <div class="delivery-date">
          Arriving on {{ $orderItem->arrivalDate->format('l, F d') }}
        </div>

        <div class="product-info">
          {{ $orderItem->product->name }}
        </div>

        <div class="product-info">
          Quantity: {{ $orderItem->itemQty }}
        </div>

        <img class="product-image" src="{{ asset('/storage/'. $orderItem->product->product_image) }}">

        <div class="progress-labels-container">
          <div class="progress-label">
            Preparing
          </div>
          <div class="progress-label">
            Shipped
          </div>
          <div class="progress-label">
            Delivered
          </div>
        </div>

        @php $totalDays = \Carbon\Carbon::parse($orderItem->order->placeDate)->diffInDays(\Carbon\Carbon::parse($orderItem->arrivalDate)); @endphp

        @php $daysLeft = \Carbon\Carbon::parse($orderItem->order->placeDate)->diffInDays(now()); @endphp

        @php $percentage = ($daysLeft / $totalDays) * 100; @endphp

        <div class="progress-bar-container">
          <div class="progress-bar" style="width: {{ $percentage }}%;"></div>
        </div>
        @endforeach
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
  </body>
</html>
