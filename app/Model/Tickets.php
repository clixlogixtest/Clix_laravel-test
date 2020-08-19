<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Tickets extends Model
{
	use Notifiable;

    protected $primaryKey = 'ticket_id';
}
