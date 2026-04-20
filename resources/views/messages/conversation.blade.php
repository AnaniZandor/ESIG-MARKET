@extends('layouts.app')

@section('title', 'Conversation avec '.$otherUser->name)

@section('content')

<div style="padding: 24px 0; max-width: 800px; margin: 0 auto;">

    {{-- RETOUR --}}
    <a href="{{ route('messages.index') }}"
       style="display:inline-flex; align-items:center; gap:8px; font-size:14px; color:var(--text-mid); margin-bottom:20px; text-decoration:none;">
        <i class="fas fa-arrow-left"></i> Retour aux conversations
    </a>

    {{-- EN-TÊTE CONVERSATION --}}
    <div style="
        background:white; border:1px solid var(--border);
        border-radius:var(--radius-lg); padding:16px 20px;
        display:flex; align-items:center; gap:14px;
        margin-bottom:16px; box-shadow:var(--shadow-sm);
    ">
        {{-- Avatar --}}
        <div style="
            width:48px; height:48px; border-radius:50%;
            background:var(--primary); color:white;
            display:flex; align-items:center; justify-content:center;
            font-size:18px; font-weight:700; flex-shrink:0; overflow:hidden;
        ">
            @if($otherUser->avatar)
                <img src="{{ asset('storage/'.$otherUser->avatar) }}"
                     style="width:100%;height:100%;object-fit:cover;">
            @else
                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
            @endif
        </div>

        {{-- Infos --}}
        <div style="flex:1;">
            <div style="font-weight:600; font-size:16px; color:var(--text-dark);">
                {{ $otherUser->name }}
            </div>
            @if($otherUser->filiere)
                <div style="font-size:13px; color:var(--text-light);">
                    <i class="fas fa-graduation-cap"></i> {{ $otherUser->filiere }}
                </div>
            @endif
        </div>

        {{-- Article concerné --}}
        <a href="{{ route('articles.show', $article->id) }}"
           style="display:flex; align-items:center; gap:10px; padding:10px 14px; background:var(--primary-light); border-radius:var(--radius); text-decoration:none;">
            @if($article->images && $article->images->first())
                <img src="{{ asset('storage/'.$article->images->first()->path) }}"
                     style="width:40px; height:40px; border-radius:var(--radius-sm); object-fit:cover;">
            @endif
            <div>
                <div style="font-size:13px; font-weight:600; color:var(--text-dark);">
                    {{ Str::limit($article->title, 30) }}
                </div>
                <div style="font-size:13px; font-weight:700; color:var(--primary);">
                    {{ number_format($article->price, 0, ',', ' ') }} FCFA
                </div>
            </div>
        </a>
    </div>

    {{-- FIL DE MESSAGES --}}
    <div id="messages-container" style="
        background:var(--bg); border:1px solid var(--border);
        border-radius:var(--radius-lg); padding:20px;
        min-height:400px; max-height:500px; overflow-y:auto;
        display:flex; flex-direction:column; gap:12px;
        margin-bottom:16px;
    ">
        @forelse($messages as $message)
            @php $isMe = $message->sender_id == auth()->id(); @endphp

            <div style="
                display:flex; flex-direction:column;
                align-items: {{ $isMe ? 'flex-end' : 'flex-start' }};
            ">
                {{-- Nom expéditeur --}}
                <span style="font-size:12px; color:var(--text-light); margin-bottom:4px;">
                    {{ $isMe ? 'Vous' : $otherUser->name }}
                </span>

                {{-- Bulle de message --}}
                <div style="
                    max-width:70%; padding:12px 16px;
                    border-radius:{{ $isMe ? '20px 20px 4px 20px' : '20px 20px 20px 4px' }};
                    background: {{ $isMe ? 'var(--primary)' : 'white' }};
                    color: {{ $isMe ? 'white' : 'var(--text-dark)' }};
                    border: 1px solid {{ $isMe ? 'transparent' : 'var(--border)' }};
                    font-size:14px; line-height:1.5;
                    box-shadow: var(--shadow-sm);
                ">
                    {{ $message->body }}
                </div>

                {{-- Heure + statut lu --}}
                <span style="font-size:11px; color:var(--text-light); margin-top:4px;">
                    {{ $message->created_at->format('H:i') }}
                    @if($isMe)
                        @if($message->read_at)
                            <i class="fas fa-check-double" style="color:var(--primary);"></i>
                        @else
                            <i class="fas fa-check" style="color:var(--text-light);"></i>
                        @endif
                    @endif
                </span>
            </div>

        @empty
            <div style="text-align:center; color:var(--text-light); padding:40px;">
                <i class="fas fa-comment" style="font-size:32px; margin-bottom:10px; display:block;"></i>
                Début de la conversation
            </div>
        @endforelse
    </div>

    {{-- FORMULAIRE DE RÉPONSE --}}
    @if($article->status === 'disponible' || $messages->count() > 0)
    <div style="
        background:white; border:1px solid var(--border);
        border-radius:var(--radius-lg); padding:16px 20px;
        box-shadow:var(--shadow-sm);
    ">
        <form action="{{ route('messages.store') }}" method="POST">
            @csrf

            {{-- Champs cachés --}}
            <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
            <input type="hidden" name="article_id"  value="{{ $article->id }}">

            <div style="display:flex; gap:12px; align-items:flex-end;">
                <textarea
                    name="content"
                    rows="2"
                    placeholder="Écrire un message..."
                    required
                    style="
                        flex:1; padding:12px 16px;
                        border:1.5px solid var(--border);
                        border-radius:var(--radius-lg);
                        resize:none; outline:none;
                        font-size:14px; font-family:inherit;
                        transition:var(--transition);
                    "
                    onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px var(--primary-light)'"
                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow=''"></textarea>

                <button type="submit" class="btn btn-primary"
                        style="padding:12px 20px; border-radius:var(--radius-lg); flex-shrink:0;">
                    <i class="fas fa-paper-plane"></i>
                    Envoyer
                </button>
            </div>

        </form>
    </div>
    @endif

</div>

{{-- Scroll automatique vers le bas --}}
@section('scripts')
<script>
    const container = document.getElementById('messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endsection

@endsection