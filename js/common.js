$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

function buttonDisabled(target) {
    $(target).attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
}
function buttonDisabledWithoutDisabled(target) {
    $(target).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
}
function buttonEnabled(target, html) {
    $(target).attr('disabled', false).html(html);
}

$(document).on('click','.btn_logout',function(){
	buttonDisabled('.btn_logout');
});