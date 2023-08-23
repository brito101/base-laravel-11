$(".file-delete").on("click", function (e) {
    e.preventDefault();
    if (confirm("Confirma a exclusão deste arquivo?") == true) {
        let fileRemove = e.target.dataset.id;
        $.ajax({
            type: "delete",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: e.target.dataset.action,
            success: function (res) {
                if (res.message == "success") {
                    $("div").find(`[data-file='${fileRemove}']`).remove();
                } else {
                    alert("Falha ao remover arquivo");
                }
            },
        });
    }
});
