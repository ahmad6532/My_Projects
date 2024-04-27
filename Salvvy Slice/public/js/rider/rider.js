$(document).ready(function () {
    // put route in form
    $(document).on("click", ".del-btn", function () {
        $("#deleteRiderModal").modal("show");
        let url = $(this).data('url');
        $("#deleteRiderForm").attr("action", url);
    });
    // close modal onclick close button
    $("#closeModal").click(function () {
        $("#deleteRiderModal").modal("hide");
    });
    // close modal after delete company
    $("#delBtn").click(function () {
        $("#deleteRiderModal").modal("hide");
    });
});
