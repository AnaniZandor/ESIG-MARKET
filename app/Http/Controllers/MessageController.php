<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageNotification;

class MessageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INBOX — Liste des conversations
    |--------------------------------------------------------------------------
    */
    public function inbox()
    {
        $userId = auth()->id();

        // Récupérer tous les messages avec les relations
        $allMessages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver', 'article'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Grouper par conversation unique (article + autre utilisateur)
        $conversations = $allMessages->groupBy(function ($msg) use ($userId) {
            $otherId = $msg->sender_id == $userId
                ? $msg->receiver_id
                : $msg->sender_id;
            return $msg->article_id . '-' . $otherId;
        });

        return view('messages.inbox', compact('conversations'));
    }

    /*
    |--------------------------------------------------------------------------
    | CONVERSATION — Fil de messages entre deux users pour un article
    |--------------------------------------------------------------------------
    */
    public function conversation($userId, $articleId)
    {
        $otherUser = User::findOrFail($userId);
        $article   = Article::findOrFail($articleId);

        $messages = Message::where(function ($q) use ($userId) {
                $q->where('sender_id', auth()->id())
                  ->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', auth()->id());
            })
            ->where('article_id', $articleId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer les messages reçus comme lus
        Message::where('sender_id', $userId)
               ->where('receiver_id', auth()->id())
               ->where('article_id', $articleId)
               ->whereNull('read_at')
               ->update(['read_at' => now()]);

        return view('messages.conversation', compact('messages', 'otherUser', 'article'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE — Envoyer un message
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'article_id'  => 'required|exists:articles,id',
            'content'     => 'required|string|max:1000',
        ]);

        // Empêcher de s'envoyer à soi-même
        if ($request->receiver_id == Auth::id()) {
            return back()->with('error', 'Action non autorisée');
        }

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'article_id'  => $request->article_id,
            'body'        => $request->content,
        ]);

        // Notifier le destinataire
        $receiver = User::find($request->receiver_id);
        if ($receiver) {
            $receiver->notify(new NewMessageNotification($message));
        }

        // Rediriger vers la conversation
        return redirect()->route('messages.conversation', [
            'userId'    => $request->receiver_id,
            'articleId' => $request->article_id,
        ])->with('success', '✅ Message envoyé !');
    }

    /*
    |--------------------------------------------------------------------------
    | MARK AS READ
    |--------------------------------------------------------------------------
    */
    public function markAsRead(Message $message)
    {
        if ($message->receiver_id === auth()->id()) {
            $message->update(['read_at' => now()]);
        }
        return back();
    }
}