@extends('layouts.masterDashboard')

@section('title','Carts')

@section('path')
    <li><a href="{{ url('dashboard/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Carts</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">

            <div class="box">

                <div class="box-body">

                    @if(session('message'))
                        <div class="alert {{ session('class') }} alert-dismissible">
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

                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Order</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($carts as $cart)
                            <tr>
                                <td>{{ $cart->user->name }}</td>
                                <td>
                                    @foreach($cart->items as $item)
                                        [ {{ $item->quantity }}
                                        {{ $item->size->size }}
                                        {{ $item->size->meal->name }} ]
                                    @endforeach
                                </td>
                                <td>
                                    <?php
                                    $totalPrice=0;
                                        foreach ($cart->items as $item){
                                        $totalPrice=$totalPrice+$item->size->price;
                                        }
                                    ?>
                                    {{ $totalPrice }}
                                </td>

                                <td>
                                    <button class="btn btn-danger" onclick="deleteCart(this)"
                                            data-id="{{ $cart->id }}">Delete </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Order</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>

                    {{ $carts->links() }}

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
        var deleteUrl = '{{ route('deleteCart') }}';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function deleteCart(cart) {
            var id = $(cart).data('id');

            $.ajax({
                type: 'POST',
                url: deleteUrl,
                data: {cartId: id}
            }).done(function (msg) {
                console.log(msg['msg']);
                // Remove the deleted row from the table
                if (msg['class'] == 'alert-success') {
                    $(cart).closest('tr').remove();
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