@include('layouts.header')
@php $currency=$info->currency_symbol @endphp
@include('components.message')
                <div class="row pt-3">

                    <div class="col-xl-3 col-md-6 mb-4">
                        @include('small-card',['title'=>'Sales Transaction (Today)','value'=>$today_sales,'class'=>'primary'])
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        @include('small-card',['title'=>'Sales Transaction (Yesterday)','value'=>$today_sales])
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        @include('small-card',['title'=>'Sales Transaction (Last 7 Day)','value'=>$last_seven_day_sales,'class'=>'info'])
                    </div>

                    <!-- Pending Requests Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        @include('small-card',['title'=>'All Time Sales Transaction','value'=>$total_sales,'class'=>'warning'])
                    </div>
                </div>

                <div class="row pt-0">

                    <div class="col-xl-4 col-md-6 mb-4">
                        @include('small-card',['title'=>'Sales Recorded (Today)','value'=>$today_sales_recorded,'class'=>'primary'])
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        @include('small-card',['title'=>'Sales Recorded (Yesterday)','value'=>$yesterday_sales_recorded,'class'=>'success'])
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        @include('small-card',['title'=>'Sales Recorded (Last 7 Day)','value'=>$last_seven_day_sales_recorded,'class'=>'info'])
                    </div>

                </div><!--row div end -->
                <div class="row pt-5">

                    <div class="col-xl-3 col-md-6 mb-4">
                        @include('small-card',['title'=>'Purchase Transaction (Today)','value'=>$today_purchases,'class'=>'primary'])
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        @include('small-card',['title'=>'Purchase Transaction (Yesterday)','value'=>$yesterday_purchases])
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        @include('small-card',['title'=>'Purchase Transaction (Last 7 Day)','value'=>$last_seven_day_purchases,'class'=>'info'])
                    </div>

                    <!-- Pending Requests Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        @include('small-card',['title'=>'All Time Purchase Transaction','value'=>$total_purchases,'class'=>'warning'])
                    </div>
                </div><!--row div end -->

                <div class="row pt-0">
                    <div class="col-xl-4 col-md-6 mb-4">
                        @include('small-card',['title'=>'Purchase Recorded (Today)','value'=>$today_purchases_recorded,'class'=>'primary'])
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        @include('small-card',['title'=>'Purchase Recorded (Yesterday)','value'=>$yesterday_purchases_recorded])
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        @include('small-card',['title'=>'Purchase Recorded (Last 7 Day)','value'=>$last_seven_day_purchases_recorded,'class'=>'info'])
                    </div>

                </div><!--row div end -->
    @if(auth()->user()->is_admin())
                <div class="row">
                    <div class="col-md-3 mb-4">
                        @include('big-card',['title'=>'Active Users','value'=>$total_user,'icon'=>'users'])
                    </div>
                    <div class="col-md-3 mb-4">
                        @include('big-card',['title'=>'Active Category','value'=>$total_category,'icon'=>'sitemap'])
                    </div>
                    <div class="col-md-3 mb-4">
                        @include('big-card',['title'=>'Active Brands','value'=>$total_brand])
                    </div>
                    <div class="col-md-3 mb-4">
                        @include('big-card',['title'=>'Item in Stock','value'=>$total_product,'icon'=>'warehouse','class'=>'warning'])
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-4">
                        @include('big-card',['title'=>'Active Supplier','value'=>$total_supplier,'icon'=>'users','class'=>'primary'])
                    </div>
                    <div class="col mb-4">
                        @include('big-card',['title'=>'Active tax type','value'=>$total_tax,'icon'=>'hand-holding-usd','class'=>'info'])
                    </div>
                    <div class="col mb-4">
                        @include('big-card',['title'=>'active Unit type','value'=>$total_unit,'icon'=>'percentage','class'=>'info'])
                    </div>
                </div>
            @endif
                <div class="row">
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'Sales Value','value'=>$currency.' '.$total_sales_value,'icon'=>'shopping-cart','class'=>'info','iconclass'=>'success'])
                    </div>
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'Cash Sales Value','value'=>$currency.' '.$total_cash_sales_value,'icon'=>'money-bill','iconclass'=>'success'])
                    </div>
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'Credit Sales Value','value'=>$currency.' '.$total_credit_sales_value,'icon'=>'credit-card','class'=>'danger','iconclass'=>'danger'])
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'Revenue to collect','value'=>$currency.' '.$total_revenue_value,'icon'=>'shopping-cart','class'=>'info','iconclass'=>'success'])
                    </div>
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'uncollected Cash value','value'=>$currency.' '.$total_cash_revenue_value,'icon'=>'coins','iconclass'=>'danger'])
                    </div>
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'Uncollected credit Value','value'=>$currency.' '.$total_credit_revenue_value,'icon'=>'question','class'=>'danger','iconclass'=>'danger'])
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'purchase Value','value'=>$currency.' '.$total_purchase_value,'icon'=>'cart-arrow-down','class'=>'info','iconclass'=>'success'])
                    </div>
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'cash purchase value','value'=>$currency.' '.$total_cash_purchase_value,'icon'=>'money-bill','iconclass'=>'success'])
                    </div>
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'credit purchase value','value'=>$currency.' '.$total_credit_purchase_value,'icon'=>'credit-card','class'=>'danger','iconclass'=>'danger'])
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'Expense to pay','value'=>$currency.' '.$total_expense_value,'icon'=>'shopping-cart','class'=>'info','iconclass'=>'info'])
                    </div>
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'Unpaid Cash value','value'=>$currency.' '.$total_cash_expense_value,'icon'=>'coins','iconclass'=>'success'])
                    </div>
                    <div class="col-md-4 mb-4">
                        @include('big-card',['title'=>'Unpaid credit Value','value'=>$currency.' '.$total_credit_expense_value,'icon'=>'question','class'=>'danger','iconclass'=>'danger'])
                    </div>
                </div>
		@include('page-footer',['company_name'=>$info->company_name])
<script type="text/javascript" src="<?= env('JS_URL')?>jquery.easing.min.js"></script>
<script type="text/javascript" src="<?= env('JS_URL')?>theme.js"></script>
@include('layouts.footer')
