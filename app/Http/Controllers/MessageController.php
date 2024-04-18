<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Events\SendMessageEvent;
use App\Http\Resources\ChatUserRescource;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ChatUserResource;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use Inertia\Inertia;

class MessageController extends Controller
{
    public $messages;
    public function index(Request $request)
    {
        return Inertia::render('Chat/Messages', [
            'users' => $this->getUsers(),
        ]);
    }
    public function thread(Request $request, $id)
    {
        $user1Id = $this->uid();
        $user2Id = $id;
        $conversationId = ChatConversation::whereIn('id', function ($query) use ($user1Id, $user2Id) {
            $query->select('conversation_id')
                ->from('chat_participants')
                ->whereIn('user_id', [$user1Id, $user2Id])
                ->groupBy('conversation_id')
                ->havingRaw('COUNT(DISTINCT user_id) = 2');
        })->pluck('id')->first();
        if ($conversationId) {
            $this->messages = ChatMessage::where(['conversation_id' => $conversationId])->get();
        }
        return Inertia::render('Chat/Messages', [
            'user' => new ChatUserRescource($this->getUser($id)),
            'users' => ConversationResource::collection($this->getUsers()),
            'messages' => $this->messages ?  MessageResource::collection($this->messages) : null,
            'conversation_id' => $conversationId
        ]);
    }
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }
        $user1Id = $this->uid();
        $user2Id = $request->id;
        $conversationId = ChatConversation::whereIn('id', function ($query) use ($user1Id, $user2Id) {
            $query->select('conversation_id')
                ->from('chat_participants')
                ->whereIn(
                    'user_id',
                    [$user1Id, $user2Id]
                )
                ->groupBy('conversation_id')
                ->havingRaw('COUNT(DISTINCT user_id) = 2');
        })->pluck('id')->first();
        if (!$conversationId) {
            $newConversation = ChatConversation::create(['creator_id' => $this->uid()]);
            ChatParticipant::create(['conversation_id' => $newConversation->id, 'user_id' => $this->uid()]);
            ChatParticipant::create(['conversation_id' => $newConversation->id, 'user_id' => $user2Id]);
            $message = ChatMessage::create([
                'message' => Crypt::encryptString($request->message), 'sender_id' => $this->uid(),
                'conversation_id' => $newConversation->id,
            ]);
            broadcast(new SendMessageEvent([
                'data' => new MessageResource($message),
                'conversation_id' => $newConversation->id,
            ]));
            return response()->json(['success' => true, 'conversation_id' => $newConversation->id]);
        } else {
            if ($message = ChatMessage::create(['message' => Crypt::encryptString($request->message), 'sender_id' => $this->uid(), 'conversation_id' => $conversationId])) {

                broadcast(new SendMessageEvent([
                    'data' => new MessageResource($message),
                    // 'conversation_id' => $request->conversation_id,
                ]));
            }
            return response()->json(['success' => true, 'conversation_id' => $request->conversation_id]);
        }
    }
    public function getUser($id)
    {
        return new ChatUserRescource(User::find($id));
    }
    public function getUsers()
    {
        $users = ChatConversation::with(['participant' => function ($q) {
            $q->where('user_id', '!=', $this->uid());
        }])
            ->whereRelation('participant.user', 'user_id', '=', $this->uid())->get();
        return ConversationResource::collection($users);
    }
    public function searchUsers(Request $request)
    {

        $users = new User();
        if (!empty($request->q)) {
            $users = $users->where(function ($query) use ($request) {
                $query->where('first_name', 'like', "%$request->q%")
                    ->orWhere('last_name', 'like', "%$request->q%");
            })
                ->where('id', '!=', $this->uid());
        }
        return response()->json([
            'data' => ChatUserRescource::collection($users->get())
        ]);
    }
}
