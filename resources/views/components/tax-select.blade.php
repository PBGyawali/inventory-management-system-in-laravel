<span id="item_details_row{{$count}}" class="item_details">
    <div class="row">
        <div class="col-md-11 pr-0">
            <select name="tax[]" id="tax{{$count}}" class="form-control" data-live-search="true" >
                @php
                echo $select_menu;
                @endphp
            </select>
        </div>
        <div class="col-md-1 pl-0">
        @if($count== '')
            <button type="button" name="add_more" id="add_more" class="btn btn-success">+</button>
        @else
            <button type="button" name="remove" id="{{$count}}" class="btn btn-danger remove">-</button>
        @endif

            </div>
        </div>
    </div>
</span>