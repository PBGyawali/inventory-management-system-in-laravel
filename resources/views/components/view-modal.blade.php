<div class="table-responsive">
    <table class="table table-bordered">
        @foreach ($viewdatas as $key => $viewdata )
        <tr>
            @if($key=='Status')
            <td>{{ucwords($key)}}</td>
            <td><span class="badge badge-{{$viewdata['class']}}">{{ $viewdata['value'] }}</span></td>
            @else
            <td>{{ucwords($key)}}</td>
            <td>{{ucwords($viewdata)}}</td>
            @endif
        </tr>
        @endforeach
    </table>
</div>
