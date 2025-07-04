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
    <link rel="stylesheet" href="{{ asset('assets/css/pages/login.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  </head>
  <body>
    <center>
        <a href="/">
            <img class="amazon-logo" src="{{ asset('assets/images/amazon-logo.png') }}">
            <img class="amazon-mobile-logo" src="{{ asset('assets/images/amazon-mobile-logo.png') }}">
        </a>
        <form id="form_login">

            <p class="signUp-header">Sign in</p>
            <br><br><br>
            <label class="form-label">Email</label><br>
            <div class="col form-input2">
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-tooltip">Required</div>
               
            </div>
            
            <label class="form-label">Password</label><br>
            <div class="col form-input2">
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="invalid-tooltip">Required</div>
                
            </div>
           
                <div class="form-row d-flex justify-content-center result"></div>
            

            <button type="submit" class="signUp-button">Log in</button>

            <a href="/signup">Don't have account?</a>
        </form>
    </center>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
            });
        }, false);
        })();

        $(document).ready(function () {
            $("#form_login").submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url:"http://127.0.0.1:8000/api/login",
                    type:"POST",
                    data:formData,
                    success:function(data) {
                        $(".error").text("");
                        if(data.success == false) {
                            $(".result").text(data.msg);
                        }
                        else if(data.success == true) {
                            localStorage.setItem("user_token",data.token_type+" "+data.access_token);
                            window.open("/", "_self");
                        }
                    }
                });

            });
        });
    </script>
  </body>
</html>
