<style>
<?= file_get_contents(asset("css/app.css"))?>
<?= file_get_contents(asset("css/bootstrap.min.css"))?>
/* css for dom pdf to use */
@page { margin: 5px;}
</style>

<table  cellpadding="5" width="100%" cellspacing="0" class="text-center border border-solid m-5" >
    <tbody>
       <tr>
          <td colspan="6" class="text-center text-danger uppercase underline text-2xl">CuSTOMER BILL</td>
       </tr>
       <tr >
          <td rowspan="4"class="border-l border-solid  border-black " colspan="2" >
            {{$info->company_logo!=''? '<img src="'.$info->company_logo.'" class="ml-2 mb-2 rounded-circle" width="100px;"  >':''}}
         </td>
          <td colspan="4" class="text-center" >ORIGINAL</td>
       </tr>

       <tr >
          <td colspan="3"  class="text-right text-danger underline text-2xl fontsemi-bold  pr-3" >{{$info->company_name}}</td>
       </tr>
       <tr>
          <td colspan="3" class="text-right pr-3">{{$info->company_address}}</td>
       </tr>
       <tr>
          <td colspan="3" class="text-right pr-3">Tele: {{$info->company_contact_no}}</td>
       </tr>
       <tr>
          <td colspan="5" class="text-right text-primary underline pr-3">{{$info->company_email}}</td>
       </tr>
       <tr>
          <td colspan="4" class="align-top border-r border-solid border-black " >
             <table  class="w-100 text-left border-solid border border-black" cellpadding="0" cellspacing="0" >
                <tbody>
                   <tr>
                      <td class="align-top text-red-600 w-16" rowspan="3">TO, </td>
                      <td  class="border-red-600">  {{$row->{$table.'_name'} }}</td>
                   </tr>
                   <tr class="border border-solid  border-black bl-0 " >
                      <td>&nbsp;</td>
                   </tr>
                   <tr>
                      <td >&nbsp;</td>
                   </tr>
                </tbody>
             </table>
          </td>
          <td  class="align-top w-100" colspan="2">
             <table class="w-100 text-left" >
                <tbody >
                   <tr class=" border border-solid  " >
                      <td >Bill No : {{$row->{$table."_id"} }}</td>
                   </tr>
                   <tr class=" border border-solid  border-black ">
                      <td >Date: {{$row->{$table.'_date'} }}</td>
                   </tr>
                   <tr class= "border border-solid  border-black ">
                      <td  class="h-12"> Address: {{ $row->{$table.'_address'} }}</td>
                    </tr>
                </tbody>
             </table>
          </td>
       </tr>
       <tr class="text-center bg-dark text-white">
          <td  class=" w-28  border border-black border-solid"style="border-right-color: white;">D.C.NO </td>
          <td class=" w-50   border border-solid" >Description Of Goods</td>
          <td  class=" w-40 border  border-solid">Qty</td>
          <td  class=" w-40 border border-solid">Rate&nbsp; {{$info->company_currency}}</td>
          <td  class=" w-40 border  border-solid">Amount&nbsp; {{$info->company_currency}}</td>
       </tr>


 @foreach($product_result as $key=>$sub_row)
   <tr class="text-center h-6" >
          <td >{{$loop->iteration}}</td>
          <td >{{$sub_row->product_name}}</td>
          <td >{{$sub_row->quantity }}</td>
          <td >{{$sub_row->price}}</td>
          <td > {{$sub_row->actual_amount}}</td>
       </tr>

 @endforeach
 <tr class= "h-6 border-b border-l border-black border-solid" >
 </tr>
       <tr class= "h-6 border-b border-black border-solid" >
        <td colspan="3" class="pl-1 text-left border-solid  border  border-black ">Payment Bank :-{{$info->company_bank}} </td>
          <td class=" bg-dark bg-black text-white text-center w-40 pl-2 border border-black border-solid">Total</td>
          <td  class="text-center w-52 border border-black border-solid" >{{ $total_actual_amount }}</td>
       </tr>
       <tr>
        <td colspan="3"  class="p-3 pl-1 text-left border border-solid border-black w-96">Branch :- {{$info->company_bank_address}} </td>
          <td rowspan="1" class=" bg-dark bg-black text-white text-center pl-1 border border-black w-48" >Discount</td>
          <td rowspan="1" class="text-center w-72 border border-black" >{{$row->{$table.'_discount'} }}</td>
       </tr>
       <tr>
        <td colspan="3" class="pl-1 text-left border-b border-l border-black">Bank IBAN CODE :- {{$info->company_bank_IBAN}}</td>
          <td rowspan="1" class=" bg-dark bg-black text-white text-center pl-1 border border-black w-36" >Tax</td>
          <td rowspan="1"  class="text-center  border border-black w-52" >{{$total_tax_amount}} </td>
       </tr>
       <tr>
        <td colspan="3" class="p-3 pl-1 text-left border-b border-l border-black " >AC Name :- {{$info->company_name}}</td>
          <td  class=" bg-dark bg-black text-white text-center pl-1 border">G. Total</td>
          <td  class="text-center border" >{{$grandtotal}}</td>
       </tr>
       <tr  class="h-20">
          <td colspan="3" class="pl-1 text-left border-solid border-l border-r border-b  border-black">
          Amount in words : <span class=" border-b border-dotted border-gray-900"> ({{$info->company_currency}}) {{ $total_in_words }}  Only</span>
             </td>
          <td  colspan="2" ><b class="my-5 uppercase text-blue-600 border-solid  text-left border border-purple-900 text-2xl">{{$info->company_name}}</b></td>
       </tr>

       <tr>
          <td colspan="6" class="pl-1 text-center border-solid border border-black">
             * Interest will be charged upon all acounts remaning unpaid after due date
          </td>
       </tr>
    </tbody>
 </table>
