<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>User Management</title>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">
            <style>
                body {
                    background-color: #f8f9fa;
                }
                .container {
                    margin-top: 50px;
                }
                .modal-header {
                    background-color: #007bff;
                    color: white;
                }
                .btn-primary, .btn-success, .btn-secondary {
                    margin-right: 10px;
                }
                .btn-danger {
                    margin-left: 10px;
                }
                .file-upload {
                    display: flex;
                    align-items: center;
                }
                .file-upload input[type="file"] {
                    display: none;
                }
                .file-upload label {
                    margin: 0;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>User Management</h2>
                    <div class="d-flex">
                        <div>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#userModal" id="addUserBtn">
                                <i class="fas fa-user-plus"></i> Add User
                            </button>
                        </div>
                        <div class="ml-3">
                            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <input type="file" name="file" class="form-control-file" required>
                                </div>
                                <div class="ml-3">
                                <button type="submit" class="btn btn-primary mt-2" id="reloadBtn">Upload</button>
                                </div>
                                <div class="ml-3">
                                <button type="button" class="btn btn-secondary ml-2" id="reloadBtn">Reload Table</button>
                                </div>
                            </form>
                            </div>
                            </div>
                        <div id="messages" class="ml-3"></div>
                

                <div id="userList">
                    <table id="userTable" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Mobile Number</th>
                                <th>PAN Number</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          {{-- AJAX Table --}}
                        </tbody>
                    </table>
                </div>
            </div>

           {{-- User Create Model --}}
           <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Add/Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="userFormDetails">
                            @csrf
                            <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input type="text" class="form-control" id="first_name" name="firstname" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" name="lastname" required>
                            </div>
                            <div class="form-group">
                                <label for="full_name">Full Name:</label>
                                <input type="text" class="form-control" id="full_name" name="fullname" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email address:</label>
                                <input type="email" class="form-control" id="email" name="emailid" required>
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number:</label>
                                <input type="text" class="form-control" id="mobile_number" name="mobileno" pattern="[0-9]{10}" title="Enter 10-digit mobile number" required>
                            </div>
                            <div class="form-group">
                                <label for="pan_number">PAN Number:</label>
                                <input type="text" class="form-control" id="pan_number" name="pan_no" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="Enter valid PAN card number (e.g., ABCDE1234F)" required>
                            </div>
                            <button type="submit" class="btn btn-success" id="reloadBtn">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

            {{-- User Edit Model --}}
            <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm">
                                @csrf
                                <input type="hidden" id="editUserId" name="user_id">
                                <div class="form-group">
                                    <label for="editFirstName">First Name</label>
                                    <input type="text" class="form-control" id="editFirstName" name="firstname" required>
                                </div>
                                <div class="form-group">
                                    <label for="editLastName">Last Name</label>
                                    <input type="text" class="form-control" id="editLastName" name="lastname" required>
                                </div>
                                <div class="form-group">
                                    <label for="editFullName">Full Name:</label>
                                    <input type="text" class="form-control" id="editFullName" name="fullname" required>
                                </div>
                                <div class="form-group">
                                    <label for="editEmail">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="emailid" required>
                                </div>
                                <div class="form-group">
                                    <label for="editMobileNumber">Mobile Number:</label>
                                    <input type="text" class="form-control"  id="editMobileNumber" name="mobileno" pattern="[0-9]{10}" title="Enter 10-digit mobile number" required>
                                </div>
                                <div class="form-group">
                                    <label for="editPanNumber">PAN Number:</label>
                                    <input type="text" class="form-control" id="editPanNumber" name="pan_no" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="Enter valid PAN card number (e.g., ABCDE1234F)" required>
                                </div>
                                <button type="submit" class="btn btn-primary" id="reloadBtn">Save changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

            <script>
                $(document).ready(function () {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var table = $('#userTable').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
                        ajax: {
                            url: '{{ route('users.index') }}',
                            dataSrc: ''
                        },
                        columns: [
                            { data: 'firstname' },
                            { data: 'lastname' },
                            { data: 'fullname' },
                            { data: 'emailid' },
                            { data: 'mobileno' },
                            { data: 'pan_no' },
                            {
                                data: 'id',
                                render: function(data, type, row) {
                                    return `
                                        <button class="btn btn-info editUserBtn" data-id="${data}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger deleteUserBtn" data-id="${data}"><i class="fas fa-trash-alt"></i></button>
                                    `;
                                }
                            }
                        ]
                    });

                    // Form submission
                    $('#userFormDetails').submit(function (e) {
                        e.preventDefault();
                        const formData = $(this).serialize();
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('users.store') }}',
                            data: formData,
                            success: function (response) {
                                alert('User saved successfully');
                                $('#userModal').modal('hide');
                                table.ajax.reload();
                            },
                            error: function (response) {
                                alert('Error saving user');
                            }
                        });
                    });

                    // Bulk upload

                    $('#uploadForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: '{{ route("users.import") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#messages').html('<div class="alert alert-success">' + response.success + '</div>');
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorHtml = '<div class="alert alert-danger"><ul>';
                    $.each(errors, function(index, error) {
                        errorHtml += '<li>' + error + '</li>';
                    });
                    errorHtml += '</ul></div>';
                    $('#messages').html(errorHtml);
                }
            });
        });

        $('#reloadBtn').click(function() {
            $('#userTable').DataTable().ajax.reload();
        });

                  // Edit user
        $(document).on('click', '.editUserBtn', function () {
            var userId = $(this).data('id');
            $.ajax({
                url: '/users/' + userId,
                type: 'GET',
                success: function(user) {
                    $('#editUserId').val(user.id);
                    $('#editFirstName').val(user.firstname);
                    $('#editLastName').val(user.lastname);
                    $('#editFullName').val(user.fullname);
                    $('#editEmail').val(user.emailid);
                    $('#editMobileNumber').val(user.mobileno);
                    $('#editPanNumber').val(user.pan_no);
                    $('#editUserModal').modal('show');
                },
                error: function(xhr) {
                    var errorHtml = '<div class="alert alert-danger">Error fetching user data.</div>';
                    $('#messages').html(errorHtml);
                }
            });
        });

        // Update user
        $('#editUserForm').on('submit', function(e) {
            e.preventDefault();
            var userId = $('#editUserId').val();
            var formData = $(this).serialize();

            $.ajax({
                url: '/users/' + userId,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    $('#editUserModal').modal('hide');
                    $('#messages').html('<div class="alert alert-success">' + response.success + '</div>');
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorHtml = '<div class="alert alert-danger"><ul>';
                    $.each(errors, function(index, error) {
                        errorHtml += '<li>' + error + '</li>';
                    });
                    errorHtml += '</ul></div>';
                    $('#messages').html(errorHtml);
                }
            });
        });
                    // Delete user
                    $(document).on('click', '.deleteUserBtn', function () {
                        var userId = $(this).data('id');
                        if (confirm('Are you sure you want to delete this user?')) {
                            $.ajax({
                                type: 'DELETE',
                                url: '{{ route('users.destroy', '') }}/' + userId,
                                success: function (response) {
                                    alert('User deleted successfully');
                                    table.ajax.reload();
                                },
                                error: function (response) {
                                    alert('Error deleting user');
                                }
                            });
                        }
                    });
                });
            </script>
        </body>
        </html>
    </div>
</x-app-layout>
