<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentReplyType extends Model
{
    protected $table = 'document_reply_types';

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
