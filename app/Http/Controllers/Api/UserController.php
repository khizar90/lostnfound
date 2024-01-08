<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Pusher\Pusher;
use App\Actions\NewNotification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\returnSelf;

class UserController extends Controller
{
    public function splash(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required'
        ]);

        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' =>  $errorMessage,
            ]);
        }

        $check = UserDevice::where('device_id', $request->device_id)->first();
        if ($check == null) {
            $user = new UserDevice();
            $user->device_id = $request->device_id;
            $user->fcm_token = $request->fcm_token ?: '';
            $user->device_name = $request->device_name ?: '';
            $user->timezone = $request->timezone ?: '';
            $user->save();

            $user = UserDevice::where('device_id', $user->device_id)->first();

            return response()->json([
                'status' => true,
                'action' =>  'Register Successfully',
                'data' => $user
            ]);
        }
        $check->fcm_token = $request->fcm_token;
        $check->save();
        return response()->json([
            'status' => true,
            'action' =>  'Login Successfully',
            'data' => $check
        ]);
    }

    public function addPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_devices,id',
            'type' => 'required',
            'image' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' =>  $errorMessage,
            ]);
        }

        $file = $request->file('image');
        /*
        $extension = $file->getClientOriginalExtension();
        $mime = explode('/', $file->getClientMimeType());
        $filename = time() . '-' . uniqid() . '.' . $extension;
        if ($file->move('uploads/post/', $filename))
            $image = '/uploads/post/' . $filename;*/

        $path = Storage::disk('s3')->putFile('user/'.$request->user_id.'/post', $file);
        $path = Storage::disk('s3')->url($path);
        $post = new Post();
        $post->user_id = $request->user_id;
        $post->type = $request->type;
        $post->image = $path;
        $post->title = $request->title ?: '';
        $post->location = $request->location;
        $post->lat = $request->lat;
        $post->lng = $request->lng;
        $post->description = $request->description ?: '';
        $post->time = strtotime(date('Y-m-d H:i:s'));

        $post->save();

        return response()->json([
            'status' => true,
            'action' =>  'Post added',
        ]);
    }

    public function home(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' =>  $errorMessage,
            ]);
        }

        $userLat = $request->lat;
        $userLng = $request->lng;
        $radius = 50;


        $type = $request->type;


        $posts = DB::table('posts')
            ->select(
                '*',
                DB::raw("(6371 * acos(cos(radians($userLat)) * cos(radians(lat)) * cos(radians(lng) - radians($userLng)) + sin(radians($userLat)) * sin(radians(lat)))) AS distance")
            )
            ->where('type', $type)
            // ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->paginate(12);


        return response()->json([
            'status' => true,
            'action' =>  'Posts',
            'data' => $posts
        ]);
    }

    public function report(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_devices,id',
            'item_id' => 'required',
            'reason' => 'required',
        ]);

        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' =>  $errorMessage,
            ]);
        }

        $post = Post::find($request->item_id);
        if ($post) {
            $report = new Report();
            $report->user_id = $request->user_id;
            $report->item_id = $request->item_id;
            $report->reason = $request->reason;
            $report->save();

            return response()->json([
                'status' => true,
                'action' =>  'Report send',
            ]);
        }

        return response()->json([
            'status' => false,
            'action' =>  'Invalid post ID',
        ]);
    }

    public function myItem(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:user_devices,id',
            'type' => 'required',
        ]);

        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' =>  $errorMessage,
            ]);
        }

        $posts = Post::where('user_id', $request->user_id)->where('type', $request->type)->latest()->paginate(12);
        return response()->json([
            'status' => true,
            'action' =>  'Posts',
            'data' => $posts
        ]);;
    }

    public function deleteItem($id)
    {
        $item = Post::where('id', $id)->first();
        if ($item) {
            $item->delete();
            return response()->json([
                'status' => true,
                'action' =>  'Post deleted',
            ]);
        }
        return response()->json([
            'status' => false,
            'action' =>  'Post ID is invalid',
        ]);
    }


    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'type' => 'required'
        ]);

        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' =>  $errorMessage,
            ]);
        }

        $userLat = $request->lat;
        $userLng = $request->lng;
        $radius = 50;

        $value = $request->keyword;

        $posts = DB::table('posts')
            ->select(
                '*',
                DB::raw("(6371 * acos(cos(radians($userLat)) * cos(radians(lat)) * cos(radians(lng) - radians($userLng)) + sin(radians($userLat)) * sin(radians(lat)))) AS distance")
            )
            ->where('title', 'like', '%' . $value . '%')->where('type', $request->type)
            // ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->paginate(12);


        // $post = Post::where('title', 'like', '%' . $value . '%')->latest()->paginate(12);
        return response()->json([
            'status' => true,
            'action' => 'Posts',
            'data' =>  $posts,
        ]);
    }

    public function sendMessage(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'from_id' => 'required|exists:user_devices,id',
            'to_id' => 'required|exists:user_devices,id',
            'type' => 'required',
            'message' => 'required_without:attachment',
        ]);

        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' =>  $errorMessage,
            ]);
        }

        $message = new Message();

        $path ='';
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            // $extension = $file->getClientOriginalExtension();
            // $mime = explode('/', $file->getClientMimeType());
            // $filename = time() . '-' . uniqid() . '.' . $extension;
            // if ($file->move('uploads/messages/', $filename))
            //     $image = '/uploads/messages/' . $filename;
            // $message->attachment = $image;

            $path = Storage::disk('s3')->putFile('user/'.$request->user_id.'message', $file);
            $path = Storage::disk('s3')->url($path);
        }

        $find = Message::where('from_to', $request->from_id . '-' . $request->to_id)->orWhere('from_to', $request->to_id . '-' . $request->from_id)->first();
        $channel = '';
        if ($find) {
            $channel = $find->from_to;
            $message->from_to = $find->from_to;
            Message::where('from_to', $message->from_to)->where('to_id', $request->from_id)->where('is_read', 0)->update(['is_read' => 1]);
        } else {
            $channel =  $request->from_id . '-' . $request->to_id;
            $message->from_to = $request->from_id . '-' . $request->to_id;
        }

        $message->from_id = $request->from_id;
        $message->to_id = $request->to_id;
        $message->type = $request->type;
        $message->message = $request->message ?: '';
        $message->attachment = $path;
        $message->time = strtotime(date('Y-m-d H:i:s'));
        $message->save();


        $message = Message::where('from_id', $request->from_id)->where('to_id', $request->to_id)->latest()->first();

        $user = UserDevice::find($message->from_id);
        // $message->user = $user;
        $pusher = new Pusher('c89398276f92a8fb248b', '0dbf23abbed9648d8eae', 1698381, [
            'cluster' => 'us2',
            'useTLS' => true,
        ]);

        $pusher->trigger($message->from_to, 'new-message', $message);

        $tokens = UserDevice::where('id', $request->to_id)->where('fcm_token', '!=', '')->pluck('fcm_token')->toArray();
        NewNotification::handle($tokens, 'Someone has send you a message', 'LostnFound', ['data_id' => $message->from_id]);

        return response()->json([
            'status' => true,
            'action' =>  'Message Send',
            'message' =>  $message
        ]);
    }

    public function readMessage($login_id, $from_id)
    {

        $user = UserDevice::where('id', $from_id);
        if ($user) {
            Message::where('from_id', $from_id)->where('to_id', $login_id)->where('is_read', 0)->update(['is_read' => 1]);

            return response()->json([
                'status' => true,
                'action' =>  'Message read',
            ]);
        }

        return response()->json([
            'status' => false,
            'action' =>  'User not found',
        ]);
    }

    public function conversation($login_id, $from_id)
    {
        $user = UserDevice::where('id', $from_id);
        if ($user) {
            $messages = Message::where('from_id', $from_id)->where('to_id', $login_id)->orwhere('from_id', $login_id)->where('to_id', $from_id)->get();
            Message::where('from_id', $from_id)->where('to_id', $login_id)->where('is_read', 0)->update(['is_read' => 1]);

            return response()->json([
                'status' => true,
                'action' =>  'Messages',
                'data' => $messages
            ]);
        }

        return response()->json([
            'status' => false,
            'action' =>  'User not found',
        ]);
    }

    public function unreadCount($login_id)
    {

        $user = UserDevice::where('id', $login_id);
        if ($user) {

            $count = Message::where('to_id', $login_id)->where('is_read', 0)->count();
            return response()->json([
                'status' => true,
                'action' =>  'Unread Count',
                'data' => $count
            ]);
        }

        return response()->json([
            'status' => false,
            'action' =>  'User not found',
        ]);
    }

    public function inbox($user_id)
    {
        $get = Message::select('from_to')->where('from_id', $user_id)->orWhere('to_id', $user_id)->groupBy('from_to')->pluck('from_to');
        $arr = [];
        foreach ($get as $item) {
            $message = Message::where('from_to', $item)->latest()->first();
            if ($message) {
                if ($message->from_id == $user_id) {
                    $user = UserDevice::where('id', $message->to_id)->first();
                }
                if ($message->to_id == $user_id) {
                    $user = UserDevice::where('id', $message->from_id)->first();;
                }
            }
            $unread_count = Message::where('from_to', $item)->where('to_id', $user_id)->where('is_read', 0)->count();
            $obj = new stdClass();
            $obj->message = $message->message;
            $obj->time = $message->time;
            $obj->user_id = $user->id;
            $obj->user = 'User ' . $user->id;
            $obj->unread_count = $unread_count;
            $arr[] = $obj;
        }

        $sorted = collect($arr)->sortByDesc('time');
        /*
        ---COMMENTED FOR FUTURE USE IF NEEDED FOR PAGINATION---
        $sorted = $sorted->forPage($request->page, 20);
        */
        $arr1 = [];
        $count = 0;
        foreach ($sorted as $item) {
            $arr1[] = $item;
        }
        return response()->json([
            'status' => true,
            'action' =>  'Inbox',
            'data' => $arr1
        ]);
    }
}
