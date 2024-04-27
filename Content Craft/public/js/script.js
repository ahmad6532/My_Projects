$(document).ready(function () {
    // Stripe Payment
    $(function () {
        var $form = $(".require-validation");

        $("form.require-validation").bind("submit", function (e) {
            var $form = $(".require-validation"),
                inputSelector = [
                    "input[type=email]",
                    "input[type=password]",
                    "input[type=text]",
                    "input[type=file]",
                    "textarea",
                ].join(", "),
                $inputs = $form.find(".required").find(inputSelector),
                $errorMessage = $form.find("div.error"),
                valid = true;
            $errorMessage.addClass("hide");

            $(".has-error").removeClass("has-error");
            $inputs.each(function (i, el) {
                var $input = $(el);
                if ($input.val() === "") {
                    $input.parent().addClass("has-error");
                    $errorMessage.removeClass("hide");
                    e.preventDefault();
                }
            });

            if (!$form.data("cc-on-file")) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data("stripe-publishable-key"));
                Stripe.createToken(
                    {
                        number: $(".card-number").val(),
                        cvc: $(".card-cvc").val(),
                        exp_month: $(".card-expiry-month").val(),
                        exp_year: $(".card-expiry-year").val(),
                    },
                    stripeResponseHandler
                );
            }
        });

        /*------------------------------------------
        --------------------------------------------
        Stripe Response Handler
        --------------------------------------------
        --------------------------------------------*/
        function stripeResponseHandler(status, response) {
            if (response.error) {
                $(".error")
                    .removeClass("hide")
                    .find(".alert")
                    .text(response.error.message);
            } else {
                /* token contains id, last4, and card type */
                var token = response["id"];

                $form.find("input[type=text]").empty();
                $form.append(
                    "<input type='hidden' name='stripeToken' value='" +
                        token +
                        "'/>"
                );
                $form.get(0).submit();
            }
        }
    });

   

if (['/admin/dashboard', '/dashboard'].includes(window.location.pathname)) {
        // show chart to display all managers and amount

        let canvas = document.getElementById("bar-chart");
        let data = JSON.parse(canvas.dataset.data);
        let labels = data.map((item) => item.manager);
        let values = data.map((item) => item.amount);
        let ctx = canvas.getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Earning",
                        data: values,
                        backgroundColor: "rgba(1, 56, 1, 0.7)",
                        borderColor: "rgba(54, 11, 11, 1)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: "Managers",
                        },
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: "Sale (amount in rupees)",
                        },
                    },
                },
            },
        });
    } else if (window.location.pathname === "/managers/dashboard") {
        // show chart to display monthly earning of manager

        let managerCanvas = document.getElementById("managerBarChart");
        let managerData = JSON.parse(managerCanvas.dataset.data);
        let label = managerData.map((items) => items.month);
        let value = managerData.map((items) => items.amount);
        let ctxx = managerCanvas.getContext("2d");
        new Chart(ctxx, {
            type: "bar",
            data: {
                labels: label,
                datasets: [
                    {
                        label: "Earning",
                        data: value,
                        backgroundColor: "rgba(1, 56, 1, 0.7)",
                        borderColor: "rgba(54, 11, 11, 1)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: "Months",
                        },
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: "Sale (amount in rupees)",
                        },
                    },
                },
            },
        });
    }
   

    // show chart according to selected month
    $(document).on("change", "#earningDate", function () {
        let date = $(this).val();
        let url = $(this).data("url");
        let csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: url,
            type: "POST",
            data: {
                date: date,
            },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                let canvas = document.getElementById("bar-chart");
                let data = JSON.parse(response);
                if (canvas) {
                    var chartInstance = Chart.getChart(canvas);
                    if (chartInstance) {
                        chartInstance.destroy();
                    }
                }
                let labels = data.map((item) => item.manager);
                let values = data.map((item) => item.amount);
                let ctx = canvas.getContext("2d");
                new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: "Earning",
                                data: values,
                                backgroundColor: "rgba(1, 56, 1, 0.7)",
                                borderColor: "rgba(54, 11, 11, 1)",
                                borderWidth: 1,
                            },
                        ],
                    },
                    options: {
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: "Managers",
                                },
                                grid: {
                                    display: false,
                                },
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: "Sale (amount in rupees)",
                                },
                            },
                        },
                    },
                });
            },
            error: function (xhr) {
                toastr.error(xhr.responseText);
            },
        });
    });

    // SignUp form validation
    $(document).on("click", "#completeProfileBtn", function (e) {
        $("#signUpForm").parsley().validate();
    });

    // Login form validation
    $(document).on("click", "#loginBtn", function () {
        $("#loginForm").parsley().validate();
    });

    // image upload
    $("#inputImage").change(function () {
        readURL(this);
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#userImage").attr("src", e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Action delete button
    $(document).on("click", ".del-btn", function () {
        let url = $(this).data("url");
        $("#deleteModal").show();
        $("#confirmDeleteForm").attr("action", url);
    });

    // view manager
    $(document).on("click", ".show-btn", function () {
        let url = $(this).data("url");
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                $("#showUserModal").css("display", "flex");
                $("#showUserModal").show();
                $("#uViewId").text(response.response.data.id);
                $("#uViewfname").text(response.response.data.firstName);
                $("#uViewlname").text(response.response.data.lastName);
                $("#uViewemail").text(response.response.data.email);
                $("#uViewphone").text(response.response.data.phone);
                $("#uViewgender").text(response.response.data.gender);
                $("#uViewmanager").text(response.response.data.manager);
                $("#uViewcountry").text(response.response.data.country);
                $("#uViewpostalCode").text(response.response.data.postalCode);
                $("#uViewaddress").text(response.response.data.address);
                $("#uViewavatar").attr(
                    "src",
                    "storage/" + response.response.data.avatar
                );
                $("#uViewmanager").text(
                    response.response.manager.firstName +
                        " " +
                        response.response.manager.lastName
                );
            },
            error: function (xhr) {
                toastr.error(xhr.responseText);
            },
        });
    });

    // view user
    $(document).on("click", ".show-user-btn", function () {
        let url = $(this).data("url");
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                $("#showUserModal").css("display", "flex");
                $("#showUserModal").show();
                $("#uViewId").text(response.data.id);
                $("#uViewfname").text(response.data.firstName);
                $("#uViewlname").text(response.data.lastName);
                $("#uViewemail").text(response.data.email);
                $("#uViewphone").text(response.data.phone);
                $("#uViewgender").text(response.data.gender);
                $("#uViewmanager").text(response.data.manager);
                $("#uViewcountry").text(response.data.country);
                $("#uViewpostalCode").text(response.data.postalCode);
                $("#uViewaddress").text(response.data.address);
                $("#uViewavatar").attr(
                    "src",
                    "storage/" + response.data.avatar
                );
                $("#uViewmanager").text(
                    response.response.manager.firstName +
                        " " +
                        response.response.manager.lastName
                );
            },
            error: function (xhr) {
                toastr.error(xhr.responseText);
            },
        });
    });
    // close Modal
    $(document).on("click", "#closeModal", function () {
        $("#showUserModal").hide();
        $("#deleteModal").hide();
        $("#addArticleModal").hide();
        $("#viewArticleModal").hide();
        $("#editArticleModal").hide();
    });

    // validate update form
    $(document).on("click", "#updateProfileBtn", function () {
        $("#editeUser").parsley().validate();
    });

    // validate update form
    $(document).on("click", "#updateUserBtn", function () {
        $("#updateUser").parsley().validate();
    });

    // Add Article
    $(document).on("click", "#articleCreateBtn", function () {
        $("#addArticleModal").show();
    });

    $("#addArticleBtn").click(function () {
        let csrfToken = $('meta[name="csrf-token"]').attr("content");
        if ($("#addArticleForm").parsley().validate()) {
            let url = $(this).data("url");
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    title: $("#title").val(),
                    content: $("#content").val(),
                },
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function (response) {
                    toastr.success(response.message);
                    $("#addArticleModal").hide();
                    $("#articles-table ").DataTable().ajax.reload();
                    $("#createForm").parsley().reset();
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        toastr.error(xhr.responseText);
                    } else {
                        toastr.error(xhr.responseText);
                    }
                },
            });
        }
    });

    // Delete Article
    $(document).on("click", ".del-btn", function () {
        let url = $(this).data("url");
        $("#deleteModal").show();
        $("#confirmDeleteForm").attr("action", url);
    });

    // view article Modal
    $(document).on("click", ".view-btn", function () {
        let url = $(this).data("url");
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                $("#viewTitle").text(response.data.title);
                $("#viewContent").text(response.data.content);
                $("#viewArticleModal").show();
                $("#showUserModal").hide();
                $("#viewArticleModal").css("display", "flex");
            },
            error: function (xhr) {
                toastr.error(xhr.responseText);
            },
        });
    });

    // edit Article
    $(document).on("click", ".edit-btn", function () {
        let url = $(this).data("url");
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                $("#updateId").val(response.data.articleId);
                $("#updateTitle").val(response.data.title);
                $("#updateContent").val(response.data.content);
                $("#editArticleModal").show();
            },
            error: function (xhr) {
                toastr.error(xhr.responseText);
            },
        });
    });

    // update Article
    $(document).on("click", "#editArticleBtn", function () {
        if ($("#editArticleForm").parsley().validate()) {
            let csrfToken = $('meta[name="csrf-token"]').attr("content");
            let userId = $("#updateId").val();
            let url = $(this).data("url") + "/" + userId;
            $.ajax({
                url: url,
                type: "PUT",
                data: {
                    title: $("#updateTitle").val(),
                    content: $("#updateContent").val(),
                },
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function (response) {
                    $("#articles-table ").DataTable().ajax.reload();
                    $("#editArticleForm").parsley().reset();
                    $("#editArticleModal").hide();
                    toastr.success(response.message);
                },
                error: function (xhr) {
                    toastr.error(xhr.responseText);
                },
            });
        }
    });

    // logout dropdown
    $(document).on("click", ".logout-div", function () {
        $("#logout-dropdown").toggle();
        if ($("#logout-dropdown").is(":visible")) {
            $("#logout-dropdown").css("display", "flex");
        } else {
            $("#logout-dropdown").css("display", "none");
        }
    });

    // show user profile
    $(document).on("click", "#userProfileView", function () {
        let url = $(this).data("url");
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                $("#showUserModal").css("display", "flex");
                $("#showUserModal").show();
                $("#uViewId").text(response.data.id);
                $("#uViewfname").text(response.data.firstName);
                $("#uViewlname").text(response.data.lastName);
                $("#uViewemail").text(response.data.email);
                $("#uViewphone").text(response.data.phone);
                $("#uViewgender").text(response.data.gender);
                $("#uViewcountry").text(response.data.country);
                $("#uViewpostalCode").text(response.data.postalCode);
                $("#uViewaddress").text(response.data.address);
                $("#uViewavatar").attr(
                    "src",
                    "/storage/" + response.data.avatar,
                );
            },
            error: function (xhr) {
                toastr.error(xhr.responseText);
            },
        });
    });

    // show manager profile
    $(document).on("click", "#managerProfileView", function () {
        let url = $(this).data("url");
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                $("#showUserModal").css("display", "flex");
                $("#showUserModal").show();
                $("#uViewId").text(response.data.id);
                $("#uViewfname").text(response.data.firstName);
                $("#uViewlname").text(response.data.lastName);
                $("#uViewemail").text(response.data.email);
                $("#uViewphone").text(response.data.phone);
                $("#uViewgender").text(response.data.gender);
                $("#uViewmanager").text(response.data.manager);
                $("#uViewcountry").text(response.data.country);
                $("#uViewpostalCode").text(response.data.postalCode);
                $("#uViewaddress").text(response.data.address);
                $("#uViewavatar").attr(
                    "src",
                    "/storage/" + response.data.avatar
                );
            },
            error: function (xhr) {
                toastr.error(xhr.responseText);
            },
        });
    });

    $("#showAllArticlesBtn").click(function () {
        window.location.href = $(this).data("url");
    });

    // Like an Article
    $(document).on("click", ".like-article-btn", function () {
        let url = $(this).data("url");
        let csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: url,
            type: "POST",
            data: null,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                $("#articles-table ").DataTable().ajax.reload();
            },
            error: function (xhr) {
                toastr.error(xhr.responseText);
            },
        });
    });

    // Store Manager
    $(document).on("click", "#createManagerBtn", function () {
        $("#createManagerForm").parsley().validate();
    });

    // update Manager
    $(document).on("click", "#updateManagerBtn", function () {
        $("#editManager").parsley().validate();
    });

    // block unblock
    $(document).on("click", ".block-unblock-btn", function () {
        let url = $(this).data("url");
        let status = $(this).data("status");
        let csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            url: url,
            type: "PUT",
            data: { status: status },
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                $("#manager-table ").DataTable().ajax.reload();
                $("#user-table ").DataTable().ajax.reload();
                toastr.success(response.message);
            },
            error: function (xhr) {
                toastr.error(xhr.responseText);
            },
        });
    });

    // create user via manager
    $(document).on("click", "#createUserSubmit", function (e) {
        $("#userCreateForm").parsley().validate();
    });
});
