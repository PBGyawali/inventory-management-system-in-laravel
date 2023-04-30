		</div>
	</body>
</html>
<script>
    timeout();
    var columns = [];
    var order=['0','desc'];
    var method_type='';
    var from_date='';
    var to_date='';
    var product_list;
    var tax_list;
    var brand_list;
    var datatable='';
    var autoRefresh=true;
    var hideTimeoutId;
    var clearTimeoutId;

    var inputs = $(':input').filter(function() { // use * if not :input specific
    return Array.from(this.attributes)
        .some(a => a.nodeName.startsWith('data-parsley-'))
    })
    //similar result as above
    var attrCount = document.evaluate("count(//@*[starts-with(name(), 'data-parsley-')])", document)
    var attrcountnumber=attrCount.numberValue//this gives the count
    if(inputs.length>0){
        $('#form').parsley();
    }
    var url=$('#form').attr('action');

    if(url){
        url=modifyUrl(url)
    }

    listurl=url+'/list';
	var fetchurl=url+"/max";

    function defineDateRange(start='',end=''){
        from_date=start
        to_date=end
    }


    function callback(...args) {
        if (args.length === 0||args.length === 1) {
            method_type = args;
        } else {
            method_type="/" + args.join("/");
        }
    }


    $('#toggle_refresh').click(function(e){
        autoRefresh = !autoRefresh;
        $('#toggle_refresh').attr('title',`Toggle auto refresh. Currently set to ${autoRefresh}`)
                        .toggleClass('btn-danger btn-success')
                        .find('i').toggleClass('fa-toggle-on fa-toggle-off');

    });

    function findList(number,element='brand',checkColumn='category'){
            // must have variable declared with the format ${element}_list
            let output = '';
            var total=0;
            let checkedList=window[element+'_list'];
            if(checkedList){
                for (i = 0; i < checkedList.length; i++) {
                    if(checkedList[i][checkColumn+'_id']==number){
                    output += '<option value="'+checkedList[i][element+'_id']+'">'+checkedList[i][element+'_name']+'</option>';
                    total++;
                    }
                }
                if(total==0)
                        output = '<option value="">No '+element+' Found</option>';
            }
            return output;
    }

    function modifyUrl(currentUrl, ...replaceArgs) {
        if (!currentUrl.startsWith("http://localhost/")) {
            currentUrl = currentUrl.replace("http", "https");
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
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $( document ).ajaxSend(function() {
            $('.errormessage').html('');
        });
    $( document ).ajaxError(function( event, request, settings, thrownError ) {
        if (request.status == 422) {
            showMessage(request.responseJSON.message);
                if(!$('.login').length>0){
                        $.each(request.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="'+i+'"]');
                        el.after($('<span class="text-danger errormessage">'+error[0]+'</span>'));
                    });
                }
        }
        else if ([401].includes(request.status)) {
            showMessage(request.responseJSON.message);
        }
        else if (request.status == 419) {
            showMessage(request.responseJSON.message+ ' Please relogin or refresh');
        }
        else if(request.responseText!='')
            alert(request.responseText)
    });
    $( document ).ajaxComplete(function() {
        enableButton()
    })



    if(typeof listurl!='undefined' && listurl){
            let allowedDomain=['purchase','sales','product'];
            var listPage= allowedDomain.some(function(allowedDomain) {
                return listurl.indexOf(allowedDomain) !== -1;
            });
            if(listPage){
                listurl=modifyUrl(listurl);
                ajaxCall(listurl).then(function(result) {
                    $.each(result, function(key, value){
                    window[key+'_list']=value
                })
            })
        }
    }


    //create an array of columns for datatables to
    //create data automatically according to the current table headers

    $("th").each(
        function ()
            {
                var classname=$(this).attr('class')
                if(classname){
                        var th =classname.split(' ')[0]
                        var th2 =classname.split(' ')[1]
                        var th3 =classname.split(' ')[2]

                    if(th=='action'){
                        columns.push({data: th,name:th,orderable:false,searchable:false})
                    }
                    else if(th2=="admininfo"){
                        columns.push({
                            "className":th2,
                            "data":th,
                            "defaultContent": '',
                            'visible' :$('.'+th2).length > 0?true:false,
                            "render": function (data)
                            {
                                return  ($('.'+th2).length > 0)?data:'';
                            }
                        });
                    }
                    else{
                        columns.push({data: th,name:th})
                    }
                    if(th2=='order'){
                        var lastIndex = columns.length - 1;
                        order=[lastIndex,th3||'desc'];
                    };
                }
            }
    );


    //launch datatables only if table exist in page
    if($('#table').length>0){
        var datatable= $('#table').DataTable({
                "processing":true,
                "serverSide":true,
                "order":order,
                "ajax" : {
                            url:url,
                            type:"POST",
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
        $submit= $form.find("input[type=submit]");
        //changing the submit button values from edit, please wait etc to add
        $button.html('<i class="fa fa-paper-plane"></i>'+ " Store "+element).attr('disabled', false);
        $submit.val('<i class="fa fa-paper-plane"></i>'+  " Store "+element).attr('disabled', false);
        $('.btn').attr('disabled', false);
        //adding extra string to current base url
        callback('/create');
        $('#publish,#anonymity').attr('checked',false);
      	$("#featured_image").prop('required', true);
        $('.errormessage,#span_item_details,#append_ticket,#form_message,#append_comment').html('');
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
        element = element.toLowerCase().replace(/\s/g, "_");
        if(typeof window['add_row_'+element] == 'function')
            window['add_row_'+element]();
        //if add_row function exist in the page then call it
        else if ( typeof add_row == 'function' )
            add_row();
            $(modal).modal('show');
    });


    $(document).on('click', '.delete', function(){
        //get id attribute if it is not present then take from the data attribute
        let id = $(this).attr("id")||$(this).data('id');
        let data={};
        let finalurl=url+'/'+id+'/delete'
        disable(finalurl,datatable,data,'delete the data','DELETE');
    });

    $(document).on('click', '.status', function(){
        //get id attribute if it is not present then take from the data attribute
        let id = $(this).attr("id")||$(this).data('id');
        let status = $(this).data('status');
        //table prefix to add to create the column name
        let tableprefix=$(this).data('prefix');
        //final url to use to send the request
        let finalurl=url+'/'+id+'/update'
        let data={}
        let column='status'
        let change="inactive";
        if(tableprefix)
            tableprefix +='_'
        if (status=='inactive')
            change="active";
            //add the table prefix and column variable to make the column name
            data[tableprefix+column] =change;
        disable(finalurl,datatable,data,'change the status');
    });

    $(document).on('click', '.update', function(){
        let id = $(this).attr("id")||$(this).data('id')
        var element = $(this).data("prefix");
        let modal=$(this).data('target') ||'#Modal';
        $form=$(modal).find('form');
        $form[0].reset();
        $form.parsley().reset();
        var finalurl=url+'/'+id+'/edit'
        finalurl = modifyUrl(finalurl);
        ajaxCall(finalurl).then(function(result) {
            callback(id,'update');
            if($('.tickets').length<=0){
                    $(modal).modal('show');
            }
            $form=$(modal).find('form');
            $button= $form.find("button[type=submit]");
            $submit= $form.find("input[type=submit]");
            $button.html("Edit " +element);
            $submit.val("Edit "+element);
            $('#span_tax_details,.object_details').html('');
            $('#user_password,#featured_image').attr('required', false);
            $('.btn').attr('disabled', false);
            $title=$(modal).find('.modal-title');
            $title.html("<i class='fas fa-edit'></i> Edit " +element);
            element = element.toLowerCase().replace(/\s/g, "_");
            if (typeof window[element+'_update']== "function") {
                window[element+'_update'](result);
            }
            else
            update(result);
        })
    });

    $(document).on('click', '.view', function(){
        let id = $(this).attr("id")||$(this).data('id')
        var finalurl=url+'/'+id+'/show'
        ajaxCall(finalurl).then(function(result) {
            $('#detailsModal').modal('show');
            $('#modal_item_details').html(result);
        })
    });

    $(document).on('submit','#form,#second_form,.form', function(event){
        event.preventDefault();
        var finalurl=url+method_type;
        var form_data = new FormData(this);
        $form=$(this);
        $button= $form.find("button[type=submit]");
        $submit= $form.find("input[type=submit]");    //$(document.activeElement);
        buttonvalue=$button.html();
        submitvalue=$submit.val();
        if($form.parsley().isValid())
        {
            ajaxCall(finalurl,form_data).then(function(result) {
                        if($('.login').length>0){
                            update(result)
                            return
                        }
                        if($('.no-close').length<=0){
                            $('#Modal,.modal').modal('hide');
                        }
                        if($('.reset,.no-reset').length<=0){
                            $form[0].reset()
                        }
                        $form.parsley().reset();
                        $('#span_tax_details,.item_details').html('');
                        $('.file_upload,.password').val('');
                        $button.html(buttonvalue);
                        $submit.val(submitvalue);
                }).catch(function(error) {
                    $button.html(buttonvalue);
                    $submit.val(submitvalue);
                });
        }
    });


        $(document).on('change', '.quantitypicker', function(){
			let input=$(this);
            var Count=input.data('count');
            var selectId=$('#product_id'+Count).val();
            var currentValue=input.val();
            totalmax=0;
            realMax=input.data('max')*1;//457
            currentmax=realMax;
            if ($('select option[value="' + selectId + '"]:selected').length > 1) {
                $('.selectpicker').each(function ()
                {
                    nonSelect=$(this);
                    var nonSelectId=nonSelect.val();
                    var nonSelectCount=nonSelect.data('count');
                    if(nonSelectId==selectId && Count!=nonSelectCount){
                        var quantity=$('#quantity'+nonSelectCount).val();//100+100+50-50
                        if(quantity)
                        totalmax+= quantity*1//250-50
                    }
                });
                currentmax=realMax-totalmax;//457-250+50=307
            }
            if(currentmax<=0)
            currentmax=0;
            if(currentmax==0)
            input.attr('min',0);
            input.attr('max',currentmax);
            if(currentValue>currentmax)
              input.val(currentmax);
		});

        $(document).on('change', '.selectpicker', function(){
			select=$(this);
            var selectId=select.val();
            var Count=select.data('count');
            var currentMax=$('#quantity'+Count).attr('max');
            totalmax=0;
            if ($('select option[value="' + selectId + '"]:selected').length > 1||currentMax) {
            $('.selectpicker').each(function ()
            {
                nonSelect=$(this);
                var nonSelectCount=nonSelect.data('count');
                var nonSelectId=nonSelect.val();
                currentValue=$('#quantity'+nonSelectCount).val()
                if(nonSelectId==selectId && select.attr('id')!= nonSelect.attr('id')){
                    if(currentValue)
                    totalmax+= currentValue*1
                }
            });
            }
			$quantityform=$('#quantity'+Count);
            var form_data = new FormData();
            form_data.append('product_id',selectId)
            ajaxCall(fetchurl,form_data).then(function(result) {
                currentmax=result-totalmax;
                $quantityform.attr('max',currentmax*1);
                $quantityform.data('max',result);
                if(currentmax<=0)
                    currentmax=0;
                var currentValue=$quantityform.val();
                if(currentValue>currentmax)
                    $quantityform.val(currentmax);
            })
		});

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
            var row_no = $(this).attr("id")||$(this).data("id");
            $('#row'+row_no).hide();
            $('#item_details_row'+row_no).remove();
        });

    $('#category_id').change(function(){
        var category_id = $('#category_id').val();
        data=findList(category_id)
        $('#brand_id').html(data);
    });

    function enableButton(value=false){
        $('.btn,button').attr('disabled', false);
        $('.btn,button').css({"filter": "","-webkit-filter": ""});
        enableText(value);
    }
    function enableText(value=false,buttonvalue=''){
        if (!value)
        timeout();
        if (buttonvalue)
            $('#btn').html(buttonvalue);
        $('#hint').html('Login hint');
    }
    function disableButton(element='button[type="submit"].btn'){
        $(element).css({"filter": "grayscale(100%)","-webkit-filter": "grayscale(100%)"});
        $(element).attr('disabled', 'disabled');
        $(element).html('Please wait...');
        $(element).val('Please wait...');

    }
    function  hide()
	{
        $('.error, .message, .alert').slideUp();
    }

    function clear(){
        $('#message,#alert_action,#form_message,.alert').html('')
    }
    function timeout(datatable='')
	{
        clearTimeout(hideTimeoutId);
        clearTimeout(clearTimeoutId);

        hideTimeoutId = setTimeout(function(){hide();}, 10000);
        clearTimeoutId = setTimeout(function(){clear();}, 15000);

        if(datatable && autoRefresh) {
            datatable.ajax.reload();
        }
    }

    function result(data,dataTable=''){
        $('.errormessage').html('');
		$('#alert_action,#message,#form_message').fadeIn().html(data);
		timeout(dataTable);
    }

    $('#refresh').click(function(){
  		$('#from_date,#to_date').val('');
          defineDateRange();
          datatable.ajax.reload();
  	});


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
                reporturl=modifyUrl(currentUrl,':from_date', from_date,':to_date',to_date)
                window.open(reporturl);
        }
        else{
            defineDateRange(from_date, to_date);
            datatable.ajax.reload();
        }
  	});

	function disable(url,datatable,data,message="change the status",postmethod='POST'){
        $.confirm
        ({
            title: 'Confirmation please!',
            content: "This will "+ message+". Are you sure?",
			type: 'blue',
            buttons:{
						Yes: {
							btnClass: 'btn-blue',
							action: function() {
								ajaxCall(url,data,postmethod);
							}
						},
					}
        });
    }

    function current_date(separator = '-',format = 'yyyy-mm-dd') {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');//January is 0!
        const dd = String(today.getDate()).padStart(2, '0');

        if (format === 'yyyy-mm-dd') {
            return `${yyyy}${separator}${mm}${separator}${dd}`;
        } else if (format === 'dd-mm-yyyy') {
            return `${dd}${separator}${mm}${separator}${yyyy}`;
        } else if (format === 'mm-dd-yyyy') {
            return `${mm}${separator}${dd}${separator}${yyyy}`;
        } else {
            throw new Error(`Invalid date format: ${format}`);
        }
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
	   }


    function ajaxCall(sendUrl,sendData=[],postmethod='POST') {
        sendUrl=modifyUrl(sendUrl)
            return new Promise(function(resolve, reject) {
                let contentType="application/x-www-form-urlencoded; charset=UTF-8";
                let processData=true;
                if (sendData.constructor === FormData) {
                    contentType=false;
                    processData=false;
                }
                $.ajax({
                    url: sendUrl,
                    method:postmethod,
                    data:sendData,
                    dataType:"JSON",
                    contentType:contentType,
                    processData:processData,
                    success: function(response) {
                        if(typeof response=="object"){
                            let responseKeys=['error','response','success']
                            let classKeys=['danger','success','success']
                            $.each(responseKeys, function(key, value){
                                if (value in response && response[value]!=''){
                                    if (response[value].includes('<div>')) {
                                        result(response[value],datatable);
                                    } else {
                                        let classValue=classKeys[key];
                                        showMessage(response[value],classValue,datatable);
                                    }
                                }
                            });
                            if ("image" in response && response.image!=''){
                                $('#profile_image').attr('src',response.image);
                            }
                            if ("redirect" in response && response.redirect!=''){
                                window.location.assign('.'+response.redirect);
                            }
                            if ("update" in response && response.update!=''){
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

    function showMessage(message,type='danger',dataTable='')
	{
        let response=
        `<div class="alert alert-${type} alert-dismissible fade show">
            <button type="button" class="close" onclick="hide()">&times;</button>
                            ${message}
        </div>`
        result(response,dataTable)
	}

    function add_row_sales_order(count = '',purchase=null)
    {
        // create a template string with the HTML elements
        const template = `
        <span class="item_details " id="row${count}">
            <div class="row" id="item_details_row${count}">
                <div class="col-sm-8">
                    <select name="product_id[]" id="product_id${count}" data-count="${count}"
                        class="form-control ${purchase?'':'selectpicker'}" data-live-search="true" required>
                            ${product_list}
                    </select>
                </div>
                <div class="col-sm-3 px-0">
                    <input type="number" name="quantity[]" data-count="${count}"
                    id="quantity${count}"  min="1" class="form-control ${purchase?'':'quantitypicker'}" required />
                </div>
                <div class="col-sm-1 pl-0">
                    <button type="button" data-element="${purchase?'purchase':'sales'}_order"
                            id="${count?count:'add_more'}"
                            class="btn btn-${count?'danger remove':'success add_more'}">
                            ${count?'-':'+'}
                    </button>
                </div>
            </div>
        </span>`;
        // append the template to the DOM
        $('#span_item_details').append(template);
    }


    function add_row_purchase_order(count = '')
    {
        add_row_sales_order(count,true);
    }


    function add_row_product(count = '') {
        const html = `
            <span id="item_details_row${count}" class="item_details">
            <div class="row">
                <div class="col-md-11 pr-0">
                <select name="tax[]" id="tax${count}" class="form-control"
                    data-live-search="true" required>
                    ${tax_list}
                </select>
                </div>
                <div class="col-md-1 pl-0">
                    <button type="button" data-element="product"
                            id="${count?count:'add_more'}"
                            class="btn btn-${count?'danger remove':'success add_more'}">
                            ${count?'-':'+'}
                    </button>
                </div>
            </div>
            </span>`;
        $('#span_item_details').append(html);
    }




/*template section end*/

</script>
