<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Models\ChatConversation;
use Inertia\Inertia;

class ConversationController extends Controller
{
    public function index()
    {

        $conversations = ChatConversation::with(['participant' => function ($q) {
            $q->where('user_id', '!=', $this->uid());
        }])
            ->whereRelation('participant.user', 'user_id', '=', $this->uid())->get();
        return Inertia::render('Chat/Conversations', [
            'conversations' => ConversationResource::collection($conversations)
        ]);
    }
}
