$(function () {
    function collectAdminFormData() {
        const formData = new FormData();
        formData.append('name', $('#name').val());
        formData.append('display_name', $('#display_name').val());
        formData.append('description', $('#description').val());

        $("input[name='permissions[]']:checked").each(function () {
            formData.append('permissions[]', $(this).val());
        });

        return formData;
    }
    $('#display_name').on('input', function () {
        const value = $(this).val();
        const kebab = value
            .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
            ?.map(x => x.toLowerCase()).join('-') ?? '';
        $('#name').val(kebab);
    });
    $('#submit-btn-create').on('click', function (e) {
        e.preventDefault();
        if (!rada360Role.validateRoleForm()) return;

        const formData = collectAdminFormData();
        rada360Role.createRole(formData);
    });
    $('#submit-btn-edit').on('click', function (e) {
        e.preventDefault();
        if (!rada360Role.validateRoleForm()) return;

        const formData = collectAdminFormData();
        const roleId = $(this).data('roleId');
        rada360Role.updateRole(formData, roleId);
    });
    $('.delete-role-btn').off().on('click', function () {
       const id = $(this).data('id');
       rada360Role.deleteRole(id);
    });
    $('.select-all-group').on('change', function () {
        const group = $(this).data('group');
        $('.permission-checkbox.' + group).prop('checked', $(this).is(':checked'));
    });
    $('.permission-checkbox').on('change', function () {
        const group = [...this.classList].find(cls => cls !== 'permission-checkbox');
        const all = $('.permission-checkbox.' + group);
        const allChecked = all.length && all.filter(':checked').length === all.length;
        $('#selectAll-' + group).prop('checked', allChecked);
    });
    $('.select-all-group').each(function () {
        const group = $(this).data('group');
        const checkboxes = $('.permission-checkbox.' + group);
        $(this).prop('checked', checkboxes.length && checkboxes.filter(':checked').length === checkboxes.length);
    });
});

(function (exports, global) {
    exports.validateRoleForm = function () {
        const roleForm = $("#roleForm");
        if (roleForm.length === 0) {
            return;
        }

        roleForm.validate({
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').find('span.input-invalid').remove();
                $(element).parents('.form-group').append(error);
            },
            ignore: ':hidden:not(.validate)',
            errorElement: "span",
            errorClass: "input-invalid",
            rules: {
                display_name: {
                    required: true,
                }
            },
            messages: {
                display_name: {
                    required: "Tên role không được để trống",
                }
            },
            invalidHandler: function (form, validator) {
                let errors = validator.numberOfInvalids();
                if (errors) {
                    let firstInvalidElement = $(validator.errorList[0].element);
                    $('html,body').scrollTop(firstInvalidElement.offset().top);
                    firstInvalidElement.focus();
                }
            },
            submitHandler: function (form) {
                return false;
            },
            success: function (label, element) {
                $(element).parents('.form-group').find('span.input-invalid').remove();
            }
        });

        const hasPermissionChecked = $("input[name='permissions[]']:checked").length > 0;
        if (!hasPermissionChecked) {
            radaAlert.showSwalError("Vui lòng chọn ít nhất một quyền (permission).");
            return false;
        }

        return $("#roleForm").valid();
    };
    exports.createRole = function (data) {
        $.ajax({
            url: route('admin.role.store'),
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    radaAlert.showSwalSuccess('Thêm Role thành công!').then(function () {
                        window.location.href = route('admin.role.index');
                    });
                } else if (response.error) {
                    radaAlert.showSwalError(response.error);
                } else {
                    radaAlert.showSwalError('Cập nhật Role không thành công, vui lòng thử lại!');
                }
            },
            error: function (response) {
                let errorMessage = 'Đã xảy ra lỗi. Vui lòng kiểm tra lại dữ liệu nhập vào.';
                if (response && response.responseJSON) {
                    const responseData = response.responseJSON;
                    if (responseData.errors) {
                        errorMessage = Object.values(responseData.errors).flat().join('<br>');
                    } else if (responseData.message) {
                        errorMessage = responseData.message;
                    }
                }
                radaAlert.showSwalError(errorMessage);
            }
        });
    };
    exports.updateRole = function (data, id) {
        $.ajax({
            url: route('admin.role.update', id),
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    radaAlert.showSwalSuccess('Cập nhật Role thành công!').then(function () {
                        window.location.href = route('admin.role.index');
                    });
                } else if (response.error) {
                    radaAlert.showSwalError(response.error);
                } else {
                    radaAlert.showSwalError('Cập nhật Role không thành công, vui lòng thử lại!');
                }
            },
            error: function (response) {
                let errorMessage = 'Đã xảy ra lỗi. Vui lòng kiểm tra lại dữ liệu nhập vào.';
                if (response && response.responseJSON) {
                    const responseData = response.responseJSON;
                    if (responseData.errors) {
                        errorMessage = Object.values(responseData.errors).flat().join('<br>');
                    } else if (responseData.message) {
                        errorMessage = responseData.message;
                    }
                }
                radaAlert.showSwalError(errorMessage);
            }
        });
    };

    exports.deleteRole = function(roleId) {
        radaAlert.showSwalWarning("Bạn có chắc chắn muốn xóa Role này?").then(function (confirm) {
            if (confirm.isConfirmed) {
                $.ajax({
                    url: route("admin.role.destroy", roleId),
                    type: "POST",
                    data: { _method: "DELETE", _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        if (response.success) {
                            radaAlert.showSwalSuccess("Xoá role thành công").then(function (confirm) {
                                if (confirm.isDismissed) {
                                    location.reload();
                                }
                            });
                        } else {
                            radaAlert.showSwalError(response.message);
                        }
                    },
                    error: function (xhr) {
                        const response = JSON.parse(xhr.responseText);
                        radaAlert.showSwalError(response.message || "Có lỗi xảy ra");
                    },
                });
            }
        });
    }
})(window.rada360Role = window.rada360Role || {}, window);
