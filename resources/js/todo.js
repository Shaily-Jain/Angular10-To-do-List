$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    window.location.reload();

    $("#category").select2({
        placeholder: "Category",
        allowClear: true
    });

    // add to-do item
    $('#store_data').on('submit', function(e){
        e.preventDefault();
        if ($('#name').val() == '') {
            alert('Please type to-do item name');
            $('#name').focus();
        }
        else if ($('#category_id').val() == '') {
            alert('Please select category');
            $('#category_id').focus();
        }
        else{
            var formData = new FormData(this);
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data:  formData,
                dataType: 'text',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $('.loading').show();
                  },
                success:function(res)
                {
                    $('.loading').show();
                    if(res.status == 'success'){
                        alert('To-do item added successfully!!')
                    }
                    $('.loading').hide();
                    window.location.reload();
                    $('.name').val('');
                    $('.category_id').val('');
                },
                error: function (data) {
                    $('.loading').hide();
                  },
                  complete: function () {
                    $('.form-control').val('');
                  },
            });
        }
    });

    // delete to-do item
    $('#delete_todo').on('submit',function(e){
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })

        var url = $(this).attr('action');

        $.ajax({
            type: "DELETE",
            url: url,
            data : {
                id : $(this).val()
            },
            dataType: 'text',
            contentType: false,
            beforeSend: function () {
                $('.loading').show();
              },
            success: function (data) {
                $('.loading').show();
                if(data.status == 'success'){
                    alert('To-do item deleted successfully!!')
                }
                $('.loading').hide();
                $("#todo_item_id").remove();
                window.location.reload();
            },
            error: function (data) {
                $('.loading').hide();
            }
        });
    });
});