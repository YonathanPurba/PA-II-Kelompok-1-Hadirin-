document.addEventListener("DOMContentLoaded", function () {
    const flash = document.getElementById("flash-data");

    if (!flash) return;

    const successMessage = flash.dataset.success;
    const errorMessage = flash.dataset.error;

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: successMessage,
            timer: 3000,
            showConfirmButton: false
        });
    }

    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: errorMessage,
            timer: 5000,
            showConfirmButton: false
        });
    }
});
