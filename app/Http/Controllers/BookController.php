<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookAuthor;
use App\Models\Booking;
use App\Models\Genre;
use App\Traits\ManagesResponse;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    use ManagesResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        try {
            $date =$request->query('date');
            $searchItem = $request->query('search');
            $status = $request->query('status');

            $builder = Book::with(['authors', 'genre'])->orderBy('created_at', 'desc');
            if(isset($date)) {
                $search_date = Carbon::parse($date);
                $builder = $builder->whereDate('created_at', $search_date);
            }
            if (isset($status)) {
                $builder = $builder->where('status', $status);
            }
            if(isset($searchItem)) {
                $search = $request->query('search');
                $builder->where(function ($query) use ($search) {
                    $query->where('title', 'LIKE', '%'.$search.'%')
                        ->orWhere('isbn', 'LIKE', '%'.$search.'%')
                        ->orWhere('publisher', 'LIKE', '%'.$search.'%');
                });
            }
            $volume = $request->query('limit');
            $limit = isset($volume)? $volume : 10;
            $result = $builder->paginate($limit);
            return $this->sendResponse($result, 'books retrieved successfully');
        }catch (\Exception $exception) {
            return $this->sendError('error in retrieving books', $exception->getTrace(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|required',
            'cover' => 'required|file|max:3000|mimes:png,jpg,gif,jpeg,bmp',
            'isbn' => 'required',
            'publisher' => 'required',
            'revision_no' => 'required',
            'genre_id' => 'required',
            'authors' => 'required',
            'published_date' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError('validation errors', $validator->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $inputs = $request->except(['cover', 'authors']);
            if ($request->hasFile('cover')) {
                $image = $request->file('cover');
                $uniqueFileName = uniqid().'-cover_photo.'.$image->getClientOriginalExtension();

                //in reality, a cloud storage will be used
                $is_uploaded = $image->move(public_path('/assets/cover-photos'), $uniqueFileName);
                if (!$is_uploaded) {
                    return $this->sendError('failed to upload cover image', [], 400);
                }
                $inputs['cover_url']='/assets/cover-photos/'.$uniqueFileName;
            }
            //create book
            $book = Book::create($inputs);
            if(empty($book)) {
                return $this->sendError('failed in creating book', [], 400);
            }
            //create author(s)
            $authors = explode(',', $request->get('authors'));
            foreach ($authors as $author) {
                $isNotAuthor = Author::where('name', $author)->doesntExist();
                if ($isNotAuthor) {
                    $_author = Author::create(['name' => $author]);
                    BookAuthor::Create([
                        'author_id' => $_author->id,
                        'book_id' => $book->id
                    ]);
                }
            }
            DB::commit();

            return $this->sendResponse($book->with(['authors', 'genre'])->first(), 'book created successfully');
        }catch (\Exception $exception) {
            return $this->sendError('error in creating books', $exception->getTrace(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string',
            'cover' => 'sometimes|file|max:3000|mimes:png,jpg,gif,jpeg,bmp',
            'isbn' => 'sometimes|string',
            'publisher' => 'sometimes|string|max:70',
            'revision_no' => 'sometimes',
            'genre_id' => 'sometimes|string',
        ]);
        if($validator->fails()) {
            return $this->sendError('validation errors', $validator->errors(), 422);
        }
        try {
            DB::beginTransaction();
            $book = Book::find($id);
            if(empty($book)) {
                return $this->sendError('book not found', [], 404);
            }
            $inputs = $request->except(['cover', 'authors']);
            if ($request->hasFile('cover')) {
                //delete existing cover image
                File::delete(public_path().$book->cover_url);

                // upload new image
                $image = $request->file('cover');
                $uniqueFileName = uniqid().'-cover_photo.'.$image->getClientOriginalExtension();
                //in reality, a cloud storage will be used
                $is_uploaded = $image->move(public_path('/assets/cover-photos'), $uniqueFileName);
                if (!$is_uploaded) {
                    return $this->sendError('failed to upload cover image', [], 400);
                }
                $inputs['cover_url']='/assets/cover-photos/'.$uniqueFileName;
            }

            if ($request->get('authors')) {
                //delete previous authors and associated tables
                $authors_ids = BookAuthor::where('book_id', $book->id)->pluck('author_id');
                Author::whereIn('id', $authors_ids)->delete();
                BookAuthor::where('book_id', $book->id)->delete();

                $authors = explode(',', $request->get('authors'));

                foreach ($authors as $author) {
                    $isNotAuthor = Author::where('name', $author)->doesntExist();
                    if ($isNotAuthor) {
                        $_author = Author::create(['name' => $author]);
                        BookAuthor::Create([
                            'author_id' => $_author->id,
                            'book_id' => $book->id
                        ]);
                    }
                }
            }
            $book->fill($inputs)->save();

            DB::commit();

            return $this->sendResponse($book->with(['authors', 'genre'])->first(), 'book updated successfully');
        }catch (\Exception $exception) {
            return $this->sendError('error in updating books', $exception->getTrace(), 500);
        }
    }

    public function show($id)
    {
        try{
            $book = Book::with(['genre', 'authors'])->find($id);
            $book['booking']= Booking::with(['user'])->where('book_id', $id)->latest()->first();

            return $this->sendResponse($book, 'book retrieved successfully');
        }catch (\Exception $exception) {
            return $this->sendError('error in retrieving book', $exception->getTrace(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $book = Book::find($id);
            File::delete(public_path().$book->cover_url);
            Book::destroy($id);
            return $this->sendResponse(null, 'book deleted successfully');
        }catch (\Exception $exception) {
            return $this->sendError('error in deleting book', $exception->getTrace(), 500);
        }
    }

    public function genres()
    {
        try {
           $genres = Genre::all();
           return $this->sendResponse($genres, 'genres retrieved successfully');
        }catch (\Exception $exception) {
            return $this->sendError('error in getting book genres', $exception->getTrace(), 500);
        }
    }
}
