
        <span class="item_details" id="row{{$count}}">
            <input
                type="hidden"
                name="hidden_product_id[]"
                id="hidden_product_id{{$count}}"
                value="{{$product_id}}"
            />
            <input
                type="hidden"
                name="hidden_quantity[]"
                id="hidden_quantity{{$count}}"
                value="{{$quantity}}"
            />
            <div class="row" id="item_details_row{{$count}}">
                <div class="col-md-8">
                    <select name="product_id[]" id="product_id{{$count}}" class="form-control " data-live-search="true" required>
                        {!! $select_menu !!}
                    </select>
                </div>
                <div class="col-md-3 px-0">
                    <input
                        type="number"
                        name="quantity[]"
                        class="form-control"
                        value="{{$quantity}}"
                        @if (isset($max))
                            max="{{$max}}"
                        @endif
                        required
                    />
                </div>
                <aside class="col-md-1 pl-0">
                    @if($count == '')
                        <button type="button" id="add_more" class="btn btn-success add-more">+</button>
                    @else
                        <button type="button" id="{{$count}}" class="btn btn-danger remove">-</button>
                    @endif
                </aside>
            </div>
        </span>