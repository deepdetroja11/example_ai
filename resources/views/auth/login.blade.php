<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login Page</title>
</head>

<body>
    <section class="background-radial-gradient overflow-hidden">
        <style>
            .background-radial-gradient {
                background-color: hsl(218, 41%, 15%);
                background-image: radial-gradient(650px circle at 0% 0%,
                        hsl(218, 41%, 35%) 15%,
                        hsl(218, 41%, 30%) 35%,
                        hsl(218, 41%, 20%) 75%,
                        hsl(218, 41%, 19%) 80%,
                        transparent 100%),
                    radial-gradient(1250px circle at 100% 100%,
                        hsl(218, 41%, 45%) 15%,
                        hsl(218, 41%, 30%) 35%,
                        hsl(218, 41%, 20%) 75%,
                        hsl(218, 41%, 19%) 80%,
                        transparent 100%);
            }

            #radius-shape-1 {
                height: 220px;
                width: 220px;
                top: -60px;
                left: -130px;
                background: radial-gradient(#44006b, #ad1fff);
                overflow: hidden;
            }

            #radius-shape-2 {
                border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
                bottom: -60px;
                right: -110px;
                width: 300px;
                height: 300px;
                background: radial-gradient(#44006b, #ad1fff);
                overflow: hidden;
            }

            .bg-glass {
                background-color: hsla(0, 0%, 100%, 0.9) !important;
                backdrop-filter: saturate(200%) blur(25px);
            }
        </style>

        <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
            <div class="row gx-lg-5 align-items-center mb-5">
                <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                    <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
                        The best offer <br />
                        <span style="color: hsl(218, 81%, 75%)">for your business</span>
                    </h1>
                    <p class="mb-4 opacity-70" style="color: hsl(218, 81%, 85%)">
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                        Temporibus, expedita iusto veniam atque, magni tempora mollitia
                        dolorum consequatur nulla, neque debitis eos reprehenderit quasi
                        ab ipsum nisi dolorem modi. Quos?
                    </p>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                    <div class="card bg-glass">
                        <div class="card-body px-4 py-5 px-md-5">
                            <form method="POST" id="signinForm">
                                @csrf
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="form3Example3">Email address<span
                                            class="text-danger">*</span></label>
                                    <input type="email" id="form3Example3" name="email" class="form-control" />
                                    <div class="text-danger mt-1 validation-error" id="error-email"></div>
                                </div>
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <label class="form-label" for="form3Example4">Password<span
                                            class="text-danger">*</span></label>
                                    <input type="password" id="form3Example4" name="password" class="form-control" />
                                    <div class="text-danger mt-1 validation-error" id="error-password"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-primary">
                                        Sign in
                                    </button>
                                    <span>
                                        No account? <a href="{{ route('get.signup.form') }}">Sign up</a>
                                    </span>
                                </div>
                                <div class="text-center">
                                    <p>or sign in with:</p>
                                    <button type="button" data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-facebook-f"></i>
                                    </button>
                                    <a href="{{ route('auth.google') }}"><button type="button" data-mdb-button-init
                                            data-mdb-ripple-init class="btn btn-link btn-floating mx-1">
                                            <i class="fab fa-google"></i>
                                        </button>
                                    </a>
                                    <button type="button" data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-twitter"></i>
                                    </button>
                                    <button type="button" data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-link btn-floating mx-1">
                                        <i class="fab fa-github"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("form").on("submit", function(e) {
                e.preventDefault();
                $(".validation-error").html("");
                $.ajax({
                    url: "{{ route('signin.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = response.redirect;
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $("#error-" + key).html(value[0]);
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
