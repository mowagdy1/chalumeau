@extends('layouts.masterDashboard')

@section('title','Users')

@section('path')
    <li><a href="{{ url('dashboard/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Users</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">

            <div class="box">

                <div class="box-body">

                    @if(session('message'))
                        <div id="message" class="alert {{ session('class') }} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>
                                <i class="icon fa {{ session('class')=='alert-success'?'fa-check':'fa-ban' }}"></i> {{ session('message') }}
                            </h4>
                        </div>
                    @endif
                <!-- Ajax Messages - Start -->
                    <div id="messageDiv" class="" style="display: none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4 id="messageBody">

                        </h4>
                    </div>
                    <!-- Ajax Messages - End -->

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                        Add New User
                    </button>

                    <table id="tableData" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody id="tbodyData">
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <label class="btn btn-default"><a
                                                href="{{ url('dashboard/users/'.$user->id.'/edit') }}">Edit</a></label>
                                    <button class="btn btn-danger" onclick="deleteUser(this)" data-id="{{ $user->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>

                    {{ $users->links() }}

                </div>
                <!-- /.box-body -->

            </div>
            <!-- /.box -->

        </div>
    </div>




    <!-- Add User Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add New User</h4>
                </div>
                <div class="modal-body">

                    @if(count($errors)>0)

                        <div class="alert alert-danger" dir="ltr">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ url('dashboard/users/add') }}" id="addUser"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Password Confirmation</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                           name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select name="role" id="role" class="form-control" required>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" form="addUser" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    @if(count($errors)>0)
        <script type="text/javascript">$('#myModal').modal('show');</script>
    @endif

    <script>
        // Session Message
        $('#message').delay(5000).fadeOut('slow');

        // Ajax Message - Start
        var deleteUrl = '{{ route('deleteUser') }}';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function deleteUser(user) {
            var id = $(user).data('id');

            $.ajax({
                type: 'POST',
                url: deleteUrl,
                data: {userId: id}
            }).done(function (msg) {
                console.log(msg['msg']);
                // Remove the deleted row from the table
                if (msg['class'] == 'alert-success') {
                    $(user).closest('tr').remove();
                }
                // Message of the result
                $('#messageBody').html(msg['msg']);
                $('#messageDiv').attr({
                    'class': 'alert alert-dismissible ' + msg['class'],
                    'style': 'display: block;'
                }).delay(5000).fadeOut('slow');
            });

        }
        // Ajax Message - End
    </script>
@stop