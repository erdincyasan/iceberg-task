<?php

namespace App\Repositories;

use App\Interfaces\AppointmentsInterface;
use App\Models\Appointments;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class AppointmentsRepository implements AppointmentsInterface
{
    use ResponseAPI;
    public function getAllAppointments()
    {
        try {
            $appointments = Appointments::with(["contact"=>function ($query){
                $query->orderBy("id");
            }])->filter()->get();
            return $this->success("All Appointments", $appointments);
        } catch (Exception $e) {
            return $this->error("An error occurred".$e->getMessage(), 500);
        }
    }
    public function getAppointmentById($id)
    {
        try {
            $appointments = Appointments::with(["contact"])->find($id);
            if($appointments){
                return $this->success("Appointment with id $id", $appointments);
            }
            return $this->success("No appointment with this id $id",404);
        } catch (Exception) {
            return $this->error("An error occurred", 500);
        }
    }
    public function deleteAppointmentsById($id)
    {
        DB::beginTransaction();
        try {
            $appointment = Appointments::find($id);
            if (!$appointment) {
                return $this->error("No appointment with this id $id", 404);
            }
            DB::commit();
            $appointment->delete();
            return $this->success("Successfully deleted ", $appointment);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error("An error occurred", 500);
        }
    }
    public function appointmentRequest($appointmentRequest, $id=null)
    {
        DB::beginTransaction();
        // DB::connection()->enableQueryLog();
        try {
            $appointment = $id ? Appointments::find($id) : new Appointments;
            if ($id && !$appointment) return $this->error("No appointment with this id $id", 404);
            $appointment->contacts_id=$appointmentRequest->contacts_id;
            $appointment->date = $appointmentRequest->date;
            $appointment->leaving_the_office = $appointmentRequest->leaving_the_office;
            $appointment->arrive_office = $appointmentRequest->arrive_office;
            $appointment->address=$appointmentRequest->address;
            $appointment->distance=$appointmentRequest->distance;
            $appointment->save();
            // $queries = DB::getQueryLog();
            DB::commit();
            return $this->success($id?"Appointment Updated with id $id":"Appointment created", $appointment);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error("An error occurred ".$e->getMessage(), 500);
        }
    }

    public function checkTwoDate($dates)
    {
        if(isNull($dates->except)){
            $checkFrom=Appointments::whereBetween("leaving_the_office",[$dates->leave,$dates->arrive])->get();
            $checkTo=Appointments::whereBetween("arrive_office",[$dates->leave,$dates->arrive])->get();
            if(count($checkFrom)>0 || count($checkTo)>0)
            return false;
            return true;
        }else{
            $checkFrom=Appointments::whereBetween("leaving_the_office",[$dates->leave,$dates->arrive])->where("id","!=",$dates->except)->get();
            $checkTo=Appointments::whereBetween("arrive_office",[$dates->leave,$dates->arrive])->where("id","!=",$dates->except)->get();
            if(count($checkFrom)>0 || count($checkTo)>0)
            return false;
            return true;
        }
    }
}
