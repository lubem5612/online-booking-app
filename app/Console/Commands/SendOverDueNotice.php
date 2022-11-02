<?php

namespace App\Console\Commands;

use App\Mail\OverdueMail;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOverDueNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to librarian when reader defaults book check in';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $admin = User::where('role', 'librarian')->first();
        $date = Carbon::today();
        $defaults = Booking::whereDate('expected_checkin_date', '>', $date)->get();
        foreach ($defaults as $default) {
            $data['admin'] = $admin->name;
            $data['name'] = $default->user->name;
            $data['email'] = $default->user->email;
            $data['date'] = Carbon::parse($default->expected_checkin_date)->format('d-m-Y');

            Mail::to($admin->email)->send(new OverdueMail($data));
        }
        Log::info("mail sent to librarian successfully");
        return Command::SUCCESS;
    }
}
