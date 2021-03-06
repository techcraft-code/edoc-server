<?php

namespace App\Http\Controllers\Traits ;

use App\Models\Document ;

use Illuminate\Http\Request;

trait DocumentRespondTrait {
    

    public function respond($id, Request $request) {
        // if not approve able 
        $documentModel = Document::findOrFail($id);
        $user = auth()->user();
        if( $documentModel->approvAbleByUser($user->id) ) {             // ตรวจสอบว่า อนุมัติ ได้ไหม
            return $this->approve($documentModel, $request);
        } elseif ( $documentModel->acceptAbleByUser($user->id) ) {      // ตรวจสอบว่า รับทราบ ได้ไหม
            return $this->accept($documentModel, $request);
        } else {
            abort(404);
        }
    }

    /**
     * Approve Document
     */
    public function approve(Document $documentModel, Request $request) {
        $user = auth()->user();
        $status = $request->is_approve == 1 ? 3 : 4;
        $documentModel->update(['status' => $status]);
        if (!is_null($request->comment)) {
            $documentModel->comments()->create([
                'author_id' => $user->id,
                'comment' => $request->comment
            ]);
        }
        $user->accessibleDocuments()->updateExistingPivot($documentModel->id,[
            'is_read' => 1 
         ]);
        return redirect()->route('document.show', $documentModel->id);
    }

    /** 
     * Accept Document  
     */    
    public function accept(Document $documentModel, Request $request) {
        $user = auth()->user();
        $user->accessibleDocuments()->updateExistingPivot($documentModel->id,[
           'is_read' => 1 
        ]);
        if (!is_null($request->comment)) {
            $documentModel->comments()->create([
                'author_id' => $user->id,
                'comment' => $request->comment
            ]);
        }
        if ( $documentModel->accessibleUsers()->wherePivot('is_read', 0)->count() == 0 ) {
            $documentModel->update([
                'status' => 3 
            ]);
        }
        return redirect()->route('document.show', $documentModel->id);
    }
    
}