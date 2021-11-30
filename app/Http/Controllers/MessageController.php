<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Message;

class MessageController extends Controller
{
    function list(){
        $messages = Message::all();
        return response()->json($messages);
    }

    function create(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'text' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $message = Message::create($request->all());
        return response()->json($message, 201);
    }

    function detail($id){
        $message = Message::find($id);
        return response()->json($message);
    }

    function update($id, $request){
        $message = Message::find($id);
        $message->update($request->all());
        return response()->json($message);
    }

    function destroy($id){
        $message = Message::find($id);
        $message->delete();
        return response()->json(null, 204);
    }
}
