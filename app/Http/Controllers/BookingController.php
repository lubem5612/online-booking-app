<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Booking;
use App\Traits\ManagesResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    use ManagesResponse;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        try {
            $builder = Booking::with(['book', 'user'])->orderBy('created_at', 'desc');

            $queryParam = $request->query('search');
            if (isset($queryParam)) {
                $search = $request->query('search');
                $builder->where(function ($query) use ($search) {
                    $query->orWhereHas('user', function ($query2) use ($search) {
                        $query2->where('name', 'LIKE', '%'.$search.'%');
                    })
                        ->orWhereHas('book', function ($query3) use ($search) {
                            $query3->where('title', 'LIKE', '%'.$search.'%')
                                ->orWhere('isbn', 'LIKE', '%'.$search.'%')
                                ->orWhere('publisher', 'LIKE', '%'.$search.'%');
                        });
                });
            }
            $volume = $request->query('limit');
            $limit = isset($volume)? $volume : 10;
            $result = $builder->paginate($limit);
            return $this->sendResponse($result, 'bookings retrieved successfully');
        }catch (\Exception $exception) {
            return $this->sendError('error in retrieving bookings', $exception->getTrace(), 500);
        }
    }

    public function show($id)
    {
        try{
            $book = Booking::with(['user', 'book'])->find($id);
            return $this->sendResponse($book, 'booking retrieved successfully');
        }catch (\Exception $exception) {
            return $this->sendError('error in retrieving booking', $exception->getTrace(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required',
            'user_id' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError('validation errors', $validator->errors(), 422);
        }
        try {
            $booking = Booking::create([
                'book_id' => $request->get('book_id'),
                'user_id' => $request->get('user_id'),
                'expected_checkin_date' => Carbon::now()->addDays(10),
            ]);
            if (empty($booking)) {
                return $this->sendError('error in creating booking', [], 400);
            }
            $book = Book::find($request->get('book_id'));
            $book->status = 'checked_out';
            $book->save();

            return $this->sendResponse($booking, 'booking created successfully');
        }catch (\Exception $exception) {
            return $this->sendError('fatal error in saving booking', $exception->getTrace(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'checkin_date' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError('validation errors', $validator->errors(), 422);
        }
        try {
            $book = Book::find($id);
            $booking = Booking::where('book_id', $id)->first();
            //return Carbon::parse($request->get('checkin_date'));
            $booking->actual_checkin_date = Carbon::parse($request->get('checkin_date'));
            $booking->save();

            $book->status = 'checked_in';
            $book->save();

            return $this->sendResponse($booking, 'booking checked in successfully');
        }catch (\Exception $exception) {
            return $this->sendError('fatal error in checking in book', $exception->getTrace(), 500);
        }
    }

}
