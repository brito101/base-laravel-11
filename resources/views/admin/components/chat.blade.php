<div class="col-10 col-sm-6 col-md-4 col-lg-3" style="z-index: 9999; position: fixed; bottom: 30px; right: 15px;"
    data-url="{{ route('admin.chat.index') }}" id="chat-component">
    <div class="card card-warning card-outline direct-chat direct-chat-warning shadow collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Chat <span class="badge badge-warning" id="activeContact"></span></h3>
            <div class="card-tools">
                <span title="Novas mensagens" class="badge bg-warning" id="unread-messages">0</span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse" id="button-message-open">
                    <i class="fas fa-plus" id="message-icon"></i>
                </button>
                <button type="button" class="btn btn-tool" title="Contatos" data-widget="chat-pane-toggle">
                    <i class="fas fa-comments"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="card-body">

            {{-- Messages --}}
            <div class="direct-chat-messages" id="messages-box" data-me="{{ auth()->user()->id }}"></div>

            {{-- Contacts --}}
            <div class="direct-chat-contacts">
                <ul class="contacts-list" id="contacts-list"></ul>
            </div>
        </div>

        <div class="card-footer">
            <form action="{{ route('admin.chat.store') }}" method="POST" id="chat">
                <input type="hidden" name="receiver_id" value="" id="receiver">
                <div class="input-group">
                    <input type="text" name="message" placeholder="Escreva aqui ..." class="form-control"
                        id="chat-text">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-warning" id="chat-sent">Enviar</button>
                    </span>
                </div>
            </form>
        </div>

    </div>

</div>

@section('chat_js')
    <script src="{{ asset('js/chat.js') }}"></script>
@endsection
