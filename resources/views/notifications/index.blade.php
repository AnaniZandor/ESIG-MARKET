@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

<div style="padding: 32px 0; max-width: 800px; margin: 0 auto;">

    {{-- EN-TÊTE --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:28px;">
        <h1 style="font-family:'Playfair Display',serif; font-size:28px;">
            🔔 Mes Notifications
        </h1>

        {{-- Marquer tout comme lu --}}
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-outline btn-sm">
                <i class="fas fa-check-double"></i>
                Tout marquer comme lu
            </button>
        </form>
        @endif
    </div>

    {{-- LISTE --}}
    @forelse(auth()->user()->notifications()->latest()->get() as $notif)

        <div style="
            background: {{ $notif->read_at ? 'white' : 'var(--primary-light)' }};
            border: 1px solid {{ $notif->read_at ? 'var(--border)' : 'var(--primary)' }};
            border-radius: var(--radius-lg);
            padding: 16px 20px;
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: var(--transition);
        ">
            {{-- ICÔNE selon le type --}}
            <div style="
                width: 42px; height: 42px; border-radius: 50%;
                background: var(--primary-light); color: var(--primary);
                display: flex; align-items: center; justify-content: center;
                font-size: 18px; flex-shrink: 0;
            ">
                @switch($notif->type)
                    @case('App\Notifications\NewMessageNotification')
                        <i class="fas fa-comment"></i>
                        @break
                    @case('App\Notifications\FavoriVenduNotification')
                        <i class="fas fa-heart"></i>
                        @break
                    @case('App\Notifications\ArticleSuspenduNotification')
                        <i class="fas fa-ban"></i>
                        @break
                    @default
                        <i class="fas fa-bell"></i>
                @endswitch
            </div>

            {{-- CONTENU --}}
            <div style="flex: 1;">
                <p style="font-weight: 600; font-size: 14px; color: var(--text-dark); margin-bottom: 4px;">
                    {{-- Afficher le message selon les données disponibles --}}
                    {{ $notif->data['message'] ?? 'Nouvelle notification' }}
                </p>

                @if(isset($notif->data['article_title']))
                    <p style="font-size: 13px; color: var(--text-mid);">
                        Article : <strong>{{ $notif->data['article_title'] }}</strong>
                    </p>
                @endif

                @if(isset($notif->data['sender_name']))
                    <p style="font-size: 13px; color: var(--text-mid);">
                        De : <strong>{{ $notif->data['sender_name'] }}</strong>
                    </p>
                @endif

                <small style="color: var(--text-light); font-size: 12px; margin-top: 6px; display:block;">
                    {{ $notif->created_at->diffForHumans() }}
                </small>
            </div>

            {{-- ACTIONS --}}
            <div style="display:flex; gap:8px; align-items:center; flex-shrink:0;">

           {{-- Lien selon le type de notification --}}
@if($notif->type === 'App\Notifications\NewMessageNotification')
    {{-- Ouvre la conversation directement --}}
    @if(isset($notif->data['article_id']) && isset($notif->data['message_id']))
        @php
            $msg = \App\Models\Message::find($notif->data['message_id']);
        @endphp
        @if($msg)
        <a href="{{ route('messages.conversation', [
                'userId'    => $msg->sender_id,
                'articleId' => $msg->article_id
            ]) }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-comment"></i> Répondre
        </a>
        @endif
    @endif
@elseif(isset($notif->data['article_id']))
    <a href="{{ route('articles.show', $notif->data['article_id']) }}"
       class="btn btn-outline btn-sm">
        <i class="fas fa-eye"></i> Voir
    </a>
@endif

                {{-- Marquer comme lu --}}
                @if(!$notif->read_at)
                    <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm"
                                style="background:var(--primary-light);color:var(--primary);border:none;"
                                title="Marquer comme lu">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                @else
                    <span style="font-size:11px; color:var(--text-light);">
                        <i class="fas fa-check-double"></i> Lu
                    </span>
                @endif

            </div>
        </div>

    @empty
        <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <h3>Aucune notification</h3>
            <p>Tu seras notifié ici pour les nouveaux messages, favoris vendus, etc.</p>
        </div>
    @endforelse

</div>

@endsection