@include('layouts.header')
<?php
$setup=false;
if(isset($setuppage)||session()->has('setup')){
    $setup=true;
}
?>
        <!-- Page Heading -->
        <h1 class="h3 mb-4 ml-3 text-gray-800"><?php echo ($setup)?'System Configuration':'Setting'?></h1>
        @include('components.message')
        <form method="post" class="form" id="form" class="form" enctype="multipart/form-data" action="<?= ($setup)?route('settings_store'):route('settings_update')?>">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col">
                            <h6 class="m-0 font-weight-bold text-primary"><?php echo ($setup)?'Set up Account':'Setting'?></h6>
                        </div>
                        <div clas="col text-right" >
                            <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm reset"> <?php echo ($setup)?'<i class="fas fa-save"></i>  Set Up':'<i class="fas fa-edit"></i> Edit'?></button>
                            &nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company Name</label>
                                <input type="text" name="company_name" value="<?php echo ($setup)?'':$info->company_name?>"id="company_name" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Company Email</label>
                                <input type="text" name="company_email" value="<?php echo ($setup)?'':$info->company_email?>"id="company_email" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Company Contact No.</label>
                                <input type="text" name="company_contact_no" value="<?php echo ($setup)?'':$info->company_contact_no?>"id="company_contact_no" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Company Address</label>
                                <input type="text" name="company_address" value="<?php echo ($setup)?'':$info->company_address?>"id="company_address" class="form-control" />
                            </div>
                                <div class="form-group">
                                <label>Inventory Method</label>
                                <select name="inventory_method" id="inventory_method" class="form-control" >
                                <option value="" selected hidden disabled> Select method</option>
                                <option value="FIFO" <?php echo ($setup)?'':($info->inventory_method=='FIFO'?'Selected':'')?>>First in First Out </option>
                                <option value="LIFO" <?php echo ($setup)?'':($info->inventory_method=='LIFO'?'Selected':'')?>>Last In First Out</option>
                                <option value="AVG"<?php echo ($setup)?'':($info->inventory_method=='AVG'?'Selected':'')?>>Average Mean</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Company Bank</label>
                                <input type="text" name="company_bank" value="<?php echo ($setup)?'':$info->company_bank?>"id="company_bank" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Secret Password</label>
                                <input type="password" name="secret_password" class="form-control" />
                                <span class="text-muted">This is required to log in to secured places</span><br />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label>Sales Target</label>
                                    <input type="number" name="company_sales_target" value="<?php echo ($setup)?'':$info->company_sales_target?>"id="company_sales_target" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>Revenue Target</label>
                                    <input type="number" name="company_revenue_target" value="<?php echo ($setup)?'':$info->company_revenue_target?>"id="company_revenue_target" class="form-control" />
                                </div>
                            <div class="form-group">
                                <label>Currency</label>
                                <?php  echo $currencylist; ?>
                            </div>
                            <div class="form-group">
                                <label>Timezone</label>
                                <?php  echo $timezonelist; ?>
                            </div>
                            <div class="form-group">
                                <label>Company Bank Address</label>
                                <input type="text" name="company_bank_address" value="<?php echo ($setup)?'':$info->company_bank_address?>"id="company_bank_address" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Company Bank IBAN</label>
                                <input type="text" name="company_bank_IBAN" value="<?php echo ($setup)?'':$info->company_bank_IBAN?>"id="company_bank_IBAN" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Select Logo</label><br />
                                <input type="file" name="company_logo" class="file_upload" id="company_logo" />
                                <br />
                                <span class="text-muted">Only .jpg, .png file allowed for upload</span><br />
                                <span id="uploaded_logo"><?php echo ($setup)?'':($info->company_logo!=''? '<img src="'.IMAGES_URL.$info->company_logo.'" alt="logo image not found" >':'')?></span>
                            </div>

                        </div>
                    </div>
                    <?php if($setup):?>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Admin Email Address</label>
                                <input type="text" name="email" id="admin_email" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Admin Username</label>
                                <input type="text" name="username" id="admin_name" class="form-control" required data-parsley-trigger="keyup" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Admin Password</label>
                                <input type="password" name="password" id="admin_password" class="form-control" required data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <?php endif?>
                </div>
            </div>
            <input type="hidden" name="company_id" id="company_id" value="<?php echo ($setup)?'':$info->company_id?>" />
        </form>
        @include('page-footer',['company_name'=>$info->company_name])
        @include('layouts.footer')
<script>
    let id=$("#company_id").val();
    if(id)
    method_type='/'+id;
</script>
