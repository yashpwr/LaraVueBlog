<?php
 
 namespace App\Http\Controllers\Api;
 
 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use App\Models\User;
 use Illuminate\Http\Response;

 class PassportAuthController extends Controller {
    /**
    * Registration Req
    */
    public function register(Request $request) {
         $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required'],
        ]);

         try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $token = $user->createToken('Laravel9PassportAuth')->accessToken;
            return response([
                'user' => $user,
                'access_token' => $token
            ], Response::HTTP_CREATED);

        } catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == '1062'){
                return response()->json([
                    'errors' => array('email' => ['Duplicate Email Found'])
                ], 422);
            }
        }
     }
   
     /**
      * Login Req
      */
    public function login(Request $request) {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
   
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('Laravel9PassportAuth')->accessToken;
            // return response()->json(['token' => $token], 200);

            return response([
                'user' => auth()->user(),
                'access_token' => $token
            ], Response::HTTP_OK);

        } else {
            // return response()->json(['error' => 'Unauthorised'], 401);
            return response([
                'message' => 'This User does not exist'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
    
    public function userInfo() {
        $user = auth()->user();
        return response()->json(['user' => $user], 200);
    }
}