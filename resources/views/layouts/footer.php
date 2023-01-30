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
    var tax_list;
    var brands;
    var product_list;
    var datatable='';

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

    listurl=url+'/list';
	var fetchurl=url+"/max";
    function varname(start='',end=''){
        from_date=start
        to_date=end
    }
    function callback(response) {
        method_type = response;
    }

    function list(response) {
		product_list = response;
	}
    function calllist(response,secondlist=null) {
        tax_list = response;
        brands=	secondlist;
    }
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $( document ).ajaxSend(function() {
            $('.errormessage').html('');
            $('#action').attr('disabled', 'disabled');
        });
    $( document ).ajaxError(function( event, request, settings, thrownError ) {
        if (request.status == 422) {
            result('<div class="alert alert-danger text-center">'+request.responseJSON.message+'</div>');
                if(!$('.login').length>0){
                        $.each(request.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="'+i+'"]');
                        el.after($('<span class="text-danger errormessage">'+error[0]+'</span>'));
                    });
                }
        }
        else if ([401].includes(request.status)) {
            result('<div class="alert alert-danger text-center">'+request.responseJSON.message+ '</div>');
        }
        else if (request.status == 419) {
            result('<div class="alert alert-danger text-center">'+request.responseJSON.message+ ' Please relogin or refresh </div>');
        }
        else if(request.responseText!='')
            alert(request.responseText)
    });
    $( document ).ajaxComplete(function() {
        enableButton()
    })

    $("th").each(
    function ()
		{
            var classname=$(this).attr('class')
            var th =classname.split(' ')[0]
            var th2 =classname.split(' ')[1]
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
                })
            }
            else
            columns.push({data: th,name:th})
        }
);


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




    $('#add_button').click(function(){
        $('#form')[0].reset();
        var element=$(this).data('element')
        $('#form').parsley().reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add "+element);
        $('#Modal').modal('show');
        $('#action').val('Add');
        $('#action').attr('disabled', false);
        callback('/create');
        $('.errormessage,#span_item_details').html('');
        $('#product_tax').attr('required',false);
        $('#product_tax').hide();
        $('#brand_id').html('<option value="" >Select Category First</option>');
        if ( typeof add_row == 'function' )
            add_row();
    });


    $(document).on('click', '.delete', function(){
        var id = $(this).attr("id");
        if(!id)
            id = $(this).data('id');
    var data={};
    var finalurl=url+'/'+id+'/delete'
    disable(finalurl,datatable,data,'delete the data','DELETE');
  });

  $(document).on('click', '.status', function(){
        var id = $(this).attr("id");
        if(!id)
            id = $(this).data('id');
		var status = $(this).data('status');
        var tableprefix=$(this).data('prefix');
        var finalurl=url+'/'+id+'/update'
        var data={}
        var column='status'
		var change="inactive";
        if(tableprefix)
            tableprefix +='_'
		if (status=='inactive')
			change="active";
            data[tableprefix+column] =change;
		disable(finalurl,datatable,data,'change the status');
  	});

      $(document).on('click', '.update', function(){
        var id = $(this).attr("id");
        if(!id)
            id = $(this).data('id');
        var element = $(this).data("prefix");
        $('#form').parsley().reset();
        var finalurl=url+'/'+id+'/edit'
		$.ajax({
			url:finalurl,
			method:"POST",
			data:{unit_id:id},
			dataType:"json",
			success:function(data)
			{
                if ("error" in data && data.error!=''){
                        result(data.error);
                    return
                }
                callback('/'+id+'/update');
                $('#Modal').modal('show');
                $('#submit_button').html("Edit");
                $('#product_tax').attr('required','required');
                $('#span_tax_details').html('');
                $('#product_tax').show();
                $('#user_password').attr('required', false);
                $('#submit_button').attr('disabled', false);
                $('.modal-title').html("<i class='fas fa-edit'></i> Edit " +element);
                update(data);
			}
		})
    });

    $(document).on('click', '.view', function(){
        var id = $(this).attr("id");
        if(!id)
            id = $(this).data('id');
        var finalurl=url+'/'+id+'/show'
        $.ajax({
            url:finalurl,
            method:"POST",
            dataType:'json',
            data:{},
            success:function(data){
                $('#detailsModal').modal('show');
                $('#product_details').html(data);
            }
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
        $.ajax({
            url:finalurl,
            method:"POST",
            data:form_data,
            dataType:'json',
            contentType:false,
            processData:false,
            beforeSend:function()
            {
                disableButton($submit)
                disableButton($button)
            },
            complete:function()
            {
                $button.html(buttonvalue);
                $submit.val(submitvalue);
            },
            success:function(data)
            {
                if(typeof data=="string")
                {
                    result(data);
                    return
                }
                if ("error" in data && data.error!=''){
                    result(data.error);
                    return
                }
                if ("image" in data && data.image!=''){
                    $('#user_uploaded_image').html('<img src="'+data.image+'" class="img-thumbnail img-fluid rounded-circle" width="200" height="200" /><input type="hidden" name="hidden_user_image" value="'+data.image+'" />');
                }
                if ("redirect" in data && data.redirect!=''){
                    window.location.assign(data.redirect);
                }
                if($('.login').length>0){
                    update(data)
                    return
                }
                $('#Modal,.modal').modal('hide');
                if($('.reset').length<0){
                    $form[0].reset()
                }
                $form.parsley().reset();
                result(data.response,datatable)
                $('#span_tax_details,.item_details').html('');
                $('.file_upload').val('');
            }
        })
    }
});

$(document).on('change', '.selectpicker', function(){
			select=$(this);
            var id=select.val();
            totalmax=0;
            if ($('select option[value="' + $(this).val() + '"]:selected').length > 1) {
            $('.selectpicker').each(function ()
            {
                nonselect=$(this);
                maxvalue=nonselect.parent().siblings().find("input[type=number]").val()
                if(nonselect.val()==select.val() && select.attr('id')!= nonselect.attr('id')){
                    totalmax+= maxvalue*1
                }
            });
            }
			$quantityform=select.parent().siblings().find("input[type=number]");
			$.ajax({
				url:fetchurl,
				method:"POST",
				data:{product_id:id},
				dataType:"json",
				success:function(data){
                    currentmax=data-totalmax
					$quantityform.attr('max',currentmax);
				}
			});
		});

        $(document).on('click', '#add_more', function(){
            count = $('.item_details').length;
            count = count + 1;
            add_row(count);
        });
        $(document).on('click', '.remove', function(){
            var row_no = $(this).attr("id");
            $('#row'+row_no).hide();
            $('#item_details_row'+row_no).remove();
        });

       function brand(number){
        var output = '';
        var total=0;
        for (i = 0; i < brands.length; i++) {
            if(brands[i].category_id==number){
            output += '<option value="'+brands[i].brand_id+'">'+brands[i].brand_name+'</option>';
            total++;
            }
        }
        if(total==0)
                output = '<option value="">No brands Found</option>';
        return output;
    }

    $('#category_id').change(function(){
        var category_id = $('#category_id').val();
        data=brand(category_id)
        $('#brand_id').html(data);
    });

    function enableButton(value=false){
    $('.btn').attr('disabled', false);
    $('.btn').css({"filter": "","-webkit-filter": ""});
    enableText(value);
}
function enableText(value=false,buttonvalue=''){
	if (!value)
	timeout();
    if (buttonvalue)
        $('#btn').html(buttonvalue);
    $('#hint').html('Login hint');
}
function disableButton(element='.btn'){
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
		setTimeout(function(){hide();}, 7000);

		setTimeout(function(){clear();}, 10000);
		if(datatable)
		datatable.ajax.reload();
    }
	function showMessage(datatable=null,data)
	{
        result(data,datatable)
	}
    function result(data,dataTable=''){
        $('.errormessage').html('');
		$('#alert_action,#message,#form_message').fadeIn().html(data);
		timeout(dataTable);
    }
    $('#filter').click(function(){
  		from_date = $('#from_date').val();
        to_date = $('#to_date').val();
        if(from_date==''){
            alert('Start date range needs to be selected')
            return
          }
        if(to_date =='')
        to_date =current_date();
       varname(from_date, to_date);
        datatable.ajax.reload();
      });


  	$('#refresh').click(function(){
  		$('#from_date,#to_date').val('');
          varname();
          datatable.ajax.reload();
  	});
      $('#report').click(function(){
  		var from_date = $('#from_date').val();
          var url=$(this).data('url')
        var to_date = $('#to_date').val();
        if(from_date==''){
            alert('Start date range needs to be selected')
            return
          }
        if(to_date =='')
        to_date =current_date();
        var table=$(this).data('table')
        reporturl=url+'/'+from_date+'/'+to_date+'/'+table
        window.open(reporturl);
  	});

	  function printReport(response) {
	var mywindow = window.open('', '', 'height=400,width=600');
	mywindow.document.write('</head><body>');
	mywindow.document.write(response);
	mywindow.document.write('</body></html>');
	mywindow.document.close(); // necessary for IE >= 10
	mywindow.focus(); // necessary for IE >= 10
	mywindow.resizeTo(screen.width, screen.height);
		}// /success function

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
								$.ajax({
									url:url,
									method:postmethod,
									data:data,
                                    dataType:"JSON",
									success:function(response){
                                        if(typeof response=="string")
                                        {
                                            result(response);
                                            return
                                        }
                                        if ("error" in response && response.error!=''){
                                             result(response.error);
                                            return
                                        }
                                        else if ("response" in response)
                                            result(response.response,datatable);
                                        else if ("success" in response)
                                            result(response.success,datatable);
                                        else
                                        result(response,datatable);
									}
								});
							}
						},
					}
        });
    }

    function current_date(format='-'){
        var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            return yyyy+ format + mm + format + dd;
    }
</script>
