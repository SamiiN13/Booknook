<?php

namespace App\Console\Commands;

use App\Models\BookRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReturnReminders extends Command
{
    protected $signature = 'books:send-return-reminders';
    protected $description = 'Send reminders for books that are due for return';

    public function handle()
    {
        $overdueRequests = BookRequest::where('status', 'approved')
            ->where('return_date', '<', now())
            ->whereNull('actual_return_date')
            ->with(['book', 'requester', 'owner'])
            ->get();

        $this->info("Found {$overdueRequests->count()} overdue book requests.");

        foreach ($overdueRequests as $request) {
            // Send reminder to borrower
            $this->info("Sending reminder to {$request->requester->name} for book: {$request->book->title}");
            
            // In a real application, you would send an email here
            // Mail::to($request->requester->email)->send(new ReturnReminder($request));
            
            // For now, we'll just log it
            \Log::info("Return reminder sent to {$request->requester->email} for book: {$request->book->title}");
        }

        $this->info('Return reminders sent successfully!');
    }
}
