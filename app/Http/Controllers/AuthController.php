<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'list']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized', "request" => $credentials->all()], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|string|max:100|unique:users',
            'password' => 'required|min:8|string',
            'identification_card' => 'required|alpha_dash',
            'born' => 'required|date|date_format:d/m/Y',
            'address' => 'required|max:280',
            'state' => 'required|max:32',
            'city' => 'required|max:32',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        if($validator->fails()){
            return response()->json(["response" => $validator->errors()->toJson(), "request" => $request->all()],400);
        }


        $user = User::create(array_merge(
            $validator->validate(),
            ['password' => bcrypt($request->password)]
        ));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $user->image = base64_encode(file_get_contents($image));
            $user->save();
        }

        return response()->json([
            'message' => '??Usuario registrado exitosamente!',
            'user' => $user
        ], 201);
    }

    public function update(Request $request, $id=null){

        $id_user = auth()->user()->id;
        $admin = auth()->user()->is_admin;

        if($id==null){

            // if /update without id
            $user = auth()->user();
            $user->update($request->all());

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $user->image = base64_encode(file_get_contents($image));
                $user->save();
            }

            return \response()->json(['message' => 'User updated successfully','id' => $user->id, 'user' => auth()->user(), "request" => $request->all()], 200);

        } elseif($id_user == $id or $admin == 1){

            // if /update/{id} with id
            $user = User::find($id);
            if ($user) {
                $user->update($request->all());

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $user->image = base64_encode(file_get_contents($image));
                    $user->save();
                }

                return \response(['message' => 'User updated successfully','id' => $id, 'user' => $user, "request" => $request->all()], 200);
            } else {
                return \response(['message' => 'User not found', "request" => $request->all()], 404);
            }
            return \response(User::find($id), 200);

        }
        return response()->json(['message' => 'Don\'t have permission to update this user', "request" => $request->all()], 401);
    }

    public function destroy($id=null){

        $admin = json_decode((auth()->user()), true)['is_admin'];
        $user_id = json_decode((auth()->user()), true)['id'];

        if($id == null){

            // if /destroy without id
            $user = User::find($user_id)->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);

        } elseif($admin == 1 or $id_user == $id){

            // if /destroy/{id} with id
            $user = User::find($id);
            if($user){ // If user exists
                $user->delete();
                return response()->json(['message' => 'User deleted successfully','id' => $id, 'user' => $user], 200);
            } else { // If user doesn't exists
                return response()->json(['message' => 'User not found'], 404);
            }

        } else {
            return \response('Destroy not allowed. Not an admin an not your account', 403);
        }

    }

    public function list(){

        $users = User::all();
        return response()->json(['users' => $users], 200);

    }
}
