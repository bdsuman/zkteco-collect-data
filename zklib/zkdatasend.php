<?php

function debug($data){
    echo '<pre>';
        print_r($data);
    echo '</pre>';
}

function data_send($uid,$datetime,$base_url='localhost:8000'){
    $request_data = array(
        'zk_user_id'=> $uid,
        'entry_time'=>$datetime
    );
    
    $url = curl_init($base_url.'/api/zkuser-attandence');
    curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url,CURLOPT_POSTFIELDS, $request_data);
    curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

    $resultdata = curl_exec($url);
    curl_close($url);
    return $resultdata;
}

function user_save($uid,$name,$role,$password,$base_url='localhost:8000'){
    $request_data = array(
        'uid'=> $uid,
        'name'=> $name,
        'role'=> $role,
        'password'=> $password, 
    );
    
    $url = curl_init($base_url.'/api/zkuser-create');
    curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url,CURLOPT_POSTFIELDS, $request_data);
    curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

    $resultdata = curl_exec($url);
    curl_close($url);
    return $resultdata;
}