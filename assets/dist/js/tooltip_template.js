jQuery(function()
{
   $('[data-toggle="tooltip"]').each(function()
    {
        var headName = $(this).attr('data-field');
        if (typeof headName !== typeof undefined && headName !== false) {
            headName;
        }else{
            headName = 'Field Info';
        }
        if ($(this).val().length <=0)
        {
            $(this).tooltip(
            {
                template : '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-head t-head"><h3><i class="fa fa-info-circle"> '+headName+'</h3></div><div class="tooltip-inner t-inner"></div></div>'
            });
        }
        $(this).on('keyup',function()
        {
            if ($(this).val().length <=0)
            {
                $(this).tooltip(
                {
                    template : '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-head t-head"><h3><i class="fa fa-info-circle"> '+headName+'</h3></div><div class="tooltip-inner t-inner"></div></div>'
                });
                if($(this).focus()){
                    $(this).tooltip(
                    {
                        template : '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-head t-head"><h3><i class="fa fa-info-circle"> '+headName+'</h3></div><div class="tooltip-inner t-inner"></div></div>'
                    });
                }
            }
            else
            {
                $(this).tooltip('hide');     
            }
        });
    });
});