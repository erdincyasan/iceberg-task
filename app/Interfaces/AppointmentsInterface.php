<?php

namespace App\Interfaces;

interface AppointmentsInterface{

    public function getAllAppointments();
    public function getAppointmentById($id);
    public function appointmentRequest($appointmentRequest,$id=null);
    public function deleteAppointmentsById($id);
    public function checkTwoDate($dates);
}