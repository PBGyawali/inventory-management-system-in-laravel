
$(document).ready(function(){


  $(".file_upload").change(function()
  {
          var extension = $(".file_upload").val().split('.').pop().toLowerCase();
          var upload_time=$(this).data('upload_time');
          var file = $(this)[0].files[0];
          var filesize = this.files[0].size;
          var allowed_file=$(this).data('allowed_file');
          if(extension != '')
          {
                if(jQuery.inArray(extension, allowed_file) == -1)
                {
                  return fileAlert("Invalid Image File type");
                }
                else if (filesize > 500000)
                {
                  return fileAlert("File Image size is too large");
                }
                else
                  {
                        var _URL = window.URL || window.webkitURL;
                        var image = new Image();
                        image.src = _URL.createObjectURL(file);
                        image.onload = function()
                        {
                            imgwidth = this.width;
                            imgheight = this.height;
                            if (imgheight + imgwidth == 0)
                                return fileAlert('This file is not an image');
                            else if ( upload_time=='now')
                                uploadNow();
                            else
                                return false;
                        }
                        image.onerror = function() {
                          return fileAlert(`This is not a valid ${extension} file`);
                        }
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            if(!$('.settings').length)
                            $('#profile_image,.profile_image').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(file);
                  };
          }
  });

  $(document).on('click','.delete_btn',function(event){
      event.preventDefault();
        $.confirm
        ({
            title: 'Delete Picture!',
            content: 'This will delete the data permanently. Are you sure?',
            buttons:
            {
                Yes:
                {//name of the function
                      action: function()
                      {
                          var url = $('#picture_upload').attr('action');
                          var form = $('form#picture_upload')[0]; // You need to use standard javascript object here
                          var data  = new FormData(form);
                          // If you want to add an extra field for the FormData
                          data.append("delete_picture", 1);
                          $.ajax
                          ({
                              url:url,
                              method:"POST",
                              data:data,
                              contentType:false,
                              processData:false,
                              dataType:"JSON",
                              success:function(data)
                              {
                                  $('.success_msg').text(data.success);
                                  $('.profile_image').attr('src',data.profile_image)
                                  $('[data-fancybox="gallery"]').attr('href',data.profile_image)
                                  $('#upload_icon_text').text(' Upload New');
                                  $('.delete_btn').remove();

                              }
                          });
                        }
                  },
            }
        });
    });

      $("a.logout,button.logout").click(function(event)
      {
          event.preventDefault();
          var url=$(this).attr('href');
          var form = $(this).closest('form');
          $.confirm
          ({
              title: 'Log Out!',
              content: 'This will log you out. Are you sure buddy?',
              buttons:
              {
                  Yes:
                  {//name of the function
                      action: function()
                      {
                        form.submit();
                        if(url!='' && typeof url!='undefined')
                        window.location.href =  url;
                      }
                  },

              }
           });
      });
    $("a#logout,button.logout").hover(function()
    {
      $(this).css({"background-color": "red","color":"white"});
    },
      function(){//return to original state when hover out
        $(this).css({"background-color": "","color":""});
    });


function fileAlert($content){
 return showAlert($content,'File selection Invalid')
}


    function uploadNow()
    {
        $.confirm
        ({
            title: 'Change Image!',
            content: 'This will change the image. Are you sure to proceed?',
            type: 'blue',
            buttons:
            {
                Yes:
                {//name of the function
                    btnClass: 'btn-blue',
                    action: function()
                    {
                        var url = $('#picture_upload').attr('action');
                        var form = $('form#picture_upload')[0]; // You need to use standard javascript object here
                        var data  = new FormData(form);
                        // If you want to add an extra field for the FormData
                        data.append("upload", 1);
                        $.ajax
                        ({
                            url:url,
                            method:"POST",
                            data:data,
                            contentType:false,
                            processData:false,
                            dataType:"JSON",
                            success:function(data)
                                  {     $('.profile_image').attr('src',data.profile_image)
                                        $('[data-fancybox="gallery"]').attr('href',data.profile_image)
                                        $('#upload_icon_text').text(' Change');
                                        $('#delete_div').html(data.button);

                                  }
                          });     //ajax call end
                    }
                },
            }
        });//confirm box
    }


});
