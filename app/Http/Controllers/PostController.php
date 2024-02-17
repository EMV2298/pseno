<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function getOne(Request $request)
    {
        $id = $request->get('id');

        $post = Post::findOrFail($id);

        return response([
            'status' => 'success',
            'data' => new PostResource($post)
        ]);
    }

    public function feed()
    {
        $subsModels = Subscription::select('user_id')->where('follower_id', Auth::id())->get();
        $subs = [];
        foreach($subsModels as $el)
        {
            $subs[] = $el->user_id;
        }
        $posts = Post::whereIn('user_id', $subs)->get(); 
        return response(new PostCollection($posts));
    }

    public function byUser(Request $request)
    {
        $id = $request->get('id');

        if(!$id)
        {
            $id = Auth::id();
        }

        $user = User::findOrFail($id);

        $posts = Post::where('user_id', $id)->get(); 

        return response(new PostCollection($posts));
    }

}
