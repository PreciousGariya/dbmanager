<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- // add bootstrap 5 js file -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Styles -->
    @include('db_manager::include.style')

</head>

<body class="antialiased">

    <div class="container">
        @include('db_manager::include.flash-message')
        <div class="form-body">
            <div class="row">
                <div class="form-holder">
                    <div class="form-content">
                        <div class="form-items">
                            <h3>Login to Databse Manager</h3>
                            <p>Fill in the credentials below</p>
                            <form method="post" action="{{route('db-manager.step_1_post')}}" class="requires-validation" novalidate>
                                @csrf
                                <div class="col-md-12">
                                    <input class="form-control" type="text" name="user" placeholder="E-mail Address" required>
                                    <div class="valid-feedback">Username field is valid!</div>
                                    <div class="invalid-feedback">Username field cannot be blank!</div>
                                    @if ($errors->has('user'))
                                    <div class="invalid-feedback">{{ $errors->first('user') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                                    <div class="valid-feedback">Password field is valid!</div>
                                    <div class="invalid-feedback">Password field cannot be blank!</div>
                                    @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                                <!-- <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                    <label class="form-check-label">I confirm that all data are correct</label>
                                    <div class="invalid-feedback">Please confirm that the entered data are all correct!</div>
                                </div> -->
                                <div class="form-button mt-3">
                                    <button id="submit" type="submit" class="btn btn-primary">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</body>
@include('db_manager::include.script')

</html>