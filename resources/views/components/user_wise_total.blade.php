    @foreach($result as $index =>$row)
    <tr>
        <td class="text-left">{{ $row->username }}</td>
        <td class="text-right">{{ $row->transaction_count}}</td>
        <td class="text-right">{!! $currency !!} {{ $row->transaction_total }}</td>
        <td class="text-right">{!! $currency !!} {{ $row->cash_total }}</td>
        <td class="text-right">{!! $currency !!} {{ $row->credit_total }}</td>
    </tr>
    @endforeach
    <tr>
        <td class="text-right"><b>Total</b></td>
        <td class="text-right"><b>{{ $result->sum('transaction_count') }}</b></td>
        <td class="text-right"><b>{!! $currency !!} {{ $result->sum('transaction_total') }}</b></td>
        <td class="text-right"><b>{!! $currency !!} {{$result->sum('cash_total')}}</b></td>
        <td class="text-right"><b>{!! $currency !!} {{ $result->sum('credit_total') }}</b></td>
    </tr>
</table>
