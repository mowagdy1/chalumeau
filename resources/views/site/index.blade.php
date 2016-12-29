@extends('layouts.masterSite')

@section('content')
    <div class="container">

        @if(session('message'))
            <div id="message" class="alert {{ session('class') }} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>
                    <i class="icon fa {{ session('class')=='alert-success'?'fa-check':'fa-ban' }}"></i> {{ session('message') }}
                </h4>
            </div>
        @endif

        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Cart</div>
                    <div class="panel-body">
                        <form method="post" action="{{ url('order') }}" id="formOrder">
                            {{ csrf_field() }}
                            <input id="theOrder" type="hidden" name="theOrder" value="">
                            <div id="cartBody"></div>

                            <div class="panel-footer">
                                <button type="button" class="btn btn-success" id="submitOrder">Order Now!</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Meals</div>
                    <div class="panel-body">

                        <!-- The Collapse - Start -->

                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php $itemId = 0; ?>
                            @foreach($categories as $category)
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="{{ 'heading'.$category->id }}">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#{{ 'collapse'.$category->id }}"
                                               aria-expanded="false" aria-controls="{{ 'collapse'.$category->id }}">
                                                {{ $category->name }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="{{ 'collapse'.$category->id }}" class="panel-collapse collapse"
                                         role="tabpanel" aria-labelledby="{{ 'heading'.$category->id }}">
                                        <div class="panel-body">

                                            @foreach($category->meals as $meal)
                                                <div class="row">
                                                    <div>
                                                        <div class="col-md-2">
                                                            <input type="submit" data-id="{{ $itemId }}"
                                                                   class="btn send btn-primary" value="Add to cart"
                                                                   data-name="{{ $meal->name }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select name="size_id" id="va{{ $itemId }}"
                                                                    class="form-control size">
                                                                @foreach($meal->sizes as $size)
                                                                    <option value="{{ $size->id }}">{{ $size->size }} {{ '('.$size->price.' L.E.)' }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <h3 style="text-align: right;">{{ $meal->name }}</h3>
                                                        <h5 style="text-align: right;">{{ $meal->description }}</h5>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <img src="{{ asset(imgJuice($meal->image)) }}" alt="..."
                                                             class="img-rounded" style="width: 100px; height: auto">
                                                    </div>

                                                </div>

                                                <?php $itemId++; ?>
                                            @endforeach


                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <!-- The Collapse - End -->

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        var itemsList = {};

        $(document).ready(function () {
            // Add item to cart
            $('.send').click(function () {
                // Get 'size' id
                var id = $(this).data('id');
                var sizeId = $('#va' + id).val();
                var quantity = 1;

                // Ajax to get the meal details //
                // [[ There are other easier ways to get it but I used Ajax ]] //
                var getMeal = '{{ route('getMealFromSize') }}';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: getMeal,
                    data: {
                        sizeId: sizeId,
                    },
                    success: function (data) {
                        console.log(data['msg']);
                        var size = data['data']['size'];
                        var price = data['data']['price'];
                        var meal = data['data']['meal']['name'];
                        // Add to the itemsList
                        itemsList[data['data']['id']] = [data['data']['id'],quantity];
                        console.log(JSON.stringify(itemsList));
                        // Add to the cart in front-end
                        $('#cartBody').append('<div><a href="#" data-sizeid="' + sizeId + '" class="item">X</a> ' + meal + ' [' + size + ' - ' + price + 'L.E.] </div>');
                    },
                    error: function (data) {
                        console.log(data['msg']);
                        $('#cartBody').append('<div><a href="#" class="item">X</a> Error! This item has no sizes in database.</div>');
                    }
                });

            });

            // Remove item from cart
            $(document).on('click', '.item', function () {
                // Remove from itemsList
                var removedSizeId = $(this).data('sizeid');
                delete itemsList[removedSizeId];
                console.log(JSON.stringify(itemsList));
                // Remove from front-end
                $(this).parent().remove();
            });

            $('#submitOrder').click(function () {
                // Converting the object of itemsList to an array
                var orderItems = $.map(itemsList, function(value, index) {
                    return [value];
                });
                // put the items in theOrder and send it
                $('#theOrder').val(orderItems);
                $('#formOrder').submit();
            });

        });
    </script>
@stop