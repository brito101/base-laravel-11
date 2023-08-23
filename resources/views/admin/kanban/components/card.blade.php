<div draggable="true" class="draggable-item">
    <div class="card card-secondary card-outline">
        <div class="card-header" data-toggle="collapse" href="#collapse{{ $kanban->id }}" role="button"
            aria-expanded="false" aria-controls="collapse{{ $kanban->id }}">
            <h5 class="card-title">Detalhes</h5>
        </div>
        <div class="collapse" id="collapse{{ $kanban->id }}">
            <div class="card-body">
                <p>Tipo: {{ $kanban->type }}</p>
                <p>Classificação: {{ $kanban->classification }}</p>
                <p>Início: {{ $kanban->start ? date('d/m/Y H:i', strtotime($kanban->start)) : 'Indeterminado' }}</p>
                <p>Término: {{ $kanban->end ? date('d/m/Y H:i', strtotime($kanban->end)) : 'Indeterminado' }}</p>
            </div>
        </div>
    </div>
</div>
