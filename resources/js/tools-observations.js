$(document).ready(function () {
    let item = $("[data-observation-qtd]").data("observationQtd") || 0;
    $("[data-observation]").on("click", function (e) {
        e.preventDefault();
        let action = $(e.target).data("observation");
        if (action === "open") {
            item++;
            let html = `
        <div class="col-12 form-group px-0 d-flex flex-wrap justify-content-start" id="container_observation_${item}">
            <div class="col-12 px-0">
                <textarea class="form-control" id="observation_${item}" placeholder="Observação útil sobre a ferramenta ou processo de execução" name="observation_${item}" rows="2"></textarea>
            </div>
        </div>`;
            $("#observation").append(html);
        }
        if (action === "close" && item >= 0) {
            $(`label[for=observation_${item}]`).remove();
            $(`#container_observation_${item}`).remove();
            $(`#observation_${item}`).remove();
            $(`#observation_${item}_date`).remove();
            item--;
        }
    });
});
