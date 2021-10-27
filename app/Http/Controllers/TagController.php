<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Program;
use App\Models\Tag;

class TagController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function list($name){
        $user = auth()->user();
        $tag = Tag::where('name', $name)->first();
        if ($user->is_admin == '1'){
            $programs = $tag->programs()->get();
        } else {
            $programs = $tag->programs()->where('user_id', $user->id)->get();
        }


        return response()->json($programs);
    }
}
