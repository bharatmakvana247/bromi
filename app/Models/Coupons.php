<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupons extends Model
{
    use HasFactory;
use SoftDeletes;

	protected $table = 'coupons';

	protected $fillable = [
		'name',
		'code',
		'amount_off',
		'status',
	];
}
