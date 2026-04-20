@extends('layouts.app')

@section('title', 'Mes messages')

@section('content')

<div style="padding: 32px 0; max-width: 800px; margin: 0 auto;">

    {{-- EN-TÊTE --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:28px;">
        <h1 style="font-family:'Playfair Display',serif; font-size:28px;">
            💬 Mes conversations
        </h1>
        <span style="font-size:14px; color:var(--text-light);">
            {{ $conversations->count() }} conversation(s)
        </span>
    </div>

    {{-- LISTE DES CONVERSATIONS --}}
    @forelse($conversations as $key => $msgs)
        @php
            $lastMessage = $msgs->first();
            $authId      = auth()->id();

            // L'autre utilisateur dans la conversation
            $otherUser = $lastMessage->sender_id == $authId
                ? $lastMessage->receiver
                : $lastMessage->sender;

            $article = $lastMessage->article;

            // Compter les messages non lus
            $unreadCount = $msgs->filter(function($m) use ($authId) {
                return $m->receiver_id == $authId && is_null($m->read_at);
            })->count();
        @endphp

        <a href="{{ route('messages.conversation', [
                'userId'    => $otherUser->id,
                'articleId' => $lastMessage->article_id
            ]) }}"
           style="
               display:flex;
               align-items:center;
               gap:14px;
               padding:16px 20px;
               background: {{ $unreadCount > 0 ? 'var(--primary-light)' : 'white' }};
               border: 1px solid {{ $unreadCount > 0 ? 'var(--primary)' : 'var(--border)' }};
               border-radius: var(--radius-lg);
               margin-bottom: 10px;
               text-decoration: none;
               color: inherit;
               transition: var(--transition);
           "
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow)'"
           onmouseout="this.style.transform=''; this.style.boxShadow=''">

            {{-- AVATAR de l'autre user --}}
            <div style="
                width:50px; height:50px; border-radius:50%;
                background:var(--primary); color:white;
                display:flex; align-items:center; justify-content:center;
                font-family:'Playfair Display',serif;
                font-size:20px; font-weight:700; flex-shrink:0;
                overflow:hidden;
            ">
                @if($otherUser->avatar)
                    <img src="{{ asset('storage/'.$otherUser->avatar) }}"
                         style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                @endif
            </div>

            {{-- INFOS --}}
            <div style="flex:1; overflow:hidden;">
                {{-- Nom + article --}}
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                    <span style="font-weight:600; font-size:15px; color:var(--text-dark);">
                        {{ $otherUser->name }}
                    </span>
                    @if($otherUser->filiere)
                        <span style="font-size:11px; color:var(--text-light);">
                            · {{ $otherUser->filiere }}
                        </span>
                    @endif
                </div>

                {{-- Article concerné --}}
                @if($article)
                <div style="font-size:12px; color:var(--primary); margin-bottom:4px;">
                    <i class="fas fa-box"></i>
                    {{ Str::limit($article->title, 40) }}
                    —
                    <strong>{{ number_format($article->price, 0, ',', ' ') }} FCFA</strong>
                </div>
                @endif

                {{-- Dernier message --}}
                <div style="font-size:13px; color:var(--text-light); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    @if($lastMessage->sender_id == $authId)
                        <span style="color:var(--text-light);">Toi : </span>
                    @endif
                    {{ Str::limit($lastMessage->body, 60) }}
                </div>
            </div>

            {{-- MÉTA --}}
            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px; flex-shrink:0;">
                <span style="font-size:12px; color:var(--text-light);">
                    {{ $lastMessage->created_at->diffForHumans() }}
                </span>
                @if($unreadCount > 0)
                    <span style="
                        background:var(--primary); color:white;
                        font-size:11px; font-weight:700;
                        padding:2px 8px; border-radius:20px;
                    ">
                        {{ $unreadCount }} nouveau{{ $unreadCount > 1 ? 'x' : '' }}
                    </span>
                @endif
            </div>

        </a>

    @empty
        <div class="empty-state">
            <i class="fas fa-comment-slash"></i>
            <h3>Aucune conversation</h3>
            <p>Tes conversations apparaîtront ici quand tu contacteras un vendeur.</p>
            <a href="{{ route('articles.index') }}" class="btn btn-primary" style="margin-top:16px;">
                <i class="fas fa-store"></i> Parcourir les annonces
            </a>
        </div>
    @endforelse

</div>

@endsection