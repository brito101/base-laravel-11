$(".image-delete").on("click",(function(e){if(e.preventDefault(),1==confirm("Confirma a exclusão desta imagem?")){let a=e.target.dataset.id;$.ajax({type:"delete",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},url:e.target.dataset.action,success:function(e){"success"==e.message?$("div").find(`[data-image='${a}']`).remove():alert("Falha ao remover a imagem")}})}})),$(".file-delete").on("click",(function(e){if(e.preventDefault(),1==confirm("Confirma a exclusão deste arquivo?")){let a=e.target.dataset.id;$.ajax({type:"delete",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},url:e.target.dataset.action,success:function(e){"success"==e.message?$("div").find(`[data-file='${a}']`).remove():alert("Falha ao remover arquivo")}})}}));
