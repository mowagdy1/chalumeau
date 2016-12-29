@extends('layouts.masterDashboard')

@section('title','Settings')

@section('path')
    <li><a href="{{ url('dashboard/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Settings</li>
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


                    @if(count($errors)>0)

                        <div class="alert alert-danger" dir="ltr">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="box-body">
                        <form method="post" action="{{ url('dashboard/settings/edit') }}" id="editSetting"
                              enctype="multipart/form-data">

                            {{ csrf_field() }}
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Site Name</label>
                                        <input type="text" value="{{ $setting->site_name }}" class="form-control" id="name" name="site_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="facebook">Facebook</label>
                                        <input type="text" value="{{ $setting->facebook }}" class="form-control" id="facebook" name="facebook">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="twitter">Twitter</label>
                                        <input type="text" value="{{ $setting->twitter }}" class="form-control" id="twitter" name="twitter">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="about">About</label>
                                        <textarea class="form-control" id="about" name="about">{{ $setting->about }}</textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right">Update Data</button>
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
        var deleteUrl = '{{ route('deleteMeal') }}';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function deleteMeal(meal) {
            var id = $(meal).data('id');

            $.ajax({
                type: 'POST',
                url: deleteUrl,
                data: {mealId: id}
            }).done(function (msg) {
                console.log(msg['msg']);
                // Remove the deleted row from the table
                if (msg['class'] == 'alert-success') {
                    $(meal).closest('tr').remove();
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