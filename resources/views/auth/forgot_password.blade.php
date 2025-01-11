<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="app-url" content="{{ config('app.url') }}">
    <meta name="author" content="">

    <title>{{ config('app.name') }} &middot; Login</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"
        integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous">
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        var appUrl = "{{ config('app.url') }}";
    </script>
</head>

<body class="bg-login-image">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center mt-5">

            <div class="col-xl-10 col-lg-12 col-md-9 mt-5">
                {{-- <img src="" style="width: 500px; height:500px" alt=""> --}}

                <div class="card o-hidden border-0 shadow-lg my-5 mx-2">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 mx-auto">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 mb-1" style="color: rgb(192, 15, 133)">SMARTUNIVERSITY</h1>
                                        <p class="small text-gray-900 mb-4">Forgotten your password?
                                        </p>
                                    </div>
                                    <form id="password-reset-form">
                                        @csrf
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" name="new_password"
                                                class="form-control form-control-sm" autocomplete="new-password"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control form-control-sm"
                                                autocomplete="new-password" required>
                                        </div>
                                        <button class="btn btn-outline-secondary shadow" form="password-reset-form"
                                            type="reset">Clear</button>
                                        <button class="btn btn-outline-primary shadow" form="password-reset-form"
                                            type="submit">Save
                                            Changes</button>
                                        {{-- Feedback --}}
                                        <div>
                                            <div id="change-password-feedback"></div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
            integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            const feedbackHolder = document.getElementById("change-password-feedback");
            feedbackHolder.innerHTML = null;

            const passwordResetForm = document.forms["password-reset-form"];
            passwordResetForm.addEventListener("submit", function(e) {
                let resetForm = new FormData(passwordResetForm);
                // resetForm.append('userid', USERMAIL);
                // resetForm.append('school_code', school_code);
                feedbackHolder.innerHTML = null;
                e.preventDefault();

                feedbackHolder.innerHTML = `
                    <p class="text-info mt-2">
                        <i class="fa fa-exclamation-triangle mr-1 ml-1"></i>
                        Processing please wait...
                    </p>`;

                fetch(`${appUrl}/api/admin/forgot_password`, {
                    method: "POST",
                    body: resetForm,
                }).then(function(res) {
                    return res.json();
                }).then(function(payload) {
                    if (!payload.ok) {
                        feedbackHolder.innerHTML = `
                        <p class="text-danger mt-2">
                            <i class="fa fa-exclamation-triangle mr-1 ml-1"></i>
                            ${payload.msg}
                        </p>`;
                        return;
                    }

                    feedbackHolder.innerHTML = `
                    <p class="text-success mt-2">
                        <i class="fa fa-check mr-1 ml-1"></i>
                        New password successfully sent via email and sms
                    </p>`;
                    passwordResetForm.reset();
                    setTimeout(() => {
                        feedbackHolder.innerHTML = null;
                    }, 2000);
                    return;

                }).catch(function(err) {
                    feedbackHolder.innerHTML = `
                    <p class="text-danger mt-2">
                        <i class="fa fa-exclamation-triangle mr-1 ml-1"></i>
                        An internal error occured.
                    </p>`;
                    return;
                });
            });
        </script>
</body>

</html>
