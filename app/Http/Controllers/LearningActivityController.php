<?php

namespace App\Http\Controllers;

use App\Models\ActivityMonth;
use App\Models\LearningActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\Datatables;

class LearningActivityController extends Controller
{
    public function index(){
        $learningActivities = LearningActivity::with('activityMonths')->get();
        return view('activitylearning', compact('learningActivities'));
    }

    public function getLearningActivities(Request $request){
        $learningActivities = LearningActivity::with('activityMonths')->get();
        
        return DataTables::of($learningActivities)->make(true);
    }


    public function getCategoryMethods(){
        $categoryMethods = LearningActivity::distinct()->select('id', 'learning_method')->get();

        return response()->json($categoryMethods);
    }

    public function deleteActivitiesByLearningMethod($id) {
        try {
            DB::beginTransaction();
    
            // Hapus semua ActivityMonth dengan learning_activity_id yang sesuai
            $learningActivityId = $id;
    
            ActivityMonth::where('learning_activity_id', $learningActivityId)->delete();
    
            LearningActivity::where('id', $learningActivityId)->delete();
    
            DB::commit();
    
            return response()->json(['message' => 'ActivityMonths deleted successfully']);
        } catch (\Exception $e) {
            DB::rollback();
    
            return response()->json(['message' => 'ActivityMonths failed to delete']);
        }
    }


    public function get_learning_method(){
        $learningMethods = LearningActivity::all();
        return view('methodlearning', compact('learningMethods'));
        // dd($learningMethods);
    }

    public function get_learning_method_datatable(){
        $learningMethods = LearningActivity::all();
        return response()->json(['data' => $learningMethods]);
        // dd($learningMethods);
    }


    public function store(Request $request){
        // Validasi data yang diterima dari formulir
        $request->validate([
            'categoryMethod' => 'required',
            'activityName' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ]);

        // Cek apakah kategori method sudah ada
        $learningActivity = LearningActivity::find($request->categoryMethod);

        if (!$learningActivity) {
            // Kategori method tidak ditemukan, berikan respons error
            return response()->json(['message' => 'Kategori method tidak valid'], 400);
        }

        // Lanjutkan dengan menyimpan data aktivitas ke dalam tabel activity_months
        $startDate = Carbon::parse($request->startDate);
        $monthName = $startDate->format('F');

        $activityMonth = ActivityMonth::create([
            'learning_activity_id' => $request->categoryMethod,
            'activities' => $request->activityName,
            'month' => $monthName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
        ]);

        if ($activityMonth) {
            // Berhasil disimpan
            return response()->json(['message' => 'Data aktivitas berhasil disimpan'], 200);
        } else {
            // Gagal menyimpan
            return response()->json(['message' => 'Data aktivitas gagal disimpan'], 500);
        }
    }

    public function store_learning_method(Request $request){
        // Validasi data yang diterima dari formulir
        $request->validate([
            'metodeName' => 'required',
        ]);

        $learningActivity = LearningActivity::create([
            'learning_method' => $request->metodeName,
        ]);

        if ($learningActivity) {
            // Berhasil disimpan
            return response()->json(['message' => 'Data metode pembelajaran berhasil disimpan'], 200);
        } else {
            // Gagal menyimpan
            return response()->json(['message' => 'Data metode pembelajaran gagal disimpan'], 500);
        }
    }

    public function get_learning_method_edit($id){
        $learningActivity = LearningActivity::find($id);
        return response()->json($learningActivity);
    }

    public function edit_learning_method($id, Request $request){
        // update
        $learningActivity = LearningActivity::find($id);

        // update LearningActivity
        $update = $learningActivity->update([
            'learning_method' => $request->metodeName,
        ]);

        if ($update) {
            // Berhasil disimpan
            return response()->json(['message' => 'Data metode pembelajaran berhasil diupdate'], 200);
        } else {
            // Gagal menyimpan
            return response()->json(['message' => 'Data metode pembelajaran gagal diupdate'], 500);
        }
    }

    public function delete_learning_method($id){
        $learningActivity = LearningActivity::find($id);
        $delete = $learningActivity->delete();

        if ($delete) {
            // Berhasil disimpan
            return response()->json(['message' => 'Data metode pembelajaran berhasil dihapus'], 200);
        } else {
            // Gagal menyimpan
            return response()->json(['message' => 'Data metode pembelajaran gagal dihapus'], 500);
        }
    }



}
