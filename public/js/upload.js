$(document).ready(function () {
    $('#jquery-wrapped-fine-uploader').fineUploader({
    request: {
    endpoint: 'server/handleUploads'
    },
    debug: true
    });
});