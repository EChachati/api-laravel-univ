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
        /*
        $user = auth()->user();
        $id_user = $user->id;
        $is_admin = $user->is_admin;
        $is_active = $user->is_active;

        if ($is_admin == '1') {
            $list = Program::with('tags')->get();
        } else {
            $list = Program::with('tags')->where('user_id', $id_user)->get();
        }
        */
        $list = Program::with('tags')->get();
        return response()->json($list, 200);
    }

    public function create(Request $request)
    {

        //return response()->json($request->file('image_1')->getClientMimeType(), 200);
        //return response()->json($request->hasFile('image_1'), 200);
        //return response()->json(get_class_methods($request->file('image_1')), 200);


        $user = auth()->user();
        if (!$user) {
            return response()->json(
                [
                    'error' => 'Not user detected',
                    'request' => $request->all(),
                    'user' => $user
                ],
                401
            );
        }
        $id_user = $user->id;

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
            'image_1' => 'image',
            'image_2' => 'image',
            'image_3' => 'image',

        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    $validator->errors()->toJson(),
                    "request" => $request->all(),
                    "user" => $user
                ],
                400
            );
        }




        $program = Program::create(
            array_merge(
                $validator->validate(),
                ['user_id' => $id_user]
            )
        );


        if ($request->hasFile('image_1')) {
            $image_1 = $request->file('image_1');
            $program->image_1 = base64_encode(file_get_contents($image_1)); //$image_1->hashName();
        }

        if ($request->hasFile('image_2')) {
            $image_2 = $request->file('image_2');
            $program->image_2 = base64_encode(file_get_contents($image_2));
        }

        if ($request->hasFile('image_3')) {
            $image_3 = $request->file('image_3');
            $program->image_3 = base64_encode(file_get_contents($image_3));
        }


        $tags = ($request['tags']);
        $tags = explode(',', $tags);

        foreach ($tags as $tag_name) {
            $tag =  Tag::firstOrCreate(['name' => $tag_name]);
            $program->tags()->attach($tag);
        }

        $url = $request['url'];
        if ($url) {
            $program->url = $url;
        }

        $subtype = $request['subtype'];
        if ($subtype) {
            $program->subtype = $subtype;
        }

        $program->user()->associate($user);
        $program->save();

        return response()->json(
            [
                "result" => Program::with('tags')->find($program->id),
                "request" => $request->all(),
                "user" => $user
            ],
            201
        );
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->is_admin == '1') {
            $programs = Program::all()->pluck('id');
        } else {
            $programs = Program::where('user_id', $user->id)->pluck('id');
        }

        if ($programs->contains($id)) {
            $program = Program::with('tags')->find($id);
            $program->update($request->all());

            $tags = ($request['tags']);
            $program->tags()->detach();

            foreach ($tags as $tag_name) {
                $tag =  Tag::firstOrCreate(['name' => $tag_name]);
                $program->tags()->attach($tag);
            }

            if ($request->hasFile('image_1')) {
                $image_1 = $request->file('image_1');
                $program->image_1 = base64_encode(file_get_contents($image_1)); //$image_1->hashName();
            }

            if ($request->hasFile('image_2')) {
                $image_2 = $request->file('image_2');
                $program->image_2 = base64_encode(file_get_contents($image_2));
            }

            if ($request->hasFile('image_3')) {
                $image_3 = $request->file('image_3');
                $program->image_3 = base64_encode(file_get_contents($image_3));
            }


            $program->save();

            return response()->json(Program::with('tags')->find($id), 200);
        } else {
            return \response()->json(
                [
                    'message' => 'Program not found Or not allowed to update',
                    "request" => $request->all(),
                    "user" => $user
                ],
                403
            );
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_admin == '1') {
            $programs = Program::all()->pluck('id');
        } else {
            $programs = Program::where('user_id', $user->id)->pluck('id');
        }
        if ($programs->contains($id)) {
            $program = Program::find($id);
            $program->delete();
            return response()->json(
                [
                    'message' => 'Program deleted successfully',
                    "user" => $user
                ],
                200
            );
        } else {
            return \response()->json(
                [
                    'message' => 'Program not found Or not allowed to delete',
                    "user" => $user
                ],
                403
            );
        }
    }

    public function detail($id)
    {
        $user = auth()->user();
        if ($user->is_admin == '1') {
            $programs = Program::all()->pluck('id');
        } else {
            $programs = Program::where('user_id', $user->id)->pluck('id');
        }

        if ($programs->contains($id)) {
            return response()->json(Program::with('tags')->find($id), 200);
        } else {
            return \response()->json(
                [
                    'message' => 'Program not found Or not allowed to see',
                    "user" => $user
                ],
                403
            );
        }
    }
}
