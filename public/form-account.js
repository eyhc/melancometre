$('#deleteModal').click(function () {
    if ($('#password4') && $('#password4').val() !== "") {
        $('#modal').modal('show');
    }
    else {
        $('#password4').addClass('is-invalid');
    }
});

var get_data = (function() {
    if (!$('#password3') || $('#password3').val() === "") {
        $('#invalidData').html(mis);
        $('#password3').addClass('is-invalid');
    }
    else {
        $('#password3').removeClass('is-invalid');

        var xhr, form = new FormData();
        if (xhr != undefined && xhr != null) { xhr.abort(); }

        xhr = new XMLHttpRequest();
        xhr.open('POST', 'call.php');

        form.append('action', 'get_all_data');
        form.append('password', $('#password3').val());
        xhr.send(form);

        $('#password3').val('');

        xhr.addEventListener('readystatechange', function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                var json = JSON.stringify(JSON.parse(xhr.responseText), null, 2);
                downloadFile(json, 'data.json', 'application/json');
            }
            else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 403) {
                $('#invalidData').html(inv);
                $('#password3').addClass('is-invalid');
            }
        });
    }
});

$('#dataFormButton').click(get_data);
$('#password3').keyup(function(e){
    if(e.keyCode == 13) {
        get_data();
    }
});

function downloadFile(data, name = 'file', type = 'text/plain') {
    const anchor = document.createElement('a');
    anchor.href = window.URL.createObjectURL(new Blob([data], { type }));
    anchor.download = name;
    anchor.click();
}
