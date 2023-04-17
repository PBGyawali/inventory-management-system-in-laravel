<div class="card shadow border-left-info py-2">
    <div class="card-body">
        <div class="row align-items-center no-gutters">
            <div class="col mr-2">
                <div class="text-uppercase text-info font-weight-bold text-xs mb-1"><span>{{$heading}}</span></div>
                <div class="row no-gutters align-items-center">
                    <div class="col value-indicator">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" id ="progressbar_{{$count??1}}" aria-valuenow="{{$value}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$value}}%;"><span class="sr-only">{{$value}}%</span></div>
                        </div>
                    </div>
                    <div class="col-auto value-indicator-text">
                        <div class="text-dark text-right font-weight-bold h5 mb-0 ml-3"><span class="progress-value">{{$value}}%</span></div>
                    </div>
                </div>
            </div>
            <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
        </div>
    </div>
</div>