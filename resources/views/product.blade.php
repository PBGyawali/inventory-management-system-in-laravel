@include('layouts.header')
@include('components.message')
		<div class="row">
			<div class="col-lg-12">
				<div class="card card-secondary">
                    <div class="card-header">
                                @include('header_card',['element' =>'product','noreport'=>true])
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 table-responsive">
                                @include('table',['headers'=>['product_id','category.category_name','brand.brand_name','product_name',
                                'available_quantity','product_status',auth()->user()->is_master()?'user.username ':''=>'created_by']])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="Modal" class="modal fade" data-backdrop="static">
            <div class="modal-dialog">
                <form method="post" id="form" class="form" action="<?php echo route('product')?>">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header py-0 pt-1">
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Product</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <div class="modal-body pb-0 my-0">
                            <div class="form-group">
                                <label>Select Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <?php echo $category_list;?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Select Brand</label>
                                <select name="brand_id" id="brand_id" class="form-control" required>
                                    <option value="">Select Category First</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Enter Product Name</label>
                                <input type="text" name="product_name" id="product_name" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label>Enter Product Description</label>
                                <textarea name="product_description" id="product_description" class="form-control" rows="auto" ></textarea>
                            </div>
                            <div class="form-group">
                                <label>Enter Product Opening Stock Quantity</label>
                                <div class="input-group">
                                    <input type="number" name="opening_stock" id="product_quantity" class="form-control" required  />
                                    <span class="input-group-addon ">
                                        <select name="product_unit" id="product_unit" class="form-control" required>
                                        <?php echo $unit_list?>
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Enter Product Base Price</label>
                                <input type="text" name="product_base_price" id="product_base_price" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                            </div>
                            <div class="form-group">
                                <label>Enter Product Tax (%)</label>
                                {{-- <input type="text" name="product_tax" id="product_tax" class="form-control" style="display:none" required pattern="[+-]?([0-9]*[.])?[0-9]+" /> --}}
                                <span id="span_item_details">
                                    @include('tax-select',['select_menu'=>$tax_list])  
                                </span> 
                            </div>
                        </div>
                        <div class="modal-footer py-0 my-0">
                            <button type="submit"  id="submit_button" class="btn btn-success">Add</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @include('detail_modal',['id'=>'product_detail_form','name'=>'product','id'=>'product_detail_form'])
        @include('page-footer',['company_name'=>$info->company_name])
        @include('layouts.footer_script')
