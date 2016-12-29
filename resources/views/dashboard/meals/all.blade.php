@extends('layouts.masterDashboard')

@section('title','Meals')

@section('path')
    <li><a href="{{ url('dashboard/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Meals</li>
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
                        Add New Meal
                    </button>

                    <table id="tableData" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody id="tbodyData">
                        @foreach($meals as $meal)
                            <tr>
                                <td>{{ $meal->name }}</td>
                                <td>{{ $meal->category->name }}</td>
                                <td>{{ $meal->description }}</td>
                                <td>
                                    <label class="btn btn-default"><a
                                                href="{{ url('dashboard/meals/'.$meal->id.'/edit') }}">Edit</a></label>
                                    <button class="btn btn-danger" onclick="deleteMeal(this)"
                                            data-id="{{ $meal->id }}">Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>

                    {{ $meals->links() }}

                </div>
                <!-- /.box-body -->

            </div>
            <!-- /.box -->

        </div>
    </div>



    <!-- Add Meal Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add New Meal</h4>
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

                    <form method="post" action="{{ url('dashboard/meals/add') }}" id="addMeal"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Image
                                        <small>(max width=400px & max height=400px)</small>
                                    </label>
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select name="category_id" id="category" class="form-control" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price_small">Price of small</label>
                                    <input type="text" class="form-control" id="price_small" value="{{ null }}" name="price_small">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price_medium">Price of medium</label>
                                    <input type="text" class="form-control" id="price_medium" value="{{ null }}" name="price_medium">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price_large">Price of large</label>
                                    <input type="text" class="form-control" id="price_large" value="{{ null }}" name="price_large">
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" form="addMeal" class="btn btn-primary">Submit</button>
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