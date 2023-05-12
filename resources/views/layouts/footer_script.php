		</div>
	</body>
</html>



<script>
timeout();
    let columns = [];
    let order=[];
    const defaultOrder=['0','desc'];
    const token=$('meta[name="csrf-token"]').attr('content');
    let methodUrl='';
    var from_date='';
    var to_date='';
    var productList;
    var taxList;
    var brandList;
    var dataTable='';
    var autoRefresh=true;
    var hideTimeoutId;
    var clearTimeoutId;


    var inputs = $(':input').filter(function() { // use * if not :input specific
    return Array.from(this.attributes)
        .some(a => a.nodeName.startsWith('data-parsley-'))
    })
    //similar result as above
    const attrCount = document.evaluate("count(//@*[starts-with(name(), 'data-parsley-')])", document)
    const attrCountNumber=attrCount.numberValue//this gives the count
    if(inputs.length && typeof 'parsley'=='function'){
        $('#form').parsley();
    }
    var url=$('#form').attr('action');


    let listUrl=url+'/list';
	let fetchUrl=url+"/max";

    function defineDateRange(start='',end=''){
        from_date=start
        to_date=end
    }


    function callback(...args) {
        if (args.length === 0||args.length === 1) {
            methodUrl = args;
        } else {
            methodUrl="/" + args.join("/");
        }
    }


    $('#toggle_refresh').click(function(e){
        autoRefresh = !autoRefresh;
        $('#toggle_refresh').attr('title',`Toggle auto refresh. Currently set to ${autoRefresh}`)
                        .toggleClass('btn-danger btn-success')
                        .find('i').toggleClass('fa-toggle-on fa-toggle-off');

    });

    function findList(number,element='brand',checkColumn='category'){
            // must have variable declared with the format ${element}List
            let output = '';
            var total=0;
            let checkedList=window[element+'List'];
            if(checkedList){
                for (i = 0; i < checkedList.length; i++) {
                    if(checkedList[i][checkColumn+'_id']==number){
                    output += '<option value="'+checkedList[i][element+'_id']+'">'+checkedList[i][element+'_name']+'</option>';
                    total++;
                    }
                }
                if(total==0)
                        output = `<option value="">No ${createDisplayName(element)} Found</option>`;
            }
            return output;
    }

    function modifyUrl(currentUrl, ...replaceArgs) {
        if (!currentUrl.trim().startsWith("http://localhost/")) {
            currentUrl = currentUrl.replace("http:", "https:");
        }

        for (let i = 0; i < replaceArgs.length; i += 2) {
            const replaceableText = replaceArgs[i];
            const replaceableValue = replaceArgs[i + 1];
            currentUrl = currentUrl.replace(encodeURIComponent(replaceableText), encodeURIComponent(replaceableValue))
                                    .replace(replaceableText, encodeURIComponent(replaceableValue));
        }

        return currentUrl;
    }

    //all other ajax function need to be below this or they will throw error
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
        $( document ).ajaxSend(function() {
            $('.errormesssage').html('')
        });
    $( document ).ajaxError(function( event, request, settings, thrownError ) {
        clearPreviousTime();
        if (request.status == 422) {
            showMessage(request.responseJSON.message,'danger',false);
                if(!$('.login').length>0){
                        $.each(request.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="'+i+'"]');
                        el.after($(`<span class="text-danger errormessage">${error[0]}</span>`));
                    });
                }
        }
        else if ([401].includes(request.status)) {
            showMessage(request.responseJSON.message,'primary');
        }
        else if (request.status == 419) {
            showMessage(request.responseJSON.message+ ' Please relogin or refresh');
        }
        else if(request.responseText!=''){
            showMessage(request.responseText,'info',false)
            alert(request.responseText)
        }
    });
    $( document ).ajaxComplete(function() {
        enableButton()
    })



    if(typeof listUrl!='undefined' && listUrl){
            let allowedDomain=['product'];
            let restrictedDomain=['status'];
            var nonListPage= restrictedDomain.some(function(restrictedDomain) {
                    return listUrl.indexOf(restrictedDomain) !== -1;
                });
            var listPage= allowedDomain.some(function(allowedDomain) {
                return listUrl.indexOf(allowedDomain) !== -1;
            });
            if(listPage && !nonListPage){
                listUrl=modifyUrl(listUrl);
                ajaxCall(listUrl).then(function(result) {
                    $.each(result, function(key, value){
                    window[key+'List']=value
                })
            })
        }
    }


    function createDisplayName(str) {
       return str.replace(/_/g, " ")
                  .replace("id", "name")
                  .replace("from", "start")
                  .replace("to", "end")
                  .split(" ")
                  .map(word => word.charAt(0)
                  .toUpperCase() + word.slice(1))
                  .join(" ");
    }

    //create an array of columns for dataTables to
    //create data automatically according to the current table headers

    $("th").each(
        function ()
            {
                var className=$(this).attr('class')
                if(className){
                        var th =className.split(' ')[0];
                        var th2 =className.split(' ')[1];
                        var th3 =className.split(' ')[2];
                    if(th=='action'){
                        columns.push({data: th,name:th,orderable:false,searchable:false})
                    }
                    else if(th2=="admininfo"){
                        columns.push({
                            "data":th,
                            "className":th2,
                            "defaultContent": '',
                            'visible' :$('.'+th2).length > 0?true:false,
                            "render": function (data)
                            {
                                return  ($('.'+th2).length)?data:'';
                            }
                        });
                    }
                    else{
                        columns.push({data: th,name:th})
                    }
                    if(th2=='order'){
                        var lastIndex = columns.length - 1;
                        order.push([lastIndex,th3||'desc']);
                    };
                }
            }
    );

draw_table()

    function draw_table(){    //launch dataTables only if table exist in page
        if($('#table').length>0){
            dataTable= $('#table').DataTable({
                    "processing":true,
                    "serverSide":true,
                    "responsive": true,
                    "order":order.length != 0 ?order:defaultOrder,
                    "ajax" : {
                                url:url,
                                type:"GET",
                                dataType:'json',
                                data:{
                                    from_date: function() { return from_date },
                                    to_date: function() { return to_date }
                                }
                    },
                    columns:columns,
                    "columnDefs":[
                        {
                            "targets":  [ 'action','admininfo'],
                            "orderable":false,
                        },
                    ],
                });
        }
    }

    $('#add_button').click(function(e){
        let element=$(this).data('element');
        if(typeof CKEDITOR !== 'undefined')
        CKEDITOR.instances.body.setData('');
        let modal=$(this).data('target') ||'#Modal';
        $form=$(modal).find('form');
        $form[0].reset();
        $form.parsley().reset();
        $title=$(modal).find('.modal-title');
        $title.html("<i class='fa fa-plus'></i> Add "+element);
        //do not know if the form has input/submit or button/submit so doing both here
        $button= $form.find("button[type=submit]");
        $form.find("input[name='_method']").remove();
        //changing the submit button values from edit, please wait etc to add
        $button.html('<i class="fa fa-paper-plane"></i>'+ " Store "+element);
        //adding extra string to current base url
        callback('/store');
        $('#publish,#anonymity').attr('checked',false);
      	$("#featured_image").prop('required', true);
        $('.errormessage,#append_ticket,#form_message,#append_comment').html('');
        //$('#span_item_details').html('');
        $('#brand_id').html('<option value="" >Select Category First</option>');
        var clickedElement = $(e.target);
        if (clickedElement.is('a') || clickedElement.parents('a').length > 0) {
            const linkElement = clickedElement.is('a') ? clickedElement : clickedElement.parents('a').eq(0);
            if (linkElement.attr('href') !== 'javascript:;') {
                //if the link tag does not have javascript value
                //then it takes to another page so cancel any further actions
                return;
            }
        }         
        let count = $('.item_details').length;
        if(!count){
            element = element.toLowerCase().trim().replace(/\s/g, "_");
            if(typeof window['add_row_'+element] == 'function')
                window['add_row_'+element]();
            //if add_row function exist in the page then call it
            else if ( typeof add_row == 'function' )
                add_row();
        }
        $('#span_item_details').children().not(':first').remove();
        $(modal).modal('show');
    });


    $(document).on('click', '.delete', function(e){
        //get id attribute if it is not present then take from the data attribute
        const id = $(this).attr('id') || $(this).data('id');
        let data={};
        callback(id,'delete');
        let finalUrl=url+methodUrl;
        disable(finalUrl,data,'delete the data','DELETE','red');
    });

    $(document).on('click', 'button.reset', function(){
		let id = $(this).attr("id")||$(this).data('id')
        callback(id,'reset');
        var data={}
        data['id'] =id;
        let finalUrl=url+methodUrl;
        disable(finalUrl,data,'reset the profile account password','POST','red',);
	});

    $(document).on('click', '.status', function(e){
       // Get the ID attribute, or use the data ID if it's not present
        const id = $(this).attr('id') || $(this).data('id');
        // Get the current status and table prefix from data attributes
        const status = $(this).data('status');
        const tablePrefix = $(this).data('prefix');
        //final url to use to send the request
        callback(id,'update');
        let finalUrl=url+methodUrl;
        let data={}
        const column = 'status';
        let newStatus = 'active';
        if (status === 'active') {
            newStatus = 'inactive';
        }
        columnWithPrefix = column;
        //add the table prefix and column variable to make the column name
        if (tablePrefix) {
            columnWithPrefix = `${tablePrefix}_${column}`;
        }
        data[columnWithPrefix] = newStatus;
        disable(finalUrl,data,`change the ${tablePrefix} status`);
    });

    $(document).on('click', '.update', function(e){
        let id = $(this).attr("id")||$(this).data('id')
        var element = $(this).data("prefix");
        let modal=$(this).data('target') ||'#Modal';
        $form=$(modal).find('form');
        $form[0].reset();
        $('#form_message').html('');
        $form.parsley().reset();
        callback(id,'edit');
        let finalUrl=url+methodUrl;
        ajaxCall(finalUrl).then(function(result) {
            callback(id,'update');
            if($('.tickets').length<=0){
                    $(modal).modal('show');
            }
            $form=$(modal).find('form');
            $button= $form.find("button[type=submit]");
            $button.html("Edit " +element);
            $('.object_details').html('');
            $('#user_password,#featured_image').attr('required', false);
            $('.btn').attr('disabled', false);
            $title=$(modal).find('.modal-title');
            $title.html("<i class='fas fa-edit'></i> Edit " +element);
            // append the hidden input to the form
            $form.append($("<input>").attr({name:"_method",type:"hidden",value:"PUT"}))
            element = element.toLowerCase().trim().replace(/\s/g, "_");
            if (typeof window[element+'_update']== "function") {
                window[element+'_update'](result);
            }
            else if(typeof 'update'== "function")
            update(result);
            else
            easy_update(result);
        })
    });

    $(document).on('click', '.view', function(e){
        let id = $(this).attr("id")||$(this).data('id')
        callback(id,'show');
        let finalUrl=url+methodUrl;
        ajaxCall(finalUrl).then(function(result) {
            $('#detailsModal').modal('show');
            $('#modal_item_details').html(result);
        })
    });

    $(document).on('submit','form:not(.logout_form)', function(event){
        if(!url)
            return;
        var finalUrl=url+methodUrl;
        $form=$(this);
        $form.append($("<input>").attr({name:"_token",type:"hidden",value:token}));
        $form.attr('action',finalUrl)
        // Check if the form has the element
        if (!$form.is('#form, #second_form') && !$form.hasClass('form')) {
            return;
        }
        event.preventDefault();
        var form_data = new FormData(this);
        $button= $form.find("button[type=submit]");
        buttonValue=$button.html();
        if(!typeof parsley=='function'||$form.parsley().isValid())
        {
            ajaxCall(finalUrl,form_data,"POST").then(function(result) {
                    if($('.login').length){
                        update(result)
                        return
                    }
                    if(!$('.no-close').length){
                        $('#Modal,.modal').modal('hide');
                    }
                    if(!$('.reset,.no-reset').length){
                        $form[0].reset()
                    }
                    $form.parsley().reset();
                    $('.file_upload,.password').val('');
                    $button.html(buttonValue);
            }).catch(function(error) {
                alert(error)
                $button.html(buttonValue);
            });
        }
    });


        $(document).on('change', '.quantitypicker', function(){
			let input=$(this);
            var Count=input.data('count');
            var selectId=$('#product_id'+Count).val();
            var currentValue=input.val();
            let totalMax=0;
            realMax=input.data('max')*1;//457
            currentMax=realMax;
            if ($('select option[value="' + selectId + '"]:selected').length > 1) {
                $('.selectpicker').each(function ()
                {
                    nonSelect=$(this);
                    var nonSelectId=nonSelect.val();
                    var nonSelectCount=nonSelect.data('count');
                    if(nonSelectId==selectId && Count!=nonSelectCount){
                        var quantity=$('#quantity'+nonSelectCount).val();//100+100+50-50
                        if(quantity)
                        totalMax+= quantity*1//250-50
                    }
                });
                currentMax=realMax-totalMax;//457-250+50=307
            }
            if(currentMax<=0)
            currentMax=0;
            if(currentMax==0)
            input.attr('min',0);
            input.attr('max',currentMax);
            if(currentValue>currentMax)
              input.val(currentMax);
		});

        $(document).on('change', '.selectpicker', function(){
			select=$(this);
            var selectId=select.val();
            var Count=select.data('count');
            var currentMax=$('#quantity'+Count).attr('max');
            totalMax=0;
            if ($('select option[value="' + selectId + '"]:selected').length > 1||currentMax) {
            $('.selectpicker').each(function ()
            {
                nonSelect=$(this);
                var nonSelectCount=nonSelect.data('count');
                var nonSelectId=nonSelect.val();
                currentValue=$('#quantity'+nonSelectCount).val()
                if(nonSelectId==selectId && select.attr('id')!= nonSelect.attr('id')){
                    if(currentValue)
                    totalMax+= currentValue*1
                }
            });
            }
			$quantityForm=$('#quantity'+Count);
            var form_data={product_id:selectId}
            ajaxCall(fetchUrl,form_data).then(function(result) {
                currentMax=result-totalMax;
                $quantityForm.attr('max',currentMax*1);
                $quantityForm.data('max',result);
                if(currentMax<=0)
                    currentMax=0;
                var currentValue=$quantityForm.val();
                if(currentValue>currentMax)
                    $quantityForm.val(currentMax);
            })
		});

        
        $('#category_id').change(function(){
            var category_id = $('#category_id').val();
            data=findList(category_id)
            $('#brand_id').html(data);
        });

        function enableButton(value=false){
            $('.btn,button').attr('disabled', false);
            $('.btn,button').css({"filter": "","-webkit-filter": ""});

        }

    function disableButton(element='button[type="submit"].btn'){
        $(element).css({"filter": "grayscale(100%)","-webkit-filter": "grayscale(100%)"});
        $(element).attr('disabled', 'disabled');
        $(element).html('Please wait...');
        $(element).val('Please wait...');

    }
    function  hide()
	{
        $('.error, .message, #alert').slideUp();
    }

    function clear(){

        $('#message,#form_message,#alert').html('')
    }

    function clearPreviousTime(){
        clearTimeout(hideTimeoutId);
        clearTimeout(clearTimeoutId);
    }

    function timeout()
	{
        clearPreviousTime();
        hideTimeoutId = setTimeout(function(){hide();}, 7000);
        clearTimeoutId = setTimeout(function(){clear();}, 10000);

        if(dataTable && autoRefresh) {
            dataTable.ajax.reload();
        }
    }

    function result(data,refresh=true){
        $('.errormessage').html('');
		$('#alert_action,#message,#form_message').fadeIn().html(data);

        if(refresh)
		timeout();
    }

    $('#refresh').click(function(){
  		$('#from_date,#to_date').val('');
          defineDateRange();
         //dataTable.ajax.reload();
         dataTable.destroy();
         draw_table()
  	});

    $(window).resize(function(){
        if(dataTable)
        dataTable.destroy();
        draw_table()
    })

    $('#export,#report,#filter').click(function(){
  		var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(!from_date){
            showAlert('Start date range needs to be selected')
            return
        }
        if(!to_date)
        to_date =current_date();
        var currentUrl=$(this).data('url')
        if(currentUrl){
                reportUrl=modifyUrl(currentUrl,':from_date', from_date,':to_date',to_date)
                window.open(reportUrl);
        }
        else{
            defineDateRange(from_date, to_date);
            dataTable.ajax.reload();
        }
  	});

	function disable(finalUrl,data,message="change the status",postMethod='PATCH',type= 'blue'){
        $.confirm
        ({
            title: 'Confirmation please!',
            content: "This will "+ message+". Are you sure?",
			type: type,
            buttons:{
						Yes: {
							btnClass: 'btn-blue',
							action: function() {
								ajaxCall(finalUrl,data,postMethod);
							}
						},
					}
        });
    }

    function current_date(format = 'yyyy-mm-dd') {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');//January is 0!
        const dd = String(today.getDate()).padStart(2, '0');

        return format.replace('yyyy',yyyy)
                     .replace('mm',mm)
                     .replace('dd',dd)
                     .replace('yy',yyyy)
    }


    //function to create alert messages
    function showAlert($content,$title='Error')
	{
			   $.alert({
						   title: $title,
						   content: $content,
						   buttons:
						   {
								   No: {
									   text:'OK',
									   btnClass: 'btn-blue',
								   },
								   Yes:{
									   isHidden: true,
								   }
						   }
					   });

            $(".file_upload").val('');
            return false;
	 }


    function ajaxCall(sendUrl,sendData=[],postMethod='GET') {
        let ajaxUrl=modifyUrl(sendUrl);
            return new Promise(function(resolve, reject) {
                let contentType="application/x-www-form-urlencoded; charset=UTF-8";
                let processData=true;
                if (sendData.constructor === FormData) {
                    contentType=false;
                    processData=false;
                }
                $.ajax({
                    url: ajaxUrl,
                    method:postMethod,
                    data:sendData,
                    dataType:"JSON",
                    contentType:contentType,
                    processData:processData,
                    success: function(response) {
                        if(typeof response=="object"){
                            let responseKeys=['error','response','success']
                            let classKeys=['danger','success','success']
                            $.each(responseKeys, function(key, value){
                                if (value in response && response[value]){
                                    if($('<div>').html(response[value]).find('div').length) {
                                        result(response[value]);
                                    } else {
                                        let classValue=classKeys[key];
                                        showMessage(response[value],classValue,value=='error'?null:true);
                                    }
                                }
                            });
                            if ("image" in response && response.image){
                                $('#profile_image,.profile_image').attr('src',response.image);
                            }
                            if ("redirect" in response && response.redirect){
                                window.location.assign('.'+response.redirect);
                            }
                            if ("update" in response && response.update){
                                update(response.update)
                            }
                        }
                        resolve(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        reject(errorThrown);
                    }
                });
            });
    }


    /*template section start*/

    function showMessage(message,type='danger',refresh=true)
	{
        let response=
        `<div class="alert alert-${type} alert-dismissible fade show">
            <button type="button" class="close" onclick="hide()">&times;</button>
                            ${message}
        </div>`
        result(response,refresh)
	}


    $(document).on('click', '#add_more,.add_more', function(){
        
            let element=$(this).data('element');
            let count = $('.item_details').length;
            if (typeof window['add_row_'+element]== "function") {
                window['add_row_'+element](count);
            }
            else
            add_row(count);
    });

    $(document).on('click', '.remove', function(){
        var rowNumber = $(this).attr("id")||$(this).data("id");
        $('#item_details_row'+rowNumber).remove();
    });


    function add_row_sales_order(count = ''){
        add_row_select(count)
    }

    function add_row_purchase_order(count = ''){
        add_row_select(count);
    } 

    function add_row_product(count = ''){      
        add_row_select(count);
    }

    function add_row_select(count = ''){   
        // clone the template and modify its id
        const template = $('#item_details_row').clone().attr('id',`item_details_row${count}`);
        $spanInputs=template.find(':input');
        // Loop through each option and modify the selected attribute
        $spanInputs.each(function(index, input) {
            $(input).attr('data-count', count);
            $(input).attr('id', `${$(input).attr('id')??''}${count}`);            
            $(input).val('');
        });
        template.find('.add_more').text('-').attr('class','btn btn-danger remove');
        
        // append the resulting element to the target container
        $('#span_item_details').append(template);  
    }

      

    /*template section end*/

    /*update data section start*/
    // function brand_update(data){
    //         $('#category_id').val(data.category_id);
    //         $('#brand_name').val(data.brand_name);
    // }

    // function category_update(data){
    //         $('#category_name').val(data.category_name);
    // }

    // function tax_update(data){
    //     $('#tax_name').val(data.tax_name);
    //     $('#tax_percentage').val(data.tax_percentage);
    // }


    // function unit_update(data){
    //     $('#unit_name').val(data.unit_name);
    // }

    // function supplier_update(data){
    //     $('#supplier_name').val(data.supplier_name);
    //     $('#supplier_email').val(data.supplier_email);
	// 	$('#supplier_contact_no').val(data.supplier_contact_no);
    //     $('#supplier_address').val(data.supplier_address);
    // }

    // function user_update(data){
    //         $('#user_name').val(data.username);
    //         $('#user_email').val(data.email);
    //         $('#user_type').val(data.user_type);
	// }


    function product_update(data){
        let select=findList(data.category_id)
        $('#brand_id').html(select).val(data.brand_id);
        easy_update(data)

        return
        // $('#category_id').val(data.category_id);
       
        
        // $('#product_name').val(data.product_name);
        // $('#product_description').val(data.product_description);
        // $('#product_quantity').val(data.opening_stock);
        // $('#product_base_price').val(data.product_base_price);
        // $('#product_unit').val(data.product_unit);
        // $('#span_item_details').html(data.item_details);
    }


        // function purchas_order_update(data){
        //     transaction_update(data,'purchase')
        // }

        // function sal_order_update(data){
        //     transaction_update(data)

        // }

        // function transaction_update(data,type='sale'){
        //     $('#'+type+'_name').val(data[type+'_name']);
        //     $('#'+type+'_date').val(data[type+'_date']);
        //     $('#'+type+'_address').val(data[type+'_address']);
        //     $('#span_item_details').html(data.item_details);
        //     $('#payment_status').val(data.payment_status);
        // }

        function easy_update(data) {
            $.each(data, function(key, value) {
                let $element = $(`[name="${key}"]`);                
                if ($element.is(":checkbox")) {                    
                  $element.prop("checked", $element.val() == value||['1','active','yes'].includes(value));
                } else if ($element.is(":radio")) {
                    $element.filter(`[value="${value}"]`).prop("checked", true);
                 }
                else if ($element.is('textarea') && typeof CKEDITOR !== 'undefined' 
                        && CKEDITOR.instances[key]) {
                        CKEDITOR.instances[key].setData(value);                    
                }
                else {
                    $element.val(value);
                }
                if(!$element.length)
                $(`#span_${key}`).html(value)
            });
        }

    /*update data section end*/

</script>

