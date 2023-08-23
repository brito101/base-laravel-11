const item = $("#kanban").data("operation");
let area = null;
let timer;

function updateKanban() {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "POST",
        url: $("#kanban").data("action"),
        data: {
            area,
        },
        success: function (res) {
            area = null;
        },
    });
}

// items functions
function dragStart(e) {
    e.currentTarget.classList.add("dragging");
}

function dragEnd(e) {
    e.currentTarget.classList.remove("dragging");
}

// areas functions
function dragOver(e) {
    let dragItem = document.querySelector(".draggable-item.dragging");
    e.currentTarget.appendChild(dragItem);
    if (e.target.dataset.area !== undefined) {
        area = e.target.dataset.area;
        if (timer) clearTimeout(timer);
        timer = setTimeout(() => {
            updateKanban();
            timer = null;
        }, 500);
    }
}

// Events
document.querySelectorAll(".draggable-item").forEach((item) => {
    item.addEventListener("dragstart", dragStart);
    item.addEventListener("dragend", dragEnd);
});

document.querySelectorAll(".draggable-area").forEach((area) => {
    area.addEventListener("dragover", dragOver);
    area.addEventListener("drop", dragOver);
});
