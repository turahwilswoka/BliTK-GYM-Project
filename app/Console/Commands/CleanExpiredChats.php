<?php

namespace App\Console\Commands;

use App\Models\ChatSession;
use Illuminate\Console\Command;

class CleanExpiredChats extends Command
{
    protected $signature   = 'chat:clean-expired';
    protected $description = 'Hapus chat sessions dan messages yang sudah expired (> 60 hari)';

    public function handle(): int
    {
        $deleted = ChatSession::where('expires_at', '<', now())->delete();
        $this->info("Deleted {$deleted} expired chat session(s).");
        return Command::SUCCESS;
    }
}
