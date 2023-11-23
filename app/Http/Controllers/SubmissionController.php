<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Submission;
use App\Models\SubmissionHistorie;
use App\Models\Book;

use Validator;
use DB;
use Session;
use Auth;

class SubmissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:pengajuan-read|pengajuan-create|pengajuan-edit|pengajuan-delete', ['only' => ['index','show']]);
        $this->middleware('permission:pengajuan-create|pengajuan-pinjaman', ['only' => ['create','store']]);
        $this->middleware('permission:pengajuan-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:pengajuan-delete', ['only' => ['destroy']]);
        $this->middleware('permission:pengajuan-pinjaman', ['only' => ['borrowingBook']]);
        $this->middleware('permission:daftar-peminjaman', ['only' => ['borrowing','update']]);
        $this->middleware('permission:histori-pengajuan', ['only' => ['history']]);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $submissions = Submission::orderBy('created_at','asc')->get();
        $data = compact('submissions');
        return view('books.submission.index')->with($data);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $books = Book::orderBy('created_at', 'DESC')->get();

        $data = compact('books');
        return view('books.submission.create')->with($data);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $valid = [
            'book_id' => 'required',
            'return_date' => 'required',
            'number_stock' => 'required'
        ];
        
        $validate = Validator::make($request->all(),$valid);
        if($validate->fails()){
            return back()
                ->withErrors($validate)
                ->withInput();
        }else{
            DB::beginTransaction();
            try{
                $book = Book::find($request['book_id']);
                if($book->stock - $request['number_stock'] >= 0) {
                    $book->stock = $book->stock - $request['number_stock'];
                    $book->update();

                    $submission = new Submission();
                    $submission->user_id = Auth::user()->id;
                    $submission->book_id = $request['book_id'];
                    $submission->return_date = $request['return_date'];
                    $submission->number_stock = $request['number_stock'];

                    $submission->save();
                } else {
                    DB::rollback();
                    Session::flash('error', 'Stock is enough');
                    return back();   
                }
            }catch(Exception $e){
                DB::rollback();
                Session::flash('error', 'Failed create submission because system error');
                return back();
            }
            DB::commit();
            Session::flash('success', 'Success create submission');
            return redirect()->back();
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\contact  $group
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $valid = [
        ];
        
        $validate = Validator::make($request->all(),$valid);
        if($validate->fails()){
            return back()
                ->withErrors($validate)
                ->withInput();
        }else{
            DB::beginTransaction();
            try{
                $submission = Submission::find($id);
                if(is_null($request['approved'])) {
                    if($request['return'] === 'kembali') {
                        $submission->return = true;
                    } else {
                        $submission->return = false;
                    }
                } else {
                    if($request['approved'] === 'terima') {
                        $submission->approved = true;
                    } else {
                        $submission->approved = false;
                    }
                }

                $submission->update();

                if(!is_null($request['approved'])) {
                    $history = new SubmissionHistorie();
                    $history->user_id = Auth::user()->id;
                    $history->submission_id = $submission->id;
                    if($request['approved'] === 'terima') {
                        $history->approved = true;
                    } else {
                        $history->approved = false;
                    }

                    $history->save();
                }
            }catch(Exception $e){
                DB::rollback();
                Session::flash('error', 'Failed update submission because system error');
                return back();
            }
            DB::commit();
            Session::flash('success', 'Success update submission');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Submission::find($id);
        $data->delete();

        Session::flash('success', 'Success delete submission'.$data->name);
        return redirect()->route('submissions.index');
    }

    public function history() {
        $histories = SubmissionHistorie::orderBy('created_at','asc')->get();
        $data = compact('histories');
        return view('books.history.index')->with($data);
    }

    public function borrowing() {
        $submissions = Submission::where('approved', true)->orderBy('created_at','asc')->get();
        $data = compact('submissions');
        return view('books.borrowing.index')->with($data);
    }

    public function borrowingBook()
    {
        $submissions = Submission::where('user_id', Auth::user()->id)->orderBy('created_at','asc')->get();
        $data = compact('submissions');
        return view('books.borrowing-books.index')->with($data);
    }
}
