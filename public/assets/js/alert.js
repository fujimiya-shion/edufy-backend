// radaAlert.showSwalWaring('Bạn chắc chắn xoá Product & Service này?').then(function(confirm) {
//     if(confirm.isConfirmed) {
//         //TODO handle logic after click confirm.
//     }
// });

;(function (exports, global) {
    exports.showSwalSuccess = function (mes) {
        return Swal.fire({
            icon: "success",
            title: mes,
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: " Close",
        })
    }

    exports.showSwalWarning = function (mes) {
        return Swal.fire({
            icon: "warning",
            title: mes,
            confirmButtonText: " Ok",
            confirmButtonColor: "#d33",
            showCancelButton: true,
            cancelButtonText: " Cancel",
        });
    }

    exports.showSwalWarningInfo = function (mes) {
        return Swal.fire({
            icon: "warning",
            title: mes,
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: " Close",
        });
    }

    exports.showSwalError = function (mes) {
        return Swal.fire({
            icon: "error",
            title: mes,
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: " Close",
        })
    }

    exports.showSwalInfo = function (mes) {
        return Swal.fire({
            icon: "info",
            title: mes,
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: " Close",
        })
    }
    exports.showSwalAlertInputText = function (title, label) {
        Swal.fire({
            title: title,
            input: 'text',
            inputLabel: label,
            showCancelButton: true,
        })
    }
    exports.showSwalAlertInputEmail = function (title, label, placeholder) {
        Swal.fire({
            title: title,
            input: 'email',
            inputLabel: label,
            showCancelButton: true,
            inputPlaceholder: placeholder
        })
    }
    exports.showSwalAlertInputPassword = async function (title, label, placeholder) {
        const { value: password } = await Swal.fire({
            title: title,
            input: 'password',
            inputLabel: label,
            inputPlaceholder: placeholder,
            inputAttributes: {
                autocapitalize: 'off',
                autocorrect: 'off'
            }
        })

        if (password) {
            Swal.fire(`Entered password: ${password}`)
        }
    }
})(window.radaAlert = window.radaAlert || {}, window);
