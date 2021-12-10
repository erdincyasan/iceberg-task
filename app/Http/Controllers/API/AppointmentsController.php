<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DateRequest;
use App\Interfaces\AppointmentsInterface;
use App\Interfaces\ContactsInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class AppointmentsController extends Controller
{
    protected $appointmentsInterface;
    protected $contactsInterface;
    public function __construct(AppointmentsInterface $appointmentsInterface,ContactsInterface $contactsInterface){
        $this->appointmentsInterface=$appointmentsInterface; 
        $this->contactsInterface=$contactsInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return $this->appointmentsInterface->getAllAppointments();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DateRequest $request)
    {
        $bingMapApiKey="";
        //
       // $minute=""
       //https://dev.virtualearth.net/REST/v1/Routes/DistanceMatrix?origins=38.22840720437843,27.998497677629263&destinations=38.230405741410976,27.984730350380797&travelMode=driving&timeUnit=minute&key=ApAoWM0awj4eQfYfOGQER7AGbs6ShA0YHpzpH37BzqpLQrcdigwAkH2obpiYFCJS
        $coreDistanceCalculaterUrl="https://dev.virtualearth.net/REST/v1/Routes/DistanceMatrix?";
        $contactId=$this->contactsInterface->contactRequest($request->only("name","surname","email","phone"));
        
        //Calculating Distance!!
        $requestURI="https://api.postcodes.io/postcodes/".$request->date_address;
        $response=Http::get($requestURI)['result'];
        
        $dateAddressLatitude=$response["latitude"];
        $dateAddressLongitude=$response["longitude"];
        $estateAddressResponse=Http::get("https://api.postcodes.io/postcodes/cm27pj")["result"];
        $estateAddressLongitude=$estateAddressResponse["longitude"];
        $estateAddressLatitude=$estateAddressResponse['latitude'];

        $coreDistanceCalculaterUrl.="origins=$estateAddressLatitude,$estateAddressLongitude&destinations=$dateAddressLatitude,$dateAddressLongitude&travelMode=driving&timeUnit=minute&key=$bingMapApiKey";
        $calculateDistance=Http::get($coreDistanceCalculaterUrl);
        $distanceSource=json_decode($calculateDistance->body());
        $distanceDuration=$distanceSource->resourceSets[0]->resources[0]->results[0]->travelDuration;
        $distance=$distanceSource->resourceSets[0]->resources[0]->results[0]->travelDistance;
        //!!End calculating distance
        $dateTime=Carbon::createFromDate($request->date);
        $arriveTime=Carbon::createFromDate($request->date)->addMinute($distanceDuration+60);
        $leaveTime=Carbon::createFromDate($request->date)->subMinute($distanceDuration);
        if($this->appointmentsInterface->checkTwoDate((object)["leave"=>$leaveTime,"arrive"=>$arriveTime,"except"=>null])){
            return $this->appointmentsInterface->appointmentRequest((object)["leaving_the_office"=>$leaveTime,"arrive_office"=>$arriveTime,"date"=>$dateTime,"contacts_id"=>$contactId,"address"=>$request->date_address,"distance"=>$distance]);
        }
        return response()->json(["message"=>"We are not available on this times"],200);

        //return response()->json($dateTime,200);
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
       return $this->appointmentsInterface->getAppointmentById($id);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DateRequest $request, $id)
    {
        //
        $bingMapApiKey="";
        //
       // $minute=""
       //https://dev.virtualearth.net/REST/v1/Routes/DistanceMatrix?origins=38.22840720437843,27.998497677629263&destinations=38.230405741410976,27.984730350380797&travelMode=driving&timeUnit=minute&key=ApAoWM0awj4eQfYfOGQER7AGbs6ShA0YHpzpH37BzqpLQrcdigwAkH2obpiYFCJS
        $coreDistanceCalculaterUrl="https://dev.virtualearth.net/REST/v1/Routes/DistanceMatrix?";
        $contactId=$this->contactsInterface->contactRequest($request->only("name","surname","email","phone"));
        
        //Calculating Distance!!
        $requestURI="https://api.postcodes.io/postcodes/".$request->date_address;
        $response=Http::get($requestURI)['result'];
        
        $dateAddressLatitude=$response["latitude"];
        $dateAddressLongitude=$response["longitude"];
        $estateAddressResponse=Http::get("https://api.postcodes.io/postcodes/cm27pj")["result"];
        $estateAddressLongitude=$estateAddressResponse["longitude"];
        $estateAddressLatitude=$estateAddressResponse['latitude'];

        $coreDistanceCalculaterUrl.="origins=$estateAddressLatitude,$estateAddressLongitude&destinations=$dateAddressLatitude,$dateAddressLongitude&travelMode=driving&timeUnit=minute&key=$bingMapApiKey";
        $calculateDistance=Http::get($coreDistanceCalculaterUrl);
        $distanceSource=json_decode($calculateDistance->body());
        $distanceDuration=$distanceSource->resourceSets[0]->resources[0]->results[0]->travelDuration;
        $distance=$distanceSource->resourceSets[0]->resources[0]->results[0]->travelDistance;
        //!!End calculating distance
        $dateTime=Carbon::createFromDate($request->date);
        $arriveTime=Carbon::createFromDate($request->date)->addMinute($distanceDuration+60);
        $leaveTime=Carbon::createFromDate($request->date)->subMinute($distanceDuration);
        if($this->appointmentsInterface->checkTwoDate((object)["leave"=>$leaveTime,"arrive"=>$arriveTime,"except"=>$id])){
            return $this->appointmentsInterface->appointmentRequest((object)["leaving_the_office"=>$leaveTime,"arrive_office"=>$arriveTime,"date"=>$dateTime,"contacts_id"=>$contactId,"address"=>$request->date_address,"distance"=>$distance],$id);
        }
        return response()->json(["message"=>"We are not available on this times"],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       return $this->appointmentsInterface->deleteAppointmentsById($id);
    }
}
