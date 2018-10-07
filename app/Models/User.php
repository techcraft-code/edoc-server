<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'school_id', 'email', 'first_name', 'last_name', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * append to response json
     * 
     * @var array
     */
    protected $appends = [
        'full_name'
    ];

    /**
     * @return App\Models\Role
     */
    public function role() {
        return $this->belongsTo(Role::class);
    }

     /**
     * @return App\Models\School
     */
    public function school() {
        return $this->belongsTo(School::class);
    }


    /**
     * combine firstname and last name
     * 
     * @return string $fullname
     */
    public function getFullNameAttribute() {
        return $this->attributes['first_name']." ".$this->attributes['last_name']; 
    }


    /**
     * belong to many Cabinet 
     * 
     * @return 
     */
    public function cabinetPermissions() {
        return $this->belongsToMany(Cabinet::class, 'user_cabinet_permission', 'user_id', 'cabinet_id');
    }

    public function scopeOfSearchByName($query, $text) {
        return $query->where("first_name", "like", "%{$text}%")
            ->orWhere("last_name", "like", "%{$text}%");
    }

    // public function documentAssigns() {
    //     return $this->belongsToMany(Document::class, 'documents_users', 'user_id', 'document_id');
    //         // ->withPivot(["status"]);
    // }

    // public function accessibleCabinets() {
    //     return $this->belongsTomany(Cabinet::class, 'user_cabinet_permission', 'user_id', 'cabinet_id');
    //     // return date("d/m/Y")
    //     // return date("d/m/Y", strtotime("{$this->attributes['date']} +543 years")) ;
    // }

    public function getLocalCabinets() {
        return Cabinet::where('school_id', $this->school_id)->get();
    }

    public function accessibleDocuments() {
        return $this->belongsToMany(Document::class, 'documents_users', 'user_id', 'document_id')
            ->withPivot(["document_user_status", "is_read"]);
    }

    // document assigment query
    public function assignmentAlert($document_id) {
        $query = $this->accessibleDocuments()
            ->wherePivot('document_id', $document_id)
            ->wherePivot('document_user_status', 1);
        return $query;
    }   

    public function getAssignedDocumentsAttribute() {
        return $this->accessibleDocuments->pluck(['id'])->toArray();
    }

    // public function acceptDocument($document_id) {
    //     $this->
    //     // $document = Document::find($document_id);
        
    // }
    
}
