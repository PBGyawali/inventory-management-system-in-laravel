<div class="card border-left-primary shadow h-100 py-2">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-{{ $class??'success'}} text-uppercase mb-1">
                     {{$title??''}}
                </div>
                <div class="h5 mb-0 font-weight-bold text-center">{{ $value??'No value' }}</div>
            </div>
            <div class="col-auto">
                <i class="fas {{ $icon??'fa-clipboard-list'}} fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>
</div>