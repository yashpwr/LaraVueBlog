<?php
 
 namespace App\Http\Controllers\Api;
 
 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use App\Models\User;

 class PassportAuthController extends Controller {
    /**
    * Registration Req
    */
    public function register(Request $request) {
         $this->validate($request, [
             'name' => 'required|min:4',
             'email' => 'required|email',
             'password' => 'required|min:8',
         ]);

         try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $token = $user->createToken('Laravel9PassportAuth')->accessToken;
            return response()->json(['token' => $token], 200);

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
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    
    public function userInfo() {
        $user = auth()->user();
        return response()->json(['user' => $user], 200);
    }
}