@include('config')
<?php
use Illuminate\Support\Str;
use Dompdf\Dompdf;
$data=$info;
$orderDate = $row->{$table.'_date'};
$clientName = $row->{$table.'_name'};
$clientContact =$row->{$table.'_address'};

$vat =$row->{$table.'_tax'};
$totalAmount = $row->{$table.'_sub_total'};
$discount = $row->{$table.'_discount'};
$grandTotal = $row->{$table.'_sub_total'};
$F=new NumberFormatter("en", NumberFormatter::SPELLOUT);
$total = $row->{$table.'_sub_total'};
$count = 1;
$total = 0;
 $printtable = '
<table align="center" cellpadding="0" cellspacing="0" class="w-100" style="border:1px solid black;margin-bottom: 10px;">
   <tbody>
      <tr>
         <td colspan="5" class="text-center text-danger text-uppercase" style="text-decoration: underline; font-size: 25px;">CuSTOMER BILL</td>
      </tr>
      <tr>
         <td rowspan="5" colspan="2" style="border-left:1px solid black;" >'.($info->company_logo!=''? '<img src="'.IMAGES_URL.$info->company_logo.'" class="ml-2 mb-2 rounded-circle"alt="logo image not found " width="120px;"  >':'').'</td>
         <td colspan="3" class="text-right" >ORIGINAL</td>
      </tr>

      <tr>
         <td colspan="3"  class="text-right text-danger" style="font-weight: 600;text-decoration: underline;font-size: 25px;">'.ucwords($data->company_name).'</td>
      </tr>
      <tr>
         <td colspan="3" class="text-right">'.$data->company_address.'</td>
      </tr>
      <tr>
         <td colspan="3" class="text-right">Tele: '.$data->company_contact_no.'</td>
      </tr>
      <tr>
         <td colspan="3" class="text-right text-primary" style="text-decoration: underline;">'.$data->company_email.'</td>
      </tr>
      <tr>
         <td colspan="2" class=" p-0 align-top" style="border-right:1px solid black;">
            <table align="left" class="w-100 border-dark" cellpadding="0" cellspacing="0" style="border: thin solid">
               <tbody>
                  <tr>
                     <td class="align-top text-danger" style="width: 74px;" rowspan="3">TO, </td>
                     <td  class="border-danger" style="border-bottom-style: solid; border-bottom-width: thin; ">&nbsp;'.$clientName.'</td>
                  </tr>
                  <tr class="border-dark" style="border-style: solid;border-left:none; border-width: thin;">
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td >&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
         <td class="p-0" class="align-top" colspan="3">
            <table align="left" cellpadding="0" cellspacing="0" class="w-100" >
               <tbody>
                  <tr class="border-dark" style="border-style: solid;border-width: thin;" >
                     <td>Bill No : '.$row->{$table."_id"}.'</td>
                  </tr>
                  <tr>
                     <td>Date: '.$orderDate.'</td>
                  </tr>
                  <tr>
                     <td style="height: 52px;"> Address: '.$clientContact.'</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <tr class="text-center bg-dark text-white">
         <td  style="width: 123px; border: 1px solid black;border-right-color: white;-webkit-print-color-adjust: exact;">D.C.NO </td>
         <td class=" w-50" style="border-style: solid;border-width: thin;border-right-color: white;-webkit-print-color-adjust: exact;">Description Of Goods</td>
         <td  style="width: 150px;border-style: solid;border-width: thin;-webkit-print-color-adjust: exact;">Qty.</td>
         <td  style="width: 150px;border-style: solid;border-width: thin;-webkit-print-color-adjust: exact;">Rate&nbsp; '.$data->company_currency.'</td>
         <td  style="width: 150px;border-style: solid;border-width: thin;-webkit-print-color-adjust: exact;">Amount&nbsp; '.$data->company_currency.'</td>
      </tr>';


      $total_tax_amount=0;
		$total_actual_amount = 0;
foreach($product_result as $sub_row) {
   $actual_amount = $sub_row->quantity * $sub_row->price;
			$tax_amount = ($actual_amount * $sub_row->tax)/100;
			$total_product_amount = $actual_amount + $tax_amount;
			$total_actual_amount = $total_actual_amount + $actual_amount;
			$total_tax_amount = $total_tax_amount + $tax_amount;
			$total = $total + $total_product_amount;
   $printtable .= '<tr class="text-center border-dark" style="height: 27px;">
         <td >'.$count.'</td>
         <td >'.$sub_row->product_name.'</td>
         <td >'.$sub_row->quantity .'</td>
         <td >'.$sub_row->price.'</td>
         <td >'. $actual_amount.'</td>
      </tr>
   ';
   $count++;
}
      $printtable.= '
      <tr class="border-dark" style="border-bottom: 1px solid black;">
         <td style="height: 27px;"></td>
         <td style="height: 27px;"></td>
         <td style="height: 27px;"></td>
         <td class=" bg-dark text-white text-center border-dark "style="width: 149px;border-style: solid;border-width: thin;padding-left: 5px;-webkit-print-color-adjust: exact;">Total</td>
         <td  class="text-center border-dark" style="width: 218px; border-style: 1px solid; border-width: thin;  border-color: black; ">'.$row->{$table."_sub_total"}.'</td>
      </tr>
      <tr>
         <td colspan="3" style="border: 1px solid black;padding: 5px;">Payment Bank :-'.$data->company_bank.' </td>
         <td rowspan="2" class=" bg-dark text-white text-center pl-1" style="border-style: solid;width: 199px;-webkit-print-color-adjust: exact;">Discount</td>
         <td rowspan="2" class="text-center border-dark" style="border: 1px solid black;width: 288px;">'.$row->{$table."_discount"}.'</td>
      </tr>
      <tr>
         <td colspan="3" style="border: 1px solid black;width: 859px;padding: 5px;">Branch :- '.$data->company_bank_address.' </td>
      </tr>
      <tr>
         <td colspan="3" style="border: 1px solid black;padding: 5px;"></td>
         <td rowspan="2" class=" bg-dark text-white text-center pl-1" style="border-style: solid;width: 149px;-webkit-print-color-adjust: exact;">Tax</td>
         <td rowspan="2"  class="text-center border-dark" style="width:218px;border: 1px solid black;">'.$row->{$table."_tax"}.'
         </td>
      </tr>
      <tr>
         <td colspan="3" style="border-bottom: 1px solid black;border-left: 1px solid black;padding: 5px;">AC Name :- '.$data->company_name.'</td>
      </tr>
      <tr>
         <td colspan="3" class="pl-1"style="border-bottom: 1px solid black;border-left: 1px solid black;">Bank IBAN CODE :- '.$data->company_bank_IBAN .'</td>
         <td  class=" bg-dark text-white text-center pl-1"style="border: 1px solid #fff;-webkit-print-color-adjust: exact;">G. Total</td>
         <td  class="text-center border-dark" style="border: 1px solid black;">'.$grandTotal.'</td>
      </tr>
      <tr>
         <td colspan="3" class="pl-1"
         style="border-left: 1px solid black;border-bottom: 1px solid black;">
         Amount in words : ('.$data->company_currency
         .') '.(ucwords($F->format($grandTotal))).' Only'.

            '</td>
      </tr>
      <tr>
         <td colspan="3" class="pl-1" style="border: 1px solid black;"> </td>
         <td rowspan="3" colspan="2" ><b style="color:blue;font-size: 2rem; border: 1rem solid black;
         padding: 0.1rem 0.5rem; text-transform: uppercase; ">'.$data->company_name.'</b></td>
      </tr>
      <tr>
         <td colspan="3" style="border: 1px solid black;padding-left: 5px;">
            * Intrest will be charged upon all acounts remaning unpaid after due date
         </td>
      </tr>
   </tbody>
</table>';
?>

<!DOCTYPE html>
		<html>
		<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible' />
		<link rel="stylesheet" href="<?php echo CSS_URL?>bootstrap.min.css">
</head>
<body>
<?=  $printtable;?>
</body>
