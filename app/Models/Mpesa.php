<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpesa extends Model
{
    use HasFactory;
    protected $fillable = [
        'TransactionType',
        'Student_id',
        'TransAmount',
        'MpesaReceiptNumber',
        'TransactionDate',
        'PhoneNumber',
        'response',
    ];
}
