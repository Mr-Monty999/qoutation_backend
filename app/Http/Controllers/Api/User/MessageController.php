<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Requests\Api\User\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Jobs\EmailJob;
use App\Mail\SendNewMessageNotification;
use App\Models\MessageRecipient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{

    public function getCount()
    {
        $user = auth()->user();
        $unreadMessageCount = MessageRecipient::where("receiver_id", $user->id)
            ->whereNull("read_at")
            ->count();
        $sentMessageCount = Message::where("sender_id", $user->id)->count();

        return response()->json([
            "data" => [
                "unread_messages_count" => $unreadMessageCount,
                "sent_messages_count" => $sentMessageCount
            ]
        ]);
    }
    public function index(Request $request)
    {

        if (!in_array($request->type, ["sent", "received"]))
            return response()->json([], 403);

        $user = auth()->user();

        $messages = null;

        if ($request->type == "sent") {
            $messages = Message::with("recipient.receiver.buyer", "recipient.receiver.supplier", "parentMessage")
                ->where("sender_id", $user->id)
                ->latest()
                ->paginate(10);
        } else if ($request->type == "received") {
            $messages = MessageRecipient::with("message.sender.buyer", "message.sender.supplier")
                ->where("receiver_id", $user->id)
                ->latest()
                ->paginate(10);
        }

        return response()->json([
            "data" => $messages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request)
    {

        $data = $request->validated();

        $user = auth()->user();


        $receiver = User::where("email", $data["receiver_email"])
            ->firstOrFail();


        if ($receiver->id == $user->id)
            return response()->json([], 403);

        try {

            $data["sender_id"] = $user->id;
            if ($request->hasFile("attachments")) {
                $data["attachments"] = [];
                $files = [];
                foreach ($request->file("attachments") as $attachment) {
                    $files["name"] = time() . '-' . $attachment->getClientOriginalName();
                    $files["url"] = $attachment->storeAs("messages/attachments", $files["name"], "public");
                    array_push($data["attachments"], $files);
                }
            }

            $message = Message::create($data);


            $sendMessage = MessageRecipient::where("receiver_id", $receiver->id)
                ->whereHas("message", function ($q) use ($user) {
                    $q->where("sender_id", $user->id);
                })
                ->where("created_at", ">", now()->subMinutes(5))
                ->exists();

            $messageRecipient = MessageRecipient::create([
                "receiver_id" => $receiver->id,
                "message_id" => $message->id
            ]);

            $messageRecipient->Load("receiver.buyer", "receiver.supplier", "message");

            if (!$sendMessage)
                EmailJob::dispatch([
                    "type" => "send_message",
                    "target_email" => $receiver->email,
                    "message_recipient_id" => $messageRecipient->id,
                    "sender_name" => $user->name
                ]);

            DB::commit();
            return response()->json([
                "data" => $messageRecipient
            ], 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }

    public function showMessage(Message $message)
    {
        $user = auth()->user();

        if ($message->sender_id != $user->id)
            return response()->json([], 403);

        $message->load("recipient.receiver.supplier", "recipient.receiver.buyer", "sender.buyer", "sender.supplier");

        return response()->json([
            "data" => $message
        ]);
    }
    public function showMessageRecipient(MessageRecipient $messageRecipient)
    {
        $user = auth()->user();

        if ($messageRecipient->receiver_id != $user->id)
            return response()->json([], 403);

        $messageRecipient->load("receiver.supplier", "receiver.buyer", "message.sender.buyer", "message.sender.supplier");


        return response()->json([
            "data" => $messageRecipient
        ]);
    }
    public function readMessageRecipient(MessageRecipient $messageRecipient)
    {

        $user = auth()->user();
        if ($messageRecipient->read_at != null || $messageRecipient->receiver_id != $user->id)
            return response()->json([], 403);

        $messageRecipient->load("message.sender.buyer", "message.sender.supplier");

        $messageRecipient->update([
            "read_at" => now()
        ]);
        return response()->json([
            "data" => $messageRecipient
        ]);
    }
}
