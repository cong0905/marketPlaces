<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class ChatController extends Controller
{
    /**
     * Start a new chat or open existing one with a seller
     */
    public function showByProduct(Product $product)
    {
        // Cannot chat with yourself
        if ($product->user_id === auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không thể chat với chính mình.');
        }

        // Find existing conversation
        $conversation = Conversation::firstOrCreate(
            [
                'buyer_id' => auth()->id(),
                'seller_id' => $product->user_id,
                'product_id' => $product->id,
            ],
            ['last_message_at' => now()]
        );

        return redirect()->route('chat.show', $conversation->id);
    }

    /**
     * Show all conversations
     */
    public function index()
    {
        $userId = auth()->id();
        $conversations = Conversation::where('buyer_id', $userId)
            ->orWhere('seller_id', $userId)
            ->with(['buyer', 'seller', 'product', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    /**
     * Show specific conversation window
     */
    public function show(Conversation $conversation)
    {
        // Authorization
        if ($conversation->buyer_id !== auth()->id() && $conversation->seller_id !== auth()->id()) {
            abort(403);
        }

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $conversation->load(['messages.sender', 'product', 'buyer', 'seller']);
        
        $otherUser = $conversation->getOtherUser(auth()->user());

        return view('chat.show', compact('conversation', 'otherUser'));
    }

    /**
     * Send a message
     */
    public function store(Request $request, Conversation $conversation)
    {
        // Authorization
        if ($conversation->buyer_id !== auth()->id() && $conversation->seller_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'body' => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Broadcast event for Reverb
        broadcast(new MessageSent($message))->toOthers();

        // Send Email if the recipient is offline
        $otherUser = $conversation->getOtherUser(auth()->user());
        if (!$otherUser->is_online || ($otherUser->last_seen_at && $otherUser->last_seen_at->diffInMinutes(now()) > 5)) {
            \Illuminate\Support\Facades\Mail::to($otherUser->email)->send(new \App\Mail\NewMessageMail($message));
        }

        return response()->json($message->load('sender'));
    }
}
