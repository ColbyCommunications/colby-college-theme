document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('#gform_12').addEventListener('submit', function (event) {
        var editor = tinymce.get('input_12_1');
        if (editor) {
            var content = editor.getContent();
            content = content.replace(/”/g, '"');
            content = content.replace(/\"(http[s]?:\/\/[^\s]+)\"/g, '$1');
            editor.setContent(content);
        }
    });
});
