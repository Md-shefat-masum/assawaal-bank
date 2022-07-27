window.custom_active_td_check = function () {
    setTimeout(() => {
        $('table tr td, table tr th').on('click', function () {
            console.log(
                $(this),
                $(this).children('input[type="checkbox"')
            );
            if ($(this).children('input[type="checkbox"').length) {

                // $($(this).children('input[type="checkbox"')[0]).checked = true ;
                $($(this).children('input[type="checkbox"')[0]).trigger('change');
            }

            // $(this).children('input[type="checkbox"').trigger('click');
        });

    }, 300);
}
