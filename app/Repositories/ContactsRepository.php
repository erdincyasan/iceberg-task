<?php

namespace App\Repositories;

use App\Models\Contacts;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ContactsInterface;

class ContactsRepository implements ContactsInterface
{
    use ResponseAPI;
    public function getContactById($id)
    {
        try {
            $contact = Contacts::find($id);
            if ($contact) {
                return $this->success("Contact by id", $contact);
            } else {
                return $this->error("No contact with id $id", 404);
            }
        } catch (Exception $e) {
            return $this->error("An internal server error occurred", 500);
        }
    }

    public function getAllContacts()
    {
        try {
            $contacts = Contacts::all();
            return $this->success("All Contacts", $contacts);
        } catch (Exception $e) {
            return $this->error("An internal server error occurred", 500);
        }
    }

    public function contactRequest($contactInformation, $id = null)
    {
        // DB::beginTransaction();
        try {
            $contact = $id ? Contacts::find($id) : new Contacts;

            if ($id && !$contact) return $this->error("No contact with this id $id", 404);
            $hasEmail=Contacts::where('email',$contactInformation['email'])->first();
            if($hasEmail){
                return $hasEmail->id;
            }
            $contact->name = $contactInformation["name"];
            $contact->surname = $contactInformation["surname"];
            $contact->phone = $contactInformation["phone"];
            $contact->email = $contactInformation["email"];
            $contact->save();
            // DB::commit();
            return $contact->id;
        } catch (Exception $e) {
            // DB::rollBack();
            return $e->getMessage();
        }
    }

    public function deleteContactById($id)
    {
        DB::beginTransaction();
        try {
            $getContact = Contacts::find($id);
            if (!$getContact) {
                return $this->error("No contact with this id $id", 404);
            }
            $getContact->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $this->error("An error occurred", 500);
        }
    }
}
