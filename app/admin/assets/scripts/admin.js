$(document).ready(function () {

    let $data = {};

    $('.module').on( "mouseenter", function (e) {
        $(this).toggleClass('list-group-item-primary');
    } ).on( "mouseleave", function () {
        $(this).toggleClass('list-group-item-primary');
    } );

    $('.option').on('change', function (event) {
        let optionId = $(this).data('option-id');
        if($(this).prop('type') === 'checkbox')
        {
            $data[optionId] = $(this).is(':checked');
        }else
        {
            $data[optionId] = $(this).val();
        }
    });

    $('.option-save').on('click', function (event) {
        hideNotifications();
        if(Object.keys($data).length !== 0)
        {
            $.ajax({
                url: '/admin/editoption',
                method: 'post',
                dataType: 'json',
                data: $data,
                success: function(data){
                    if(data.success)
                    {
                        $data = {};
                        showNotification('success');
                    }else
                    {
                        showNotification('warning');
                    }
                }
            });
        }
    });
    
    function showNotification(type) {
        $('.alert-' + type).show();
    }

    function hideNotifications() {
        $('.alert-success').hide();
        $('.alert-warning').hide();
    }
});