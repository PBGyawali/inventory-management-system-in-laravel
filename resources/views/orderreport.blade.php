<!DOCTYPE html>
		<html>
		<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible' />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
		<title>{{$page}}</title>
</head>
<body>
@if($results->isEmpty())
    <div class="row d-flex align-items-center min-vh-100">
        <div class="col-12">
            <h1 class='text-center text-info'>
                No results found
            </h1>
        </div>
    </div>
@else
            <h2 class="text-center text-warning bg-secondary">
                <?= $tables?> Order Report Between {{(!empty($from_date)?$from_date:'(No start date given)')}}
                and  {{(!empty($to_date)?$to_date:'(No end date given)')}}
            </h2>
            <table class="table w-100 border-2  " >
                <tr class="text-center border-2 bg-dark text-white">
                    <th class="">Order Date</th>
                    <th class="">Client Name</th>
                    <th class="">Client Address</th>
                    <th class="">Payment method</th>
                    @if($table=='sale')
                        <th class=""> Tax (<?=$info->currency_symbol?>)</th>
                    @endif
                    <th class=""> Total <?=($table=='sale'?' before tax ':'').'('.$info->currency_symbol?>)</th>
                </tr>
                    @foreach ($results as $key=>$result)
                    <tr class="text-center border-2 ">
                            <td>{{$result->date }}</td>
                            <td><?=$result->name?></td>
                            <td><?=$result->address?></td>
                            <td>{{$result->payment_status}}</td>
                            @if($table=='sale')
                                <td>{{$result->tax }}</td>
                            @endif
                            <td>{{$result->sub_total }}</td>
                        </tr>
                    @endforeach
                    <tr class="text-center  bg-dark text-light">
                        <td colspan="{{($table=='sale'?'5 ':'4')}}">Total Amount <?= ($table=='sale'?'together with tax ':''). '('.$info->currency_symbol.')';?></td>
                        <td  colspan="1">{{$totalAmount}}</td>
                    </tr>
            </table>
        @endif
</body>

