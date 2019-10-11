<style>
    .vl {
        border-left: 2px solid black;
        height: 100%;
        margin-left: 30%;
    }

</style>

<div class="footer-middle">
    <div class="container">
        <div class="footer-ribbon">
            Get in touch
        </div><!-- End .footer-ribbon -->
        <div class="row">
            <div class="col-lg-3">
                <div class="widget">
                    <h4 class="widget-title">Contact Us</h4>
                    <ul class="contact-info">
                        <li>
                            <span class="contact-info-label">Address:</span>123 Street Name, City, England
                        </li>
                        <li>
                            <span class="contact-info-label">Phone:</span>Toll Free <a href="tel:">(123) 456-7890</a>
                        </li>
                        <li>
                            <span class="contact-info-label">Email:</span> <a
                                href="mailto:mail@example.com">mail@example.com</a>
                        </li>
                        <li>
                            <span class="contact-info-label">Working Days/Hours:</span>
                            Mon - Sun / 9:00AM - 8:00PM
                        </li>
                    </ul>
                    <div class="social-icons">
                        <a href="#" class="social-icon" target="_blank"><i class="icon-facebook"></i></a>
                        <a href="#" class="social-icon" target="_blank"><i class="icon-twitter"></i></a>
                        <a href="#" class="social-icon" target="_blank"><i class="icon-linkedin"></i></a>
                    </div><!-- End .social-icons -->
                </div><!-- End .widget -->
            </div><!-- End .col-lg-3 -->

            <div class="col-lg-9">
                <div class="widget widget-newsletter">
                    <h4 class="widget-title">Subscribe newsletter</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Get all the latest information on Events,Sales and Offers. Sign up for newsletter today
                            </p>
                        </div><!-- End .col-md-6 -->

                        <div class="col-md-6">
                            <form action="#">
                                <input type="email" class="form-control" placeholder="Email address" required>

                                <input type="submit" class="btn" value="Subscribe">
                            </form>
                        </div><!-- End .col-md-6 -->
                    </div><!-- End .row -->
                </div><!-- End .widget -->

                <div class="row">
                    <div class="col-md-5">
                        <div class="widget">
                            <h4 class="widget-title">My Account</h4>

                            <div class="row">
                                <div class="col-sm-6 col-md-5">
                                    <ul class="links">
                                        <li><a href="about.html">About Us</a></li>
                                        <li><a href="contact.html">Contact Us</a></li>
                                        <li><a href="my-account.html">My Account</a></li>
                                    </ul>
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-6 col-md-5">
                                    <ul class="links">
                                        <li><a href="#">Orders History</a></li>
                                        <li><a href="#">Advanced Search</a></li>
                                        <li><a href="#" class="login-link">Login</a></li>
                                    </ul>
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->
                        </div><!-- End .widget -->
                    </div><!-- End .col-md-5 -->

                    <div class="col-md-7">
                        <div class="widget">
                            <h4 class="widget-title">Main Features</h4>

                            <div class="row">
                                <div class="col-sm-6">
                                    <ul class="links">
                                        <li><a href="#">Super Fast Magento Theme</a></li>
                                        <li><a href="#">1st Fully working Ajax Theme</a></li>
                                        <li><a href="#">20 Unique Homepage Layouts</a></li>
                                    </ul>
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-6">
                                    <ul class="links">
                                        <li><a href="#">Powerful Admin Panel</a></li>
                                        <li><a href="#">Mobile & Retina Optimized</a></li>
                                    </ul>
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->
                        </div><!-- End .widget -->
                    </div><!-- End .col-md-7 -->
                </div><!-- End .row -->
            </div><!-- End .col-lg-9 -->
        </div><!-- End .row -->
    </div><!-- End .container -->
</div><!-- End .footer-middle -->

<div class="container">
    <div class="footer-bottom">
        <p class="footer-copyright">Porto eCommerce. &copy; 2018. All Rights Reserved</p>

        <img src="{{ asset('porto-ecommerce/assets/images/payments.png') }}" alt="payment methods"
            class="footer-payments">
    </div><!-- End .footer-bottom -->
</div><!-- End .container -->

<div class="modal fade" id="moda-succes-add-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!--<div class="modal-header ">
                  <h5 class="modal-title" id="exampleModalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>-->
            <div class="modal-body">

                <div class="alert alert-success" role="alert">
                    <i class="icon-ok"></i> Tu producto se agregó al carrito
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('tenant_detail_cart') }}" class="btn btn-warning">Ir a Carrito</a>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Seguir Comprando</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-already-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!--<div class="modal-header ">
                  <h5 class="modal-title" id="exampleModalLabel"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>-->
            <div class="modal-body">

                <div class="alert alert-warning" role="alert">
                    <i class="icon-ok"></i> Tu Producto ya ha sido esta agregado al carrito.
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('tenant_detail_cart') }}" class="btn btn-warning">Ir a Carrito</a>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Seguir Comprando</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="login_register_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-body">

                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-5 ">
                            <h4 class="title mb-2">Login</h4>

                            <div id="msg_login" class="alert alert-danger" role="alert">
                                Usuario o Password Incorrectos.
                            </div>

                            <form action="#" id="form_login">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" required class="form-control" id="email"
                                        placeholder="Enter email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="pwd">Password:</label>
                                    <input type="password" required class="form-control" id="pwd"
                                        placeholder="Enter password" name="password">
                                </div>

                                <button type="submit" class="btn btn-primary">Ingresar</button>
                            </form>
                        </div>
                        <div class="col-md-1 text-center">
                            <div class="vl"></div>
                        </div>
                        <div class="col-md-5">
                            <h4 class="title mb-2">Register</h4>
                            <div id="msg_register" class="alert alert-danger" role="alert">
                                <p id="msg_register_p"></p>
                            </div>

                            <form action="#" id="form_register">
                                <div class="form-group">
                                    <label for="email">Name:</label>
                                    <input type="text" required autocomplete="off" class="form-control" id="name_reg"
                                        placeholder="Enter name" name="name">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" required autocomplete="off" class="form-control" id="email_reg"
                                        placeholder="Enter email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="pwd">Password:</label>
                                    <input type="password" required autocomplete="off" class="form-control" id="pwd_reg"
                                        placeholder="Enter password" name="pswd">
                                </div>
                                <div class="form-group">
                                    <label for="pwd">Repeat Password:</label>
                                    <input type="password" required autocomplete="off" class="form-control"
                                        id="pwd_repeat_reg" placeholder="Repeat password" name="pswd">
                                </div>

                                <button type="submit" class="btn btn-primary">Registrarse</button>
                            </form>


                        </div>

                    </div>
                </div>



            </div>

        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    matchPassword();
    submitLogin();
    submitRegister();


    function matchPassword() {
        var password = document.getElementById("pwd_reg"),
            confirm_password = document.getElementById("pwd_repeat_reg");

        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("El Password no coincide.");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    }

    function submitLogin() {
        $('#msg_login').hide();

        $('#form_login').submit(function (e) {
            e.preventDefault()
            $.ajax({
                type: "POST",
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('tenant_ecommerce_login')}}",
                data: $(this).serialize(),
                success: function (data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        $('#msg_login').show();
                    }
                },
                error: function (error_data) {
                    console.log(error_data)
                }
            });
        })

    }

    function submitRegister()
    {
        $('#msg_register').hide();

        $('#form_register').submit(function (e) {
            e.preventDefault()
            $.ajax({
                type: "POST",
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('tenant_ecommerce_store_user')}}",
                data: $(this).serialize(),
                success: function (data) {
                    if (data.success) {
                        location.reload();
                    } else {
                        $('#msg_register').show();
                        $('#msg_register_p').text(data.message)
                    }
                },
                error: function (error_data) {
                    console.log(error_data)
                }
            });
        })
    }

</script>
@endpush
