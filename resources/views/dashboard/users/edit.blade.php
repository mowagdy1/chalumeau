@extends('layouts.masterDashboard')

@section('title','Edit Profile')

@section('path')
    <li><a href="{{ url('dashboard/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Edit Profile</li>
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
                        <h4 id="messageBody"></h4>
                    </div>
                    <!-- Ajax Messages - End -->


                    @if(count($errors)>0)
                        <div class="alert alert-danger" dir="ltr">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Ajax Validation Errors - Start -->
                        <div id="errorsDiv" class="alert alert-danger" dir="ltr" style="display: none">
                            <ul id="errorsUl">

                                <li></li>

                            </ul>
                        </div>
                        <!-- Ajax Validation Errors - Start -->


                        <div class="box-body">
                        <form method="post" action="{{ url('dashboard/users/edit') }}" id="editUser">

                            {{ csrf_field() }}
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" value="{{ $user->name }}" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select name="role" id="role" class="form-control" required>
                                            <option>{{ $user->role=='admin'?'admin':'user' }}</option>
                                            <option>{{ $user->role=='admin'?'user':'admin' }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" value="{{ $user->email }}" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">New Password</label>
                                        <input type="password" value="" class="form-control" id="password" name="password">
                                    </div>
                                </div>

                            </div>

                            <div class="box-footer">
                                <button type="button" onclick="editUser({{ $user->id }})" class="btn btn-info pull-right">Update Data</button>
                            </div>

                        </form>
                    </div>


                </div>
                <!-- /.box-body -->

            </div>
            <!-- /.box -->

        </div>
    </div>
@endsection

@section('script')
    <script>
        // Session Message
        $('#message').delay(5000).fadeOut('slow');

        // Ajax Message - Start
        var editUrl = '{{ route('editUser') }}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function editUser(id) {
            // Get values
            var name =$('#name').val();
            var email =$('#email').val();
            var role =$('#role').val();
            var password =$('#password').val();

            $.ajax({
                type: 'POST',
                url: editUrl,
                data: {
                    userId: id,
                    name: name,
                    email: email,
                    role: role,
                    password: password,
                },
                success: function (msg) {
                    console.log(msg['msg']);
                    // Message of the result
                    $('#messageBody').html(msg['msg']);
                    $('#messageDiv').attr({
                        'class': 'alert alert-dismissible ' + msg['class'],
                        'style': 'display: block;'
                    }).delay(5000).fadeOut('slow');
                },
                error: function (data) {
                    console.log(data['msg']);
                    // Render the errors with js ...
                    var errors = $.parseJSON(data.responseText);
                    console.log(errors);
                    // Fetch errors
                    $('#errorsUl').html(' ');
                    for (var i=0;i<errors['errors'].length;i++){
                        $('#errorsUl').append('<li>'+errors['errors'][i]+'</li> ');
                    }
                    $('#errorsDiv').attr({
                        'style': 'display: block;'
                    })
                }
            });

        }
        // Ajax Message - End
    </script>
@stop