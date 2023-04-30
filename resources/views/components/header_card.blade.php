<div class="row">
    <div class="col-12 col-md-4">
        <h3 class="card-title">{{trim(ucwords($element).' '.($name??'List'))}}</h3>
    </div>
    <div class="col-6 col-md-4">
        <div class="row input-daterange">
            @empty($noreport)
            <div class="col-6 px-sm-0">
                <input type="date" name="from_date" id="from_date" class="form-control form-control-sm" placeholder="From Date" />
            </div>
            <div class="col-6 px-sm-0">
                <input type="date" name="to_date" id="to_date" class="form-control form-control-sm" placeholder="To Date" />
            </div>
            @endempty
        </div>
    </div>
    <div class="col-3 col-md-2 px-sm-0 text-center">
        @empty($noreport)
        <button type="button"  title="filter by date" id="filter" class="btn btn-info btn-sm pl-sm-1"><i class="fas fa-filter"></i></button>
        @endempty
        @empty($norefresh)
            <button type="button"  title="Toggle auto refresh. Currently set to true" id="toggle_refresh" class="btn btn-success btn-sm"><i class="fas fa-toggle-on"></i></button>
            <button type="button"  title="refresh" id="refresh" class="btn btn-secondary btn-sm "><i class="fas fa-sync-alt"></i></button>
        @endempty
        @isset($reporturl)
            <button type="button" id="report" title="Get Report" data-url="{{$reporturl}}"class="btn btn-info btn-sm"><i class="fas fa-print"></i></button>
        @endisset
        @isset($exporturl)
            <button type="button" id="export" title="Download CSV report" data-url="{{$exporturl}}"  class="text-success btn btn-sm p-0"><i class="fas fa-file-csv fa-2x"></i></button>
        @endisset
    </div>
    <div class="col-3 col-md-2  text-right">
        @empty($nobutton)
        <button type="button" name="add" id="add_button" data-element="{{trim(ucwords($element.' '.($extratext??'')))}}" data-toggle="modal" data-target="#Modal" class="btn btn-success btn-sm"><i class="fas {{$buttonicon??'fa-plus'}}"></i> Add</button>
        @endempty
    </div>
</div>
