<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:expire-subscription')]
#[Description('Command description')]
class ExpireSubscription extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredSubs = Subscription::where('status', 'active')
            ->where('current_period_end', '<', now())
            ->get();

        foreach ($expiredSubs as $sub) {

            $sub->update([
                'status' => 'expired',
            ]);

            $user = User::with('subscription.plan', 'store')->find($sub->user_id);

            if ($user) {
                $user->deactivateAllData();
            }
        }
    }
}
