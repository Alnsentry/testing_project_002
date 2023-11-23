<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Book;

use Validator;
use DB;
use Session;

class BookController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:buku-read|buku-create|buku-edit|buku-delete', ['only' => ['index','show']]);
        $this->middleware('permission:buku-create', ['only' => ['create','store']]);
        $this->middleware('permission:buku-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:buku-delete', ['only' => ['destroy']]);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $books = Book::orderBy('created_at','asc')->get();
        $data = compact('books');
        return view('books.books.index')->with($data);
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
        return view('books.books.create')->with($data);
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
            'name' => 'required',
            'author' => 'required',
            'publish_year' => 'required',
            'stock' => 'required'
        ];
        
        $validate = Validator::make($request->all(),$valid);
        if($validate->fails()){
            return back()
                ->withErrors($validate)
                ->withInput();
        }else{
            DB::beginTransaction();
            try{
                $book = new Book();
                $book->name = $request['name'];
                $book->author = $request['author'];
                $book->publish_year = $request['publish_year'];
                $book->stock = $request['stock'];

                $book->save();
            }catch(Exception $e){
                DB::rollback();
                Session::flash('error', 'Failed create book because system error');
                return back();
            }
            DB::commit();
            Session::flash('success', 'Success create book');
            return redirect()->route('books.index');
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Group  $group
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        
        $data = compact('book');
        return view('books.books.edit')->with($data);
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
            'name' => 'required',
            'author' => 'required',
            'publish_year' => 'required',
            'stock' => 'required'
        ];
        
        $validate = Validator::make($request->all(),$valid);
        if($validate->fails()){
            return back()
                ->withErrors($validate)
                ->withInput();
        }else{
            DB::beginTransaction();
            try{
                $book = Book::find($id);
                $book->name = $request['name'];
                $book->author = $request['author'];
                $book->publish_year = $request['publish_year'];
                $book->stock = $request['stock'];

                $book->update();
            }catch(Exception $e){
                DB::rollback();
                Session::flash('error', 'Failed update book because system error');
                return back();
            }
            DB::commit();
            Session::flash('success', 'Success update book');
            return redirect()->route('books.index');
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
        $data = Book::find($id);
        $data->delete();

        Session::flash('success', 'Success delete book'.$data->name);
        return redirect()->route('books.index');
    }
}
