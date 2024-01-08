<?php

namespace App\Http\Controllers\Admin;

use App\Actions\NewNotification;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function users()
    {
        $users = UserDevice::latest()->paginate(20);
        return view('user.index', compact('users'));
    }

    public function posts($type)
    {
        $posts = Post::where('type', $type)->latest()->paginate(20);
        return view('post.index', compact('posts'));
    }

    public function repotedPost()
    {
        $posts = Report::latest()->paginate(20);
        foreach ($posts as $item) {
            $post = Post::find($item->item_id);
            $user = UserDevice::where('device_id', $item->device_id)->first();
            $item->post = $post;
            $item->user = $user;
        }
        return view('post.repoted', compact('posts'));
    }
    public function deletePost($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect()->back()->with('success', 'Post Delete Successfully');
    }

    public function createSendNotification()
    {
        return view('notification');
    }

    public function sendNotification(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);


        $tokens = UserDevice::where('fcm_token', '!=', '')->pluck('fcm_token')->toArray();
        NewNotification::handle($tokens, 0,$request->body,$request->title);

        return redirect()->back()->with('success', 'Notification sent');
    }

    public function userPost($id)
    {
        $posts = Post::where('user_id', $id)->latest()->paginate(20);
        return view('user.profile', compact('posts'));
    }
}
