<?php

namespace App\Console\Commands;

use App\Mail\WarningMail;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWarningNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:warning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify a reader on impending check in on a book';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::today()->addDays(2);
        $bookings = Booking::with(['user'])->whereDate('expected_checkin_date', '=', $date)->get();
        foreach ($bookings as $booking) {
            $data['user'] = $booking->user->name;
            $data['title'] = $booking->book->title;
            $data['date'] = Carbon::parse($booking->expected_checkin_date)->format('d-m-Y');

            $name = $booking->user->name;
            Mail::to($booking->user->email)->send(new WarningMail($data));
            Log::info("mail sent to user $name successfully");
        }
        return Command::SUCCESS;
    }
}
