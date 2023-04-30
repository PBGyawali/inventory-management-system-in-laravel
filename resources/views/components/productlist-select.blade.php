
        <span class="item_details" id="row{{$count}}">
            <div class="row" id="item_details_row{{$count}}">
                <div class="col-sm-8">
                    <select name="product_id[]"
                    id="product_id{{$count}}"
                    data-count="{{$count}}"
                    class="form-control
                    @if (isset($max))
                        selectpicker
                    @endif"
                    data-live-search="true" required>
                        {!! $select_menu !!}
                    </select>
                </div>
                <div class="col-sm-3 px-0">
                    <input
                        type="number"
                        name="quantity[]"
                        data-count="{{$count}}"
                        id="quantity{{$count}}"
                        class="form-control
                        @if (isset($max))
                            quantitypicker
                        @endif
                        "
                        value="{{$quantity}}"
                        @if (isset($max))
                        data-max="{{$max}}"
                            max="{{$max}}"
                        @endif
                        required
                    />
                </div>
                <aside class="col-sm-1 pl-0">
                    @if(!$count)
                        <button type="button" id="add_more"
                        data-element="{{$element??'sales'}}_order"
                        class="btn btn-success add-more">+</button>
                    @else
                        <button type="button" id="{{$count}}" class="btn btn-danger remove">-</button>
                    @endif
                </aside>
            </div>
        </span>
