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
<?php
if($results->isEmpty()){
    echo '<h1 class="text-uppercase w-100 text-center position-absolute px-4 inset-1/2"style="
     transform: translate(-50%, -50%);">no results found</h1>';
return;
}?>

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
					<td>{{$result->{$table.'_date'} }}</td>
					<td><?=$result->{$table.'_name'}?></td>
					<td><?=$result->{$table.'_address'}?></td>
                    <td>{{$result->payment_status}}</td>
                    @if($table=='sale')
                        <td>{{$result->{$table.'_tax'} }}</td>
                    @endif
                    <td>{{$result->{$table.'_sub_total'} }}</td>
				</tr>
			@endforeach
			<tr class="text-center  bg-dark text-light">
				<td colspan="{{($table=='sale'?'5 ':'4')}}">Total amount <?= ($table=='sale'?'together with tax ':''). '('.$info->currency_symbol.')';?></td>
				<td  colspan="1">{{$totalAmount}}</td>
			</tr>
	</table>
</body>
