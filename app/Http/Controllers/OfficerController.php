<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Auth;
use FastExcel;
use Storage ;

class OfficerController extends Controller
{

    public function index(){
        $user = Auth::user() ;
        $officers = User::where("school_id", $user->school_id)->get();
        return view('officers.index', compact([
            'officers'
        ]));
    }

    public function edit($id) {
        $officer = User::findOrFail($id);
        return view('officers.index', compact(
            'officer'
        ));
    }

    /**
     * Import User In school by use CSV file
     */
    public function import(Request $request) {
        $school_id = Auth::user()->school_id;
        $store_path = $request->file('import_file')->storeAs("tmp", $request->file('import_file')->getClientOriginalName());
        $duplicate = [];

        $col = (new FastExcel)->import(storage_path("app/$store_path"), function($line) use($school_id, &$duplicate){
            if (User::where('user_id', $line['id'])->count() ==0 ) {
                return User::create([
                    'user_id' => $line['id'],
                    'password' => bcrypt($line['id']),
                    'role_id' => 2,
                    'school_id' => $school_id,
                    'first_name' => $line['first_name'],
                    'last_name' => $line['last_name'],
                    'email' => $line['email'],
                ]);
            } else {
                $line['user_id'] = $line['id'];
                unset($line['id']);
                return User::where('user_id', $line['user_id'])->update(
                    $line
                );
            }

        });
        return redirect()->back();
    }

    public function store(Request $request) {
        $addition = [
            'password' => bcrypt($request->user_id),
            'role_id' => 2
        ];
        User::create( array_merge($request->all(), $addition));
        return redirect()->back();
    }

    public function password_reset(Request $request) {
        $new_password = [
            'password' => bcrypt($request->user_id)
        ];
        $user = User::where('user_id', $request->user_id)->update($new_password);

        return redirect()->back()->withSuccess('ทำรายการสำเร็จ');
    }

    public function destroy($id){
        User::findOrFail($id)->delete();
        return redirect()->back();
    }
}
