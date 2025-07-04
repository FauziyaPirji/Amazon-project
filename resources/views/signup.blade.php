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
    <link rel="stylesheet" href="{{ asset('assets/css/pages/signup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .error {
            color:red;
        }
    </style>
  </head>
  <body>
    <center>
        <a href="/">
            <img class="amazon-logo" src="{{ asset('assets/images/amazon-logo.png') }}">
            <img class="amazon-mobile-logo" src="{{ asset('assets/images/amazon-mobile-logo.png') }}">
        </a>
        <form id="form_register">
            
            <p class="signUp-header">Create account</p>
            <br><br><br>
            <label class="form-label">Enter First Name</label><br>
            <div class="col form-input2">
                <input type="text" class="form-control" id="firstName" name="firstName" value="{{ old('firstName') }}">
                <div class="invalid-tooltip">Required</div>
                
                    <div class="error firstName_err"></div>
                
            </div>
            
            <label class="form-label">Enter Middle Name</label><br>
            <div class="col form-input2">
                <input type="text" class="form-control" id="middleName" name="middleName" value="{{ old('middleName') }}">
                <div class="invalid-tooltip">Required</div>
                
                    <div class="error middleName_err"></div>
                
            </div>

            <label class="form-label">Enter Last Name</label><br>
            <div class="col form-input2">
                <input type="text" class="form-control" id="lastName" name="lastName" value="{{ old('lastName') }}">
                <div class="invalid-tooltip">Required</div>
                
                    <div class="error lastName_err"></div>
                
            </div>

            <label class="form-label">Photo [Optinal]</label><br>
            <div class="col form-input2">
                <input type="file" class="form-control" id="photo" name="photo">
                
                    <div class="error photo_err"></div>
                
            </div>

            <label class="form-label">Enter Date Of Birth</label><br>
            <div class="col form-input2">
                <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}">
                <div class="invalid-tooltip">Required</div>
                
                    <div class="error dob_err"></div>
                
            </div>

            <label class="form-label">Enter Email</label><br>
            <div class="col form-input2">
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                <div class="invalid-tooltip">Required</div>
                
                    <div class="error email_err"></div>
                
            </div>

            <label class="form-label">Enter Phone Number</label><br>
            <div class="col form-input2">
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                <div class="invalid-tooltip">Required</div>
                
                    <div class="error phone_err"></div>
                
            </div>

            <label class="form-label">Enter Password</label><br>
            <div class="col form-input2">
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
                <div class="invalid-tooltip">Required</div>
                
                    <div class="error password_err"></div>
                
            </div>

            <label class="form-label">Enter Confirm Password</label><br>
            <div class="col form-input2">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}">
                <div class="invalid-tooltip">Required</div>
                
                    <div class="error password_confirmation_err"></div>
                
            </div>

            <button type="submit" class="signUp-button">Continue</button>
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
            $("#form_register").submit(function(event) {
                event.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url:"http://127.0.0.1:8000/api/register",
                    type:"POST",
                    data:formData,
                    success:function(data) {
                        if(data.msg) {
                            $("#form_register")[0].reset();
                            $(".error").text("");
                            window.open("/login", "_self");
                        }
                        else {
                            printErrorMsg(data);
                        }
                    }
                });

            });

            function printErrorMsg(data) {
                $(".error").text("");
                $.each(data, function(key, value) {

                    if(key == 'password') {
                        if(value.length > 1) {
                            $(".password_err").text(value[0]);
                            $(".password_confirmation_err").text(value[1]);
                        }
                        else {
                            if(value[0].includes('password confirmation')) {
                                $(".password_confirmation_err").text(value);
                            }
                            else {
                                $(".password_err").text(value);
                            }
                        }
                    }
                    else {
                        $("."+key+"_err").text(value);
                    }
                });
            }

        });
    </script>
  </body>
</html>
