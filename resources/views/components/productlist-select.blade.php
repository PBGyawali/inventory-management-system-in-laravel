
        <span class="item_details" id="item_details_row{{$count??''}}">
            <div class="row">
                <div class="col-sm-7">
                    <select name="product_id[]"
                    id="product_id{{$count??''}}"
                    data-count="{{$count??''}}"
                    class="form-control
                    @if (isset($max))
                        selectpicker
                    @endif"
                    data-live-search="true" required>
                        {!! $select_menu !!}
                    </select>
                </div>
                <div class="col-sm-2 px-0">
                    <input
                        type="number"
                        name="discount[]"
                        min="0"
                        value="{{$discount??''}}"
                        data-count="{{$count??''}}"
                        id="discount{{$count??''}}"
                        class="form-control"
                    />
                </div>
                <div class="col-sm-2 px-0">
                    <input
                        type="number"
                        name="quantity[]"
                        min="0"
                        data-count="{{$count??''}}"
                        id="quantity{{$count??''}}"
                        class="form-control
                        @if (isset($max))
                            quantitypicker
                        @endif
                        "
                        value="{{$quantity??''}}"
                        @if (isset($max))
                        data-max="{{$max}}"
                            max="{{$max}}"
                        @endif
                        required
                    />
                </div>
                <aside class="col-sm-1 pl-0">
                    @empty($count)
                        <button type="button"
                        data-element="{{$element??'sales'}}_order"
                        class="btn btn-success add_more">+</button>
                    @else
                        <button type="button" id="{{$count??''}}" class="btn btn-danger remove">-</button>
                    @endempty
                </aside>
            </div>
        </span>
