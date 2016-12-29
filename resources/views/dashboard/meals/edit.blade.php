@extends('layouts.masterDashboard')

@section('title','Edit Meal')

@section('path')
    <li><a href="{{ url('dashboard/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li>Meals</li>
    <li class="active">Edit Meal</li>
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
                    <!-- Ajax Validation Errors - End -->

                    <div class="box-body">
                        <form method="post" action="{{ url('dashboard/meals/edit') }}" id="editMeal" enctype="multipart/form-data">

                            {{ csrf_field() }}
                            <input type="hidden" name="mealId" value="{{ $meal->id }}">
                            <div class="row">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                   value="{{ $meal->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select name="category_id" id="category" class="form-control" required>
                                                <option value="{{ $meal->category->id }}">{{ $meal->category->name}}</option>
                                                @foreach($categories as $category)
                                                    @if($meal->category_id!=$category->id)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="current">Current image</label>
                                            <img src="{{ asset(imgJuice($meal->image)) }}" alt="..." id="current"
                                                 class="img-rounded" style="width: 100px; height: auto">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image">New Image
                                                <small>(max width=400px & max height=400px)</small>
                                            </label>
                                            <input type="file" class="form-control" id="image" name="image">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price_small">Price of small</label>
                                            <input type="text" class="form-control" id="price_small"
                                                   value="<?php foreach ($meal->sizes as $size) {
                                                       echo $size->size == 'small' ? $size->price : '';
                                                   } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price_medium">Price of medium</label>
                                            <input type="text" class="form-control" id="price_medium"

                                                   value="<?php foreach ($meal->sizes as $size) {
                                                       echo $size->size == 'medium' ? $size->price : '';
                                                   } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price_large">Price of large</label>
                                            <input type="text" class="form-control" id="price_large"
                                                   value="<?php foreach ($meal->sizes as $size) {
                                                       echo $size->size == 'large' ? $size->price : '';
                                                   } ?>">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="box-footer">
                                <!-- <button type="button" onclick="editMeal({{ $meal->id }})"
                                        class="btn btn-info pull-right">Update Data
                                </button> -->

                                <button type="button" id="buttonUpdate" class="btn btn-info pull-right">Update</button>
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
        var editUrl = '{{ route('editMeal') }}';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#buttonUpdate').on('click', function(){
            var form=$(this).closest('form');
            var formData = false;
            if (window.FormData){
                formData = new FormData(form[0]);
            }
            // Add values to the formData
            if($('#image')[0].files[0]){
                formData.append("image",$('#image')[0].files[0]);
            }
            formData.append("name",$('#name').val());
            formData.append("category",$('#category').val());
            formData.append("price_small",$('#price_small').val());
            formData.append("price_medium",$('#price_medium').val());
            formData.append("price_large",$('#price_large').val());
            console.log(formData);

            // Start AJAX
            $.ajax({
                type: 'POST',
                url: editUrl,
                data: formData ? formData : form.serialize(),
                processData: false,
                contentType: false,
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

        });
        // Ajax Message - End
    </script>
@stop