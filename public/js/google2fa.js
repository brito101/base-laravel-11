$("#google2fa").on("switchChange.bootstrapSwitch", function (e, data) {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: $(e.target).data("action"),
        type: "POST",
        data: {
            data,
            user: $(e.target).data("user"),
        },
        success: function ({ message, qrcode, seed }) {
            alert(message);
            if (seed) {
                $("#seed-container div").remove();
                $("#seed-container").append(
                    `<div>
                        <p class="w-100 d-inline-block px-0">Semente: <span style="letter-spacing: .2rem; margin-left: 20px; font-weight: 700;">${seed}</span></p>
                        <img src="data:image/png;base64,${qrcode}" alt="QRCode" />
                    </div>`
                );
            } else {
                $("#seed-container div").remove();
            }
        },
        error: function (message) {
            alert(message);
        },
    });
});
