$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if ($(".ck-editor-textarea").length > 0) {
        // const csrfToken = $('meta[name="csrf-token"]').attr('content');
        document.querySelectorAll('textarea.ck-editor-textarea').forEach(textarea => {
            ClassicEditor
                // .create(textarea, {
                //     ckfinder: {
                //         uploadUrl: `${route('admin.static-content.article.ckEditorUpload')}?_token=${csrfToken}`,
                //     }
                // })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        textarea.value = editor.getData();
                    });

                    // editor.plugins.get("FileRepository").on("uploadError", (event, { message }) => {
                    //     console.error("Lỗi upload ảnh:", message);
                    //     alert("Không thể tải ảnh lên! Lỗi: " + message);
                    // });
                })
                .catch(error => {
                    console.error("CKEditor error:", error);
                });
        });
    }

    if ($(".tinymce_editor").length > 0) {
        tinymce.init({
            selector: '.tinymce_editor',
            toolbar: 'undo redo styleselect bold italic alignleft aligncenter alignright bullist numlist outdent indent code',
            plugins: 'code'
        });
    }

    window.utils = {
        clearSearchForm: function () {
            $("#form-search")[0].reset();
            $("#form-search input").val("");
            $("#form-search select").prop("selectedIndex", 0);
        },
        setTitle: function (title) {
            document.title = title;
        },
        changePhoto:  function (UploadImage, previewImg) {
            if (UploadImage.files && UploadImage.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(UploadImage.files[0]);
            }
        }
    }
});
