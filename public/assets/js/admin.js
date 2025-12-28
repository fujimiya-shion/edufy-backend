$(document).ready(function () {
    rada360Admin.validateFormEdit();
    rada360Admin.validateFormCreate();
    function collectAdminFormData(isUpdate = false) {
        let formData = new FormData();
        formData.append('name', $('#name').val());
        formData.append('email', $('#email').val());
        formData.append('status', $('#status').val());

        if (!isUpdate || $('#password').val()) {
            formData.append('password', $('#password').val());
            formData.append('password_confirmation', $('#password-confirm').val());
        }
        const avatarFile = $('.avatar-admin')[0]?.files[0];
        if (avatarFile) {
            formData.append('avatar', avatarFile);
        }
        $("input[name='roles[]']:checked").each(function () {
            formData.append('roles[]', $(this).val());
        });
        return formData;
    }
    $('#createAdmin').off().on('click', function (e) {
        e.preventDefault();
        if($("#admins-create").valid()) {
            let formData = collectAdminFormData();
            rada360Admin.createAdmin(formData);
        }
    });
    $('#updateAdmin').off().on('click', function (e) {
        e.preventDefault();
        if($("#admins-edit").valid()) {
            let adminId = getAdminIdFromUrl();
            let formData = collectAdminFormData(true);
            console.log(formData)
            rada360Admin.updateAdmin(formData , adminId);
        }
    });
    $('.deleteAdmin').off().on('click', function (e) {
        e.preventDefault();
        let adminId = $(this).data("admin-id");
        console.log(adminId)
        rada360Admin.deleteAdmin(adminId);
    });
    $('#resetPasswordBtn').click(function() {
        const adminId = $(this).data('id');
        rada360Admin.resetPassword(adminId);
    });
    $('#toggle-password').on('click', function () {
        rada360Admin.eyePwdFunction($(this));
    });
    $('#toggle-password-confirm').on('click', function () {
        rada360Admin.eyePwnConfirmFunction($(this));
    });
    $('#selectAllRoles').on('change', function () {
        const isChecked = $(this).is(':checked');
        $('.role-checkbox:not(:disabled)').prop('checked', isChecked);
    });
    $('.role-checkbox').on('change', function () {
        const all = $('.role-checkbox:not(:disabled)');
        const checked = $('.role-checkbox:checked:not(:disabled)');
        $('#selectAllRoles').prop('checked', all.length === checked.length);
    });
    const total = $('.role-checkbox:not(:disabled)').length;
    const selected = $('.role-checkbox:checked:not(:disabled)').length;
    $('#selectAllRoles').prop('checked', total > 0 && total === selected);
});
function getAdminIdFromUrl() {
    const url = window.location.pathname;
    const urlParts = url.split('/');
    return urlParts[urlParts.length - 1];
}
(function (exports, global) {
    exports.validateFormCreate = function (){
        $("#admins-create").validate({
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').find('span.input-invalid').remove();
                $(element).parents('.form-group').append(error)
            },
            ignore: ':hidden:not(.validate)',
            errorElement: "span",
            errorClass: "input-invalid",
            rules: {
                name: {
                    required: true,
                    remote: {
                        url: route('admin.admin-manage.checkName'),
                        type: "POST",
                        data: {
                            name: function () {
                                return $('#name').val();
                            }
                        }
                    }
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: route('admin.admin-manage.checkEmail'),
                        type: "POST",
                        data: {
                            email: function () {
                                return $('#email').val();
                            }
                        }
                    }
                },
                password: {
                    required: true,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
            },
            messages: {
                name: {
                    required: "Họ và tên không được để trống",
                    remote: "Tên này đã được sử dụng",
                },
                email: {
                    required: "Vui lòng nhập email",
                    email: "Email không hợp lệ",
                    remote: "Email này đã được sử dụng",
                },
                password: {
                    required: "Mật khẩu không được để trống",
                },
                password_confirmation: {
                    required: "Mật khẩu xác nhận không được để trống",
                    equalTo: "Mật khẩu xác nhận không trùng khớp"
                },
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
                form.submit();
            },
            success: function (label, element) {
                $(element).parents('.form-group').find('span.input-invalid').remove();
            }
        });
    }
    exports.validateFormEdit = function (){
        const adminId = getAdminIdFromUrl();
        $("#admins-edit").validate({
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').find('span.input-invalid').remove();
                $(element).parents('.form-group').append(error)
            },
            ignore: ':hidden:not(.validate)',
            errorElement: "span",
            errorClass: "input-invalid",
            rules: {
                name: {
                    required: true,
                    remote: {
                        url: route('admin.admin-manage.checkName'),
                        type: "POST",
                        data: {
                            name: function () {
                                return $('#name').val();
                            },
                            id: adminId
                        }
                    }
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: route('admin.admin-manage.checkEmail'),
                        type: "POST",
                        data: {
                            email: function () {
                                return $('#email').val();
                            },
                            id: adminId
                        }
                    }
                },
                password_confirmation: {
                    equalTo: "#password"
                },
            },
            messages: {
                name: {
                    required: "Họ và tên không được để trống",
                    remote: "Tên này đã được sử dụng",
                },
                email: {
                    required: "Vui lòng nhập email",
                    email: "Email không hợp lệ",
                    remote: "Email này đã được sử dụng",
                },
                password_confirmation: {
                    equalTo: "Mật khẩu xác nhận không trùng khớp"
                },
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
                form.submit();
            },
            success: function (label, element) {
                $(element).parents('.form-group').find('span.input-invalid').remove();
            }
        });
    }
    exports.createAdmin = function (data) {
        $.ajax({
            url: route("admin.admin-manage.store"),
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    radaAlert.showSwalSuccess("Thêm Admin thành công!").then(function () {
                        window.location.href = route("admin.admin-manage.index");
                    });
                }
            },
            error: function (response) {
                let errorMessage = "Đã xảy ra lỗi. Vui lòng kiểm tra lại dữ liệu nhập vào.";
                if (response && response.responseJSON) {
                    const responseData = response.responseJSON;
                    if (responseData.errors) {
                        errorMessage = Object.values(responseData.errors)
                            .flat()
                            .join("<br>");
                    } else if (responseData.message) {
                        errorMessage = responseData.message;
                    }
                }
                radaAlert.showSwalError(errorMessage);
            }
        });
    }
    exports.updateAdmin = function (data, id) {
        $.ajax({
            url: route("admin.admin-manage.update", id),
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    radaAlert.showSwalSuccess("Cập nhật Admin thành công!").then(function () {
                        window.location.href = route("admin.admin-manage.index");
                    });
                } else if(response.error)
                {
                    radaAlert.showSwalError(response.error);
                }else {
                    radaAlert.showSwalError("Cập nhật Admin không thành công, vui lòng thử lại!");
                }
            },
            error: function (response) {
                let errorMessage = "Đã xảy ra lỗi. Vui lòng kiểm tra lại dữ liệu nhập vào.";
                if (response && response.responseJSON) {
                    const responseData = response.responseJSON;
                    if (responseData.errors) {
                        errorMessage = Object.values(responseData.errors)
                            .flat()
                            .join("<br>");
                    } else if (responseData.message) {
                        errorMessage = responseData.message;
                    }
                }
                radaAlert.showSwalError(errorMessage);
            }
        });
    }
    exports.deleteAdmin = function (adminId) {
        radaAlert.showSwalWarning("Bạn có chắn chắc muốn xóa tài khoản Admin này?").then(function (confirm) {
            if (confirm.isConfirmed) {
                fetch(`/admin/admin-manage/delete/${adminId}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        "Content-Type": "application/json",
                    },
                })
                    .then((response) => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error("Failed to delete Admin");
                        }
                    })
                    .then((data) => {
                        if (data.success) {
                            radaAlert.showSwalSuccess("Xoá Admin thành công!").then(function () {
                                location.reload();
                            }, 1000);
                        } else {
                            radaAlert.showSwalError("Xoá Admin không thành công, vui lòng thử lại!");
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        radaAlert.showSwalError("Xoá Admin không thành công, vui lòng thử lại!");
                    });
            }
        });
    };
    exports.eyePwdFunction = function(icon) {
        const passwordField = $('#password');
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    };

    exports.eyePwnConfirmFunction = function(icon) {
        const passwordConfirmField = $('#password-confirm');
        if (passwordConfirmField.attr('type') === 'password') {
            passwordConfirmField.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            passwordConfirmField.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    };
    exports.resetPassword = async function (id) {
        radaAlert.showSwalWarning("Bạn có chắc chắn muốn reset mật khẩu?").then(function (confirm) {
            if (confirm.isConfirmed) {
                try {
                    $.ajax({
                        url: route("admin.admin-manage.resetPassword", id),
                        success: function (response) {
                            console.log("data: ", response.data);
                            if (response.success) {
                                radaAlert.showSwalSuccess(response.message).then(function (confirm) {
                                    if (confirm.isDismissed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                radaAlert.showSwalError(response.message);
                            }
                        },
                        error: function (xhr) {
                            console.error("Lỗi khi reset password", xhr);
                            radaAlert.showSwalError("Có lỗi xảy ra khi gửi SMS");
                        }
                    });

                } catch (error) {
                    radaAlert.showSwalError("Có lỗi xảy ra. Vui lòng thử lại.");
                }
            }
        });
    };
})((window.rada360Admin = window.rada360Admin || {}), window);
