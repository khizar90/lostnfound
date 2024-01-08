<?php

namespace App\Actions;


class NewNotification
{
    public static function handle($tokens,$body,$title,$arr)
    {


        // $SERVER_API_KEY = 'AAAARfb-hug:APA91bHIbhcG77d0MvKLhZFdnlL4136oMe9sHY0qFa-ezjn_8oIYPXUKGWHDrCSHn5iUaNKpqNK9_7W66oWQw6Jj3mkT7rYx7bv24U2zKHe3eIXGEB52ELp5iSk82rHYL822zjXKZ6ET';

        // $data = [
        //     "registration_ids" => $tokens,
        //     "notification" => [
        //         "title" => $title,
        //         "body" => $body,
        //         'data_id' => $data_id
        //     ]
        // ];
        // $dataString = json_encode($data);

        // $headers = [
        //     'Authorization: key=' . $SERVER_API_KEY,
        //     'Content-Type: application/json',
        // ];

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        // $response =  curl_exec($ch);


        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $title,
            'body' => $body,
        ];

        $extraNotificationData = $arr;

        $fcmNotification = [
            'registration_ids'        => $tokens, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key= AAAARfb-hug:APA91bHIbhcG77d0MvKLhZFdnlL4136oMe9sHY0qFa-ezjn_8oIYPXUKGWHDrCSHn5iUaNKpqNK9_7W66oWQw6Jj3mkT7rYx7bv24U2zKHe3eIXGEB52ELp5iSk82rHYL822zjXKZ6ET',
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;



    }
}
