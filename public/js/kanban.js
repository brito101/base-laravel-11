const item=$("#kanban").data("operation");let timer,area=null;function updateKanban(){$.ajax({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},type:"POST",url:$("#kanban").data("action"),data:{area:area},success:function(a){area=null}})}function dragStart(a){a.currentTarget.classList.add("dragging")}function dragEnd(a){a.currentTarget.classList.remove("dragging")}function dragOver(a){let e=document.querySelector(".draggable-item.dragging");a.currentTarget.appendChild(e),void 0!==a.target.dataset.area&&(area=a.target.dataset.area,timer&&clearTimeout(timer),timer=setTimeout((()=>{updateKanban(),timer=null}),500))}document.querySelectorAll(".draggable-item").forEach((a=>{a.addEventListener("dragstart",dragStart),a.addEventListener("dragend",dragEnd)})),document.querySelectorAll(".draggable-area").forEach((a=>{a.addEventListener("dragover",dragOver),a.addEventListener("drop",dragOver)}));
