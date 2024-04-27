$(document).ready(function () {
  
    // put route in form
    $(document).on("click", ".del-btn", function () {
        $("#deleteOrderModal").modal("show");
        let url = $(this).data("url");
        $("#deleteOrderForm").attr("action", url);
    });
    // close modal onclick close button
    $("#closeModal").click(function () {
        $("#deleteOrderModal").modal("hide");
    });
    // close modal after delete company
    $("#delBtn").click(function () {
        $("#deleteOrderModal").modal("hide");
    });
});
