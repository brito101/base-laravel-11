$("#actionPost").on("submit", function (e) {
    e.preventDefault();

    const url = $(this).attr("action");

    $.ajax({
        url: url,
        method: "POST",
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            $("#actionPost").trigger("reset");
            alert(response.message);
        },
        error: function (response) {},
    });
});

let content = null;

function ajaxCall() {
    $.ajax({
        type: "GET",
        url: $('meta[name="ajaxCall"]').attr("content"),
        success: function (res) {
            let data = res.actions;
            $("#actions").empty();
            data.forEach((el) => {
                if (el.full_image) {
                    content = `<div class="post">
                    <div class="user-block">
                        <img src="${el.user_photo}" class="img-circle img-bordered-sm" style="width: 33.6px; height: 33.6px; object-fit: cover;" alt="${el.user_name}">
                        <span class="float-right btn btn-danger actionTrash"
                                    data-action="${el.delete_action}">
                                    <i class="fa fa-trash"></i></span>
                        <span class="username">
                            <span>${el.user_name}</span>
                        </span>
                        <span class="description">Enviado em ${el.date}</span>
                        </div>
                        <p>${el.text}</p>
                                <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start">
                                    <div class="col-12 p-2 card">
                                        <div class="card-body d-flex justify-content-center align-items-center">
                                            <img class="img-fluid"
                                                src="${el.full_image}"
                                                alt="">
                                        </div>
                                    </div>
                </div></div>`;
                } else {
                    content = `<div class="post">
                    <div class="user-block">
                        <img src="${el.user_photo}" class="img-circle img-bordered-sm" style="width: 33.6px; height: 33.6px; object-fit: cover;" alt="${el.user_name}">
                        <span class="float-right btn btn-danger actionTrash"
                                    data-action="${el.delete_action}">
                                    <i class="fa fa-trash"></i></span>
                        <span class="username">
                            <span>${el.user_name}</span>
                        </span>
                        <span class="description">Enviado em ${el.date}</span>
                        </div>
                        <p>${el.text}</p>
                </div>`;
                }
                $("#actions").append(content);
            });
        },
        error: function () {},
    });
}

setInterval(ajaxCall, 5000);

$("#actions").on("click", ".actionTrash", function (e) {
    e.preventDefault();
    let action = $(e.currentTarget).data("action");

    $.ajax({
        type: "delete",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: action,
        success: function ({ message }) {
            if (message) {
                alert(message);
            }
        },
        error: function () {
            alert("Falha ao excluir a ação.");
        },
        complete: function () {
            $(e.currentTarget).parent().parent().remove();
        },
    });
});
