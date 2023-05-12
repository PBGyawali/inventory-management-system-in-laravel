<span id="item_details_row{{$count??''}}" class="item_details">
    <div class="row">
        <div class="col-md-11 pr-0">
            <select name="tax[]" id="tax{{$count??''}}" class="form-control" data-live-search="true" >
                {!! $select_menu !!}
            </select>
        </div>
        <div class="col-md-1 pl-0">
            @empty($count)
                <button type="button" data-element="product" class="btn btn-success add_more">+</button>
            @else
                <button type="button" id="{{$count}}" class="btn btn-danger remove">-</button>
            @endempty
        </div>
    </div>
</span>
