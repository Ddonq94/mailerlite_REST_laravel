<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SubscribersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //

        $user = User::find($id);

        $api_token = $user->pluck("mailer_api_token")->first();

        session(['api_token' => $api_token]);

        return view( 'subscribers.index' );


    }


    public function load(Request $request){
        
        
        $subscribers = [
            'draw' => $request->draw,
            'data' => [],
            'total' => 0,
        ];
        
        $api_token = session('api_token');
        $subscribersApi = (new \MailerLiteApi\MailerLite($api_token))->subscribers();

        $searchEmail = $request->input('search.value');

        $filter = [
            'email' => [
                '$like' => $searchEmail
            ]
          ];

          //filter is buggy on the API

        $allSubscribers = strlen($searchEmail) ? $subscribersApi->where($filter)->limit($request->length)->offset($request->start)->get()->toArray() : $subscribersApi->limit($request->length)->offset($request->start)->get()->toArray();
        
        $totalSubscribers = $subscribersApi->count();

        foreach ($allSubscribers as $sub) {

            $sub->name = strlen($sub->name) ? $sub->name : "Not Available";
            
            $country = array_values(array_filter($sub->fields, function ($var) {
                return ($var->key == 'country');
            }))[0]->value;

            $sub->country = strlen($country) ? $country : "Not Available";

            $date = $sub->date_subscribe ? $sub->date_subscribe : $sub->date_created;

            $date=date_create($date);
            
            $sub->date = $date ? date_format($date,"d/m/Y") : "Not Available" ;
            $sub->time = $date ? date_format($date,"H:i:s") : "Not Available" ;


            $sub->action = '<button class="btn btn-sm btn-primary" onclick="edit(`'.$sub->email.'`, `'.$sub->name.'`, `'.$sub->country.'`)">Edit </button>&nbsp;<button class="btn btn-sm btn-danger" onclick="del(`'.$sub->email.'`)">Delete </button>';

        }


        $subscribers['data'] = $allSubscribers;
        $subscribers['total'] = $totalSubscribers->count;

        return $subscribers;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        
        try{

            $api_token = session('api_token');
            $subscribersApi = (new \MailerLiteApi\MailerLite($api_token))->subscribers();

            $newSubscriber = $request->only(['name', 'email', 'country']);

            $newSubscriber['fields'] = ['country' => $newSubscriber['country'] ];
            
            $subscriber = $subscribersApi->create($newSubscriber);
                        
            return response()->json($subscriber, 201);

        } catch(Exception $e) {
 
            $res =  ['error' => true, 'message' => $e->getMessage()];
            $response = response($res, 500);
		    return $response;
 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //

        try{

            $api_token = session('api_token');
            $subscribersApi = (new \MailerLiteApi\MailerLite($api_token))->subscribers();
            
            $newSubscriber = $request->only(['name', 'country']);

            $id = $request->input('id');
            // id is email

            $newSubscriber['fields'] = ['country' => $newSubscriber['country'] ];

            $subscriber = $subscribersApi->update($id, $newSubscriber);

            $subscriber->sentid = $id;
                        
            return response()->json($subscriber, 201);

        } catch(Exception $e) {
 
            $res =  ['error' => true, 'message' => $e->getMessage()];
            $response = response($res, 500);
		    return $response;
 
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //

        try{

            $api_token = session('api_token');
            $subscribersApi = (new \MailerLiteApi\MailerLite($api_token))->subscribers();

            $id = $request->input('id');
            // id is email
            
            $subscriber = $subscribersApi->delete($id);
                        
            return response()->json($subscriber, 201);

        } catch(Exception $e) {
 
            $res =  ['error' => true, 'message' => $e->getMessage()];
            $response = response($res, 500);
		    return $response;
 
        }

    }
}
