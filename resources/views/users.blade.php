<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <!-- IziToast CSS -->
    <link href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css" rel="stylesheet">

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container-fluid m-2">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <h4>{{@$title ? $title : 'Add User'}}</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="add-user-form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{old('full_name')}}">
                            <span class="text-danger" id="full_name_err"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" value="{{old('email')}}">
                            <span class="text-danger" id="email_err"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="mobile" class="form-control" id="mobile" name="mobile" value="{{old('mobile') }}">
                            <span class="text-danger" id="mobile_err"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="role_id" class="form-label">Role</label>
                            <select class="form-select" id="role_id" name="role_id">
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select></select>
                            <span class="text-danger" id="mobile_err"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                            <span class="text-danger" id="mobile_err"></span>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="profile_image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" value="{{old('profile_iamge')}}">
                            <span class="text-danger" id="mobile_err"></span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Submit</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Users</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Description</th>
                        <th>Role</th>
                    </tr>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>{{ $user->description}}</td>
                        <td>{{ $user->role->name}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <!-- IziToast JS -->
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <script>
        function usersTable() {
            console.log("hello")
            $('.table').DataTable({
                processing: true,
                search: false,
                serverSide: true,
                ajax: "{{ route('users.index') }}", // Fetch data from server-side route
                columns: [{
                        data: null, // Use `null` because the data doesn't come from the server
                        name: 'count',
                        render: function(data, type, row, meta) {
                            // `meta.row` gives the current row index, so we can use it as a sequential number
                            return meta.row + 1; // Add 1 to start the count from 1 instead of 0
                        }
                    },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'role', // The entire role object
                        name: 'role.name', // This refers to the 'role' object
                        render: function(data, type, row) {
                            return data ? data.name : ''; // Access the 'name' property of the role object
                        }
                    },
                ],
                paging: true, // Explicitly enable pagination
                pageLength: 10,
                lengthChange: true, // Allow users to change the number of rows per page
                dom: 'lfrtip',
            });
        }
        usersTable()
        // $(function() {


        $("#add-user-form").validate({
            rules: {
                full_name: {
                    required: true
                },
                email: {
                    required: true
                },
                mobile: {
                    required: true
                },
                description: {
                    required: true
                },
                role_id: {
                    required: true
                }
            },
            submitHandler: function(form) {
                var formData = new FormData(form); // Create FormData object from the form
                $.ajax({
                    url: "{{ route('users.store') }}",
                    data: formData,
                    processData: false, // Don't process the data (jquery will not alter the formData)
                    contentType: false,
                    dataType: "json",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.status) {
                            iziToast.success({
                                title: 'Success',
                                position: 'topRight',
                                messages: `${res.msg}`
                            })
                            window.location.reload()
                        } else {
                            iziToast.error({
                                title: 'Error',
                                position: 'topRight',
                                messages: `${res.msg}`
                            })
                        }
                    }
                })
            }
        })
        // })
    </script>
</body>

</html>