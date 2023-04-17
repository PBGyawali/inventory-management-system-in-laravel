<div class="btn-group text-center">
    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Menu
    </button>
    <ul class="dropdown-menu dropdown-menu-right" >
    @if (in_array('view', $buttons))
        <li><button type="button" name="view"   id="{{$id}}" data-id="{{$id}}"class="w-100 mb-1 text-center btn btn-warning btn-sm view"><i class="fas fa-eye"></i> View</button></li>
    @endif
      <li><button type="button" name="update" id="{{$id}}" data-id="{{$id}}" data-prefix="{{ucwords($prefix.' '.($extratext??''))}}" class="w-100 mb-1 text-center btn btn-info btn-sm update"><i class="fas fa-edit"></i> Update</button></li>
      <li><button type="button" name="status" id="{{$id}}" data-id="{{$id}}" data-prefix="{{$prefix}}" class="w-100 btn mb-1 btn-{{$status_class??'primary'}} btn-sm status" data-status="{{$status}}">{{$statusbutton??'Change Status'}}</button></li>
    @if (in_array('delete', $buttons))
        <li><button type="button" name="delete" id="{{$id}}" data-id="{{$id}}" class="w-100 btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i> Delete</button></li>
    @endif
    @if (in_array('report', $buttons))
        <li><a href="{{$reporturl}}" id="{{$id}}" target="_blank"  data-id="{{$id}}" class="w-100 mb-1 text-center btn btn-warning btn-sm "><i class="fas fa-eye"></i> View PDF</a></li>
    @endif

    </ul>
  </div>


