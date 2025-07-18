<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptTherapy extends Model
{
    use HasFactory;

    protected $table = 'receipt_therapies';

    protected $fillable = [
        'receipt_id',
        'therapy_id',
        'therapist_id',
        'original_qty',
        'price',
        'time_in',
        'time_out',
        'date',
        'discount',
        'total',
    ];

    // Relationship with Receipt
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    // Relationship with Therapy
    public function therapy()
    {
        return $this->belongsTo(Therapy::class);
    }

    public function therapist()
    {
        return $this->belongsTo(Therapist::class);
    }

    public function markAsUsed($timeIn, $timeOut, $usageDate)
    {
        // Update the time_in and time_out fields
        $this->update([
            'time_in' => $timeIn,
            'time_out' => $timeOut,
            'date' => $usageDate, // Set the date of usage
        ]);
    }
    /**
     * Check if therapy usage is already recorded
     */
    public function isUsed()
    {
        return !is_null($this->time_in) && !is_null($this->time_out) && !is_null($this->date) ;
    }
}
