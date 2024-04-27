$(document).ready(function () {
    // put route in form
    $(document).on("click", ".del-btn", function () {
        $("#deleteModal").modal("show");
        let url = $(this).data("url");
        $("#deleteRoute").attr("action", url);
    });
    // close modal onclick close button
    $("#closeModal").click(function () {
        $("#deleteModal").modal("hide");
    });
    // close modal after delete company
    $("#delBtn").click(function () {
        $("#deleteModal").modal("hide");
    });
    // parsley js validation
    $("#addEmployeeBtn").click(function () {
        $("#addEmployeeForm").parsley();
        $("#editEmployeeForm").parsley();
    });
});
