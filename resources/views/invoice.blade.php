<?php
if(empty($table)){
	echo '<div class="row d-flex align-items-center min-vh-100">
            <div class="col-12">
                <h1 class="text-center text-info">
                    Not a valid PDF request
                </h1>
            </div>
        </div>';
    return;
}
?>
<style>
   <?= file_get_contents(asset("css/bootstrap.min.css"))?>
/* css for dom pdf to use */
    @page { margin: 5px;}
</style>
<table class="mb-0 pb-0" width="100%" border="1"  cellpadding="5" cellspacing="0">
    <tr>
        <td class="bg-danger text-white text-center" colspan="2" style="font-size:18px">
            <b>Invoice</b>
        </td>
    </tr>
    <tr  >
        <td class="text-center" style="border-bottom:none;" colspan="2">
            <b style="font-size:32px;color:blue;"><?=$info->company_name?></b>
            <br />
            <span style="font-size:16px;color:blue;">{{$info->company_address}}</span>
            <br />
            <span style="font-size:16px;color:blue;"><b>Contact No. - </b>{{$info->company_contact_no}}</span>
            <br />
            <span style="font-size:16px;color:blue;"><b>Email - </b>{{$info->company_email}}</span>
            <br /><br />
        </td>
    </tr>
    <tr>
        <td colspan="2" style="border-top:none;border-bottom:none;">
            <table class="w-100" cellpadding="5">
                <tr>
                    <td width="65%">
                        To,<br />
                        <b>RECEIVER (BILL TO)</b><br />
                        Name : {{$row->{$table.'_name'} }}<br />
                        Billing Address : {{$row->{$table.'_address'} }}<br />
                    </td>
                    <td width="35%">
                        Reverse Charge<br />
                        Invoice No. : {{$row->{$table.'_id'} }}<br />
                        Invoice Date : {{$row->{$table.'_date'} }}<br />
                    </td>
                </tr>
            </table>
            <br />
            <table class="w-100" border="1" cellpadding="5" cellspacing="0">
                <tr class="bg-dark text-white border border-light">
                    <th rowspan="2">Sr No.</th>
                    <th rowspan="2">Product</th>
                    <th rowspan="2">Quantity</th>
                    <th rowspan="2">Price</th>
                    <th rowspan="2">Actual Amt.</th>
                    <th colspan="2">Tax (%)</th>
                    <th rowspan="2">Total (<?=$info->company_currency?>)</th>
                </tr>
                <tr class="bg-dark text-white border border-light">
                    <th class="">Rate</th>
                    <th class="">Amt.</th>
                </tr>

                @foreach($product_result as $key=>$sub_row)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$sub_row->product_name}}</td>
                        <td>{{$sub_row->quantity}}</td>
                        <td class="text-right">{!!$info->currency_symbol!!} {{$sub_row->price}}</td>
                        <td class="text-right">{!!$info->currency_symbol!!} {{number_format($sub_row->actual_amount, 2)}}</td>
                        <td>{{$sub_row->tax}} %</td>
                        <td class="text-right">{!!$info->currency_symbol!!} {{number_format($sub_row->tax_amount, 2)}}</td>
                        <td class="text-right">{!!$info->currency_symbol!!} {{number_format($sub_row->product_amount, 2)}}</td>
                    </tr>
                @endforeach

                <tr>
                    <td class="text-right" colspan="4"><b>Total</b></td>
                    <td class="text-right"><b>{!!$info->currency_symbol!!} {{$total_actual_amount}}</b></td>
                    <td>&nbsp;</td>
                    <td class="text-right"><b>{!!$info->currency_symbol!!} {{$total_tax_amount}}</b></td>
                    <td class="text-right"><b>{!!$info->currency_symbol!!} {{$total}}</b></td>
                </tr>

            </table>
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />



            <table class="w-100 border-0" cellpadding="0">
                <tr>
                    <td width="60%">
                    <span  class="text-primary m-0 ml-3 text-center text-uppercase d-inline-block" style="
                    display: flex;
                    justify-content: space-around;
                    font-size: 2rem;
                    font-weight: 600;
                    padding: 0.25rem 1rem;
                    border: 0.5rem solid #08313b;
                    transform: rotate(-14deg);
                    ">
                   <?=$info->company_name?></span>
                   <br />
                        ----------------------------------------<br />
                        Company Stamp/Signature
                    </td>
                    <td width="30%>

                    </td>
                    <td width="30%" class="text-center">
                        <span  class="m-0 ml-3 text-center text-uppercase d-inline-block invisible" style="
                        display: flex;
                        justify-content: space-around;
                        font-size: 2rem;
                        font-weight: 600;
                        padding: 0.25rem 1rem;
                        border: 0.5rem solid #08313b;
                        transform: rotate(-14deg);
                        visibility:hidden;
                        ">
                       <?=$info->company_name?></span>
                        <div>----------------------------------------</div>
                        Receiver's Signature
                    </td>
                </tr>
            </table>

            <br />
            <br />
            <br />
        </td>
    </tr>
    <tr >
        <td class="text-center" colspan="2" style="font-size:18px;border-top:none">
            <b>We are glad to transact with you</b>
        </td>
    </tr>
</table>
