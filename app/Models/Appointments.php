<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;
class Appointments extends Model
{
    use HasFactory;
    use FilterQueryString;

    protected $filters=["sort"];
    protected $primaryKey ="id";
    protected $fillable=[
        "address",
        "date",
        "leaving_the_office",
        "arrive_office",
        "contacts_id"
    ];
    public function contact(){
        return $this->belongsTo(Contacts::class,"contacts_id");
    }
}
