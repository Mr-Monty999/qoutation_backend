<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Requests\Api\User\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\MessageRecipient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{

    public function index(Request $request)
    {

        if (!in_array($request->type, ["sent", "received"]))
            abort(403);

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
            abort(403);

        try {

            $data["sender_id"] = $user->id;
            if ($request->hasFile("attachments")) {
                $fileName = time() . '-' . $request->file("attachments")->getClientOriginalName();
                $data["attachments"] = $request->file("attachments")->storeAs("messages/attachments", $fileName, "public");
            }

            $message = Message::create($data);

            $messageRecipient = MessageRecipient::create([
                "receiver_id" => $receiver->id,
                "message_id" => $message->id
            ]);

            $messageRecipient->Load("receiver.buyer", "receiver.supplier", "message");

            DB::commit();
            return response()->json([
                "data" => $messageRecipient
            ], 201);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, rollback the transaction
            return response()->json(["msg" => "error"], 400);
        }
    }

    public function show(Message $message)
    {
        $message->load("recipient", "sender.buyer", "sender.supplier");
        return response()->json([
            "data" => $message
        ]);
    }
    public function read(Message $message)
    {

        $message->load("recipient", "sender.buyer", "sender.supplier");

        if ($message->recipient->read_at != null)
            abort(403);

        $message->recipient()->update([
            "read_at" => now()
        ]);
        return response()->json([
            "data" => $message
        ]);
    }
}
