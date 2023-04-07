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

        <div class="form-body">
            <div class="row">
                <div class="form-holder">
                     @include('db_manager::include.flash-message')
                <span class="info-para">Must Read: for Example If you've selected a table [transaction] and permissions [INSERT, SELECT] then after executing the process you dont have [UPDATE, DELETE] Permission</span>
                    <div class="form-content">
                        <div class="form-items">
                            <h3>Select The Details Below</h3>
                            <p>Select the table and permissions for that individual table</p>
                            <form onsubmit="return validateForm()" class="requires-validation" action="{{route('db-manager.step_2')}}" method="post" id="validateForm">
                                @csrf
                                <div class="col-md-12 mb-4">
                                    <select id="selectBox" class="form-select mt-3" required>
                                        <option selected disabled value="">---select table---</option>
                                        @foreach ($tableNames as $tableName)
                                        <option value="{{$tableName}}">{{ $tableName }}</option>
                                        @endforeach
                                    </select>
                                    <div class="valid-feedback">You selected a Table!</div>
                                    <div class="invalid-feedback">Please select a position!</div>
                                </div>

                                @foreach ($permissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input check_boxes" name="permissions[]" type="checkbox" value="{{ $permission }}">
                                    <label class="form-check-label">{{ $permission }}</label>
                                    <div class="valid-feedback">Please Select Carefully!</div>
                                    <div class="invalid-feedback checkbox_error">Please confirm Carefully</div>
                                </div>
                                @endforeach

                                <div class="form-button mt-3">
                                    <button id="submit" type="submit" class="btn btn-primary">Update</button>
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