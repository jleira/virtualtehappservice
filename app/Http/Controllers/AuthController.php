<?php
namespace App\Http\Controllers;
use Validator;
use DB;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;


class AuthController extends BaseController 
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
        $this->miinformacion=[];
    }
    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\User   $user 
     * @return mixed
     */
    public function login(Request $request) {
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        // Find the user by email
        $usero = DB::table('users')->select('email','password','first_name','last_name')->where('email',$request->email)->first();
 
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user),
                'user'=> $user
            ], 200);
        }
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }


    public function register(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:6',
            "first_name"=> 'required',
            "last_name"=> 'required',
//            "gender"=> 'required',
//            "birthday"=> 'required',
//            "phone"=> 'required',
//            "cellphone"=> 'required',
//            "address"=> 'required',
//            "city"=> 'required',
//            "province"=> 'required',
//            "country"=> 'required',
        ]);

         DB::table('users')->insert(
            [
                'email' => $request->email, 
                'password' => Hash::make($request->password),
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name]
        ); 

        return $this->login($request);
    }

    public function findbyid($id, Request $request)
    {
        $this->miinformacion=$request->auth;
        if($id==0){
            $id=$this->miinformacion->id;
        }
     $seguidores= DB::table('seguidores')->where('usuario_id',$id)->count();
     $seguidos=  DB::table('seguidores')->where('seguidor_id',$id)->count();
     $nombre=  DB::table('users')->where('id',$id)->value('first_name').' '.DB::table('users')->where('id',$id)->value('last_name');
     $ejemplares =DB::table('mascotas')->where('id_usuario',$id)->count();
     $losigo =DB::table('seguidores')->where('seguidor_id',$this->miinformacion->id)->count();
     $datos['seguidores']=$seguidores;
     $datos['seguidos']=$seguidos;
     $datos['nombre']=$nombre;
     $datos['ejemplares']=$ejemplares;
     $datos['losigo']=$losigo;
     
       return response($datos,200);
    }



    public function seguir($id, Request $request){
        $this->miinformacion=$request->auth;
        $yalosigue=0;
        $yalosigue=DB::table('seguidores')->where('usuario_id',$id)->where('seguidor_id',$this->miinformacion->id)->count();
        if($yalosigue==0){
            DB::table('seguidores')->insert(
                [
                    'usuario_id' => $id, 
                    'seguidor_id' =>$this->miinformacion->id,
                ]); 
                return response('Ahora ud sigue a este usuario',200);
        }else{
            return response('Ud ya sigue a este usuario',419);
        }
    }
    public function dejardeseguir($id, Request $request){
        $this->miinformacion=$request->auth;
        $yalosigue=0;
        $yalosigue=DB::table('seguidores')->where('usuario_id',$id)->where('seguidor_id')->count();

        if($yalosigue==0){
            DB::table('seguidores')->where(
                    'usuario_id',$id)->where( 
                    'seguidor_id' , $this->miinformacion->id)->delete(); 
                return response('Ahora ud no sigue a este usuario',200);

        }else{
            return response('Ud no sigue a este usuario',419);
        }

    }

    public function mydata(Request $request)
    {
       return response($request->auth,200);
    }


}