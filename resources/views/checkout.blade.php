<!DOCTYPE html>
<html>
  <head>
    <title>Checkout</title>

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
    <link rel="stylesheet" href="{{ asset('assets/css/pages/checkout/checkout-header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/checkout/checkout.css') }}">
  </head>
  <body>
    <div class="checkout-header">
      <div class="header-content">
        <div class="checkout-header-left-section">
          <a href="/">
            <img class="amazon-logo" src="{{ asset('assets/images/amazon-logo.png') }}">
            <img class="amazon-mobile-logo" src="{{ asset('assets/images/amazon-mobile-logo.png') }}">
          </a>
        </div>

        @php $total = 0 @endphp
        @php $total_price = 0 @endphp
        @php $shipping_charge = 0 @endphp
        @php $total_before_tax = 0 @endphp
        @php $tax = 0 @endphp
        @php $final_bill = 0 @endphp
        @foreach((array) session('cart') as $id => $details)
            @php $total += $details['itemQty'] @endphp
        @endforeach

        <div class="checkout-header-middle-section">
          Checkout (<a class="return-to-home-link"
            href="amazon.html">{{ $total }} items</a>)
        </div>

        <div class="checkout-header-right-section">
          <img src="{{ asset('assets/images/icons/checkout-lock-icon.png') }}">
        </div>
      </div>
    </div>

    <div class="main">
      
      <div class="page-title">Review your order</div>

      <div class="checkout-grid">
        <div class="order-summary">
          @if(session('cart'))

            @foreach(session('cart') as $id => $details)

              @foreach($Products as $product)
                @if($details['productId'] === $product->id)

                  @php $price = $product->price * $details['itemQty'] @endphp
                  @php $total_price += $price @endphp

                  @foreach($DeliveryOption as $deliveryOption)
                    @if($details['deliveryOptionId'] == $deliveryOption->id)

                      @php $shipping_charge += $deliveryOption->price @endphp

                      <div class="cart-item-container">
                        <div class="delivery-date">
                          Delivery date: {{ now()->addDays($deliveryOption->deliveryDays)->format('l, F d') }}
                        </div>

                        <div class="cart-item-details-grid">
                          <img class="product-image"
                            src="{{ asset('/storage/'. $product->product_image) }}">

                          <div class="cart-item-details">
                            <div class="product-name">
                              {{ $product->name }}
                            </div>
                            <div class="product-price">
                              
                            </div>
                            <div class="product-quantity">
                              <span>
                                Quantity: <span class="quantity-label">{{ $details['itemQty'] }}</span>
                              </span>
                              <span class="update-quantity-link link-primary js-update-link js-update-link2-{{ $product->id }}" data-product-id="{{ $product->id }}">
                                Update
                              </span>
                              
                              <form class="form1" action="{{ route('cart.update') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input class="quantity-input js-quantity-input js-cart-item-container2-{{ $product->id }}" name="quantity" data-product-id="{{ $product->id }}">
                                <button class="save-quantity-link link-primary js-link-primary-{{ $product->id }}" data-product-id="{{ $product->id }}">Save</button>
                              </form>
                              <form class="form1" action="{{ route('cart.delete', $product->id) }}" method="post">
                                @csrf
                                <button class="delete-quantity-link link-primary">Delete</button>
                              </form>
                              <div class="product-quantity">
                                <span class="extra-validation js-extra-validation-{{ $product->id }}"></span>
                              </div>
                            </div>
                          </div>

                          
                          <div class="delivery-options">
                            <div class="delivery-options-title">
                              Choose a delivery option:
                            </div>
                            <form action="{{ route('cart.radio.update', $product->id) }}" method="post">
                              @csrf 
                              <div class="delivery-option">
                                <input type="radio" {{ $deliveryOption->id == 1 ? 'checked' : '' }}
                                  class="delivery-option-input delivery-option-input1{{ $product->id }}" 
                                  name="delivery_option_1{{ $product->id }}" id="delivery_option1" data-product-id="{{ $product->id }}" value="1">
                                <div>
                                  <div class="delivery-option-date">
                                    {{ now()->addDays(7)->format('l, F d') }}
                                  </div>
                                  <div class="delivery-option-price">
                                    FREE Shipping
                                  </div>
                                </div>
                              </div>
                            
                              <div class="delivery-option">
                                <input type="radio" {{ $deliveryOption->id == 2 ? 'checked' : '' }}
                                  class="delivery-option-input delivery-option-input2{{ $product->id }}" 
                                  name="delivery_option_1{{ $product->id }}" id="delivery_option2" data-product-id="{{ $product->id }}" value="2">
                                <div>
                                  <div class="delivery-option-date">
                                    {{ now()->addDays(3)->format('l, F d') }}
                                  </div>
                                  <div class="delivery-option-price">
                                    $4.99 - Shipping
                                  </div>
                                </div>
                              </div>
                            
                              <div class="delivery-option">
                                <input type="radio" {{ $deliveryOption->id == 3 ? 'checked' : '' }}
                                  class="delivery-option-input delivery-option-input3{{ $product->id }}"
                                  name="delivery_option_1{{ $product->id }}" id="delivery_option3" data-product-id="{{ $product->id }}" value="3">
                                <div>
                                  <div class="delivery-option-date">
                                    {{ now()->addDays(1)->format('l, F d') }}
                                  </div>
                                  <div class="delivery-option-price">
                                    $9.99 - Shipping
                                  </div>
                                </div>
                              </div>

                              <input type="hidden" name="radio_value" class="radio_value1{{ $product->id }}">
                              <button type="submit" class="change_button" data-product-id="{{ $product->id }}">Change</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach
                @endif
              @endforeach
            @endforeach
          @endif
        </div>
        

        @php $total_before_tax += $total_price + $shipping_charge @endphp
        @php $tax = $total_before_tax * 0.10 @endphp
        @php $final_bill = $total_before_tax + $tax @endphp

        <div class="payment-summary">
          <div class="payment-summary-title">
            Order Summary
          </div>

          <div class="payment-summary-row">
            <div>Items ({{ $total }}):</div>
            <div class="payment-summary-money">${{ number_format(round($total_price/100, 2), 2) }}</div>
          </div>

          <div class="payment-summary-row">
            <div>Shipping &amp; handling:</div>
            <div class="payment-summary-money">${{ number_format(round($shipping_charge/100, 2), 2) }}</div>
          </div>

          <div class="payment-summary-row subtotal-row">
            <div>Total before tax:</div>
            <div class="payment-summary-money">${{ number_format(round($total_before_tax/100, 2), 2) }}</div>
          </div>

          <div class="payment-summary-row">
            <div>Estimated tax (10%):</div>
            <div class="payment-summary-money">${{ number_format(round($tax/100, 2), 2) }}</div>
          </div>

          <div class="payment-summary-row total-row">
            <div>Order total:</div>
            <div class="payment-summary-money">${{ number_format(round($final_bill/100, 2), 2) }}</div>
          </div>

          <form action="{{ route('placeOrder', $final_bill) }}" method="post">
            @csrf
            <button class="place-order-button button-primary">
              Place your order
            </button>
          </form>
        </div>
      </div>
    </div>
    <script>
      document.querySelectorAll('.js-update-link')
      .forEach((link) => {
        link.addEventListener('click', () => {
          const productId = link.dataset.productId;

          // Show the quantity input
          const quantityInput = document.querySelector(
            `.js-cart-item-container2-${productId}`
          );
          quantityInput.classList.add('is-editing-quantity');

          // Show the save button
          const saveLink = document.querySelector(
            `.js-link-primary-${productId}`
          );
          saveLink.classList.add('is-editing-quantity2');

          // Remove the update button
          const updateLink = document.querySelector(
            `.js-update-link2-${productId}`
          );
          updateLink.classList.add('is-editing-quantity3');
        });
      });

  document.querySelectorAll('.save-quantity-link')
    .forEach((link) => {
      link.addEventListener('click', () => {
        const productId = link.dataset.productId; 

          let update_qty = Number(document.querySelector(`.js-cart-item-container2-${productId}`).value);
          updateCartQuantity2(update_qty, productId);

        //Remove the quantityy input
        const quantityInput = document.querySelector(
          `.js-cart-item-container2-${productId}`
        );
        quantityInput.classList.remove('is-editing-quantity');

        // Remove the save button
        const saveLink = document.querySelector(
          `.js-link-primary-${productId}`
        );
        saveLink.classList.remove('is-editing-quantity2');

        // Remove the update button
        const updateLink = document.querySelector(
          `.js-update-link2-${productId}`
        );
        updateLink.classList.remove('is-editing-quantity3');
      });
    });

  function updateCartQuantity2(new_qty, productid) {
    if(new_qty <= 0 || new_qty > 1000) {
      document.querySelector(`.js-extra-validation-${productid}`).innerHTML = "Cart product not less 0 or product not more then 1000";
      setInterval(() => {
        document.querySelector(`.js-extra-validation-${productid}`).innerHTML = "";
      }, 4000);
    }
  }

  document.querySelectorAll('.change_button')
    .forEach((link) => {
      link.addEventListener('click', () => {
        const productId = link.dataset.productId; 
        
        if(document.querySelector(`.delivery-option-input1${productId}`).checked) {
          value1 = document.querySelector(`.delivery-option-input1${productId}`).value;
          document.querySelector(`.radio_value1${productId}`).value = value1;
        }
        
        else if(document.querySelector(`.delivery-option-input2${productId}`).checked) {
          value2 = document.querySelector(`.delivery-option-input2${productId}`).value;
          document.querySelector(`.radio_value1${productId}`).value = value2;
        }

        else {
          value3 = document.querySelector(`.delivery-option-input3${productId}`).value;
          document.querySelector(`.radio_value1${productId}`).value = value3;
        }
      });
    });

  // document.querySelectorAll('.delivery-option-input1')
  //   .forEach((link) => {
  //     link.addEventListener('click', () => {
  //       const productId = link.dataset.productId;
  //       console.log(productId);

  //       @if (Session::get('cart'))
  //         console.log("yes");
  //         @php
  //           $cart = session::get('cart');

  //           $cart[`{productId}`]["deliveryOptionId"] = 1;
  //           // session::put('cart', $cart);
  //         @endphp
  //       @endif
  //     }); 
  //   });

  // document.querySelectorAll('.delivery-option-input2')
  //   .forEach((link) => {
  //     link.addEventListener('click', () => {
  //       const productId = link.dataset.productId;
  //       console.log(productId);

  //       @if (Session::get('cart'))
  //         console.log("yes");
  //         @php
  //           $cart = session::get('cart');

  //           $cart[`{productId}`]["deliveryOptionId"] = 2;
  //           // session::put('cart', $cart);
  //         @endphp
  //       @endif
  //     }); 
  //   });

  // document.querySelectorAll('.delivery-option-input3')
  //   .forEach((link) => {
  //     link.addEventListener('click', () => {
  //       const productId = link.dataset.productId;
  //       console.log(productId);

  //       @if (Session::get('cart'))
  //         console.log("yes");
  //         @php
  //           $cart = session::get('cart');

  //           $cart[`{productId}`]["deliveryOptionId"] = 3;
  //           // session::put('cart', $cart);
  //         @endphp
  //       @endif
  //     }); 
  //   });
    </script>
  </body>
</html>
