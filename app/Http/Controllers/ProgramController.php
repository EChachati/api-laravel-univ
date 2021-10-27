<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Program;
use App\Models\Tag;


class ProgramController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function list()
    {
        $id_user = json_decode((auth()->user()), true)['id'];
        $user = User::find($id_user);
        $is_admin = json_decode($user, true)['is_admin'];
        $is_active = json_decode($user, true)['is_active'];



        if ($is_admin == '1') {
            $list = Program::with('tags')->all();
        } else {
                $list = Program::with('tags')->where('user_id', $id_user)->get();
        }
        return response()->json($list, 200);
    }

    public function create(Request $request)
    {

        $id_user = json_decode((auth()->user()), true)['id'];
        $user = User::find($id_user);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
            'image_1' => 'image',
            'image_2'=> 'image',
            'image_3' => 'image',

        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }
        $program = Program::create(
            array_merge(
                $validator->validate(),
                ['user_id' => $id_user]
            )
        );


       $tags = ($request['tags']);

        foreach ($tags as $tag_name) {
            $tag =  Tag::firstOrCreate(['name' => $tag_name]);
            $program->tags()->attach($tag);
        }
        $program->user()->associate($user);
        $program->save();

        return response()->json(Program::with('tags')->find($program->id), 201);

    }

    public function update(Request $request, $id){
        $user = auth()->user();
        if ($user->is_admin == '1'){
            $programs = Program::all()->pluck('id');
        } else {
            $programs = Program::where('user_id', $user->id)->pluck('id');
        }

        if ($programs->contains($id)){
            $program = Program::with('tags')->find($id);
            //return response()->json($program, 200);

            $program->update($request->all());

            $tags = ($request['tags']);
            $program->tags()->detach();

            foreach ($tags as $tag_name) {
                $tag =  Tag::firstOrCreate(['name' => $tag_name]);
                $program->tags()->attach($tag);
            }

            $program->save();
            return response()->json(Program::with('tags')->find($id), 200);
        } else {
            return \response()->json(['message' => 'Program not found Or not allowed to update'], 403);
        }

    }

    public function destroy($id){
        $user = auth()->user();
        if ($user->is_admin == '1'){
            $programs = Program::all()->pluck('id');
        } else {
            $programs = Program::where('user_id', $user->id)->pluck('id');
        }
        if($programs->contains($id)){
            $program = Program::find($id);
            $program->delete();
            return response()->json(['message' => 'Program deleted successfully'], 200);
        } else {
            return \response()->json(['message' => 'Program not found Or not allowed to delete'], 403);
        }

    }

    public function detail($id) {
        $user = auth()->user();
        if ($user->is_admin == '1'){
            $programs = Program::all()->pluck('id');
        } else {
            $programs = Program::where('user_id', $user->id)->pluck('id');
        }

        if($programs->contains($id)){
            return response()->json(Program::with('tags')->find($id), 200);
        } else {
            return \response()->json(['message' => 'Program not found Or not allowed to see'], 403);
        }

    }
}
