<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentType;

/**
 * Display data on Index
 * 
 */
class DashBoardController extends Controller
{
    protected $documentQunatity ;

    protected $documentQuantityEachCabinet ;

    protected $documentType;

    public function index() {
        $user = auth()->user();
        $local_cabinets_object = $user->cabinetPermissions()->withCount('documents')->get();
        $local_cabinets = $user->cabinetPermissions;
        $local_cabinets_count = $local_cabinets_object->pluck(['documents_count']);

        $document_type_list = DocumentType::orderBy('id')->get()->pluck(['name']);
        $document_type_query = $document_type_count = DocumentType::with(['documents'=>function($query) use($user) {
                $query->where('school_id', $user->school_id);
            }])->get();
        $document_type_count = collect();
        foreach ($document_type_query as $type) {
            $document_type_count->push($type->documents->count());
        }
        
        $documents = $user->accessibleDocuments()
            ->orderBy('created_at','desc')->take(5)->get();

        return view('dashboard', compact([
            'documents',
            'local_cabinets',
            'local_cabinets_count',
            'document_type_list',
            'document_type_count',
            'local_cabinets_object'

        ]));
    }

}
