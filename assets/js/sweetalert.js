// เรียกใช้งาน SweetAlert2
function alertSuccess(message, redirectUrl = null) {
  Swal.fire({
    icon: "success",
    title: message,
    showConfirmButton: false,
    timer: 1500,
  }).then(() => {
    if (redirectUrl) {
      window.location.href = redirectUrl;
    }
  });
}

function alertError(message) {
  Swal.fire({
    icon: "error",
    title: "เกิดข้อผิดพลาด",
    text: message,
    confirmButtonColor: "#dc2626",
  });
}

function outOfStock() {
  Swal.fire({
    icon: "warning",
    title: "สินค้าหมดชั่วคราว",
    text: "ขออภัย สินค้านี้หมดสต๊อกแล้ว",
    confirmButtonColor: "#FBBF24",
    confirmButtonText: "ปิด",
  });
}
