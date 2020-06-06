$("body").on('dropify.beforeClear', function(event, element) {
    if (confirm("Do you really want to delete this image ?")) {
        var id = $(element.element).data('id');
        if (element.file.object && !id) return true;
        $.ajax({
            url: '{{ route('api.image.destroy') }}',
            method: 'POST',
            data: {id: id},
            success: function(data) {
                element.resetFile();
                element.input.val('');
                element.resetPreview();
            },
            error: function(data) {alert('Image cannot be deleted. Please refresh page & try again!'); }
        });
    }

    return false;
});