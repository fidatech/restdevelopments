<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSettingTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['locale','company_intro','address'];
}
