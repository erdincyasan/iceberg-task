<?php
namespace App\Interfaces;

use App\Http\Requests\DateRequest;
interface ContactsInterface{

    //gettin all contacts
    public function getAllContacts();
    //getContact by id
    public function getContactById($id);
    //create or update contact
    public function contactRequest($contactInformation,$id=null);
    //delete contact by id
    public function deleteContactById($id);

}