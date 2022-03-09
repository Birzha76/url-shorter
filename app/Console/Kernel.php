<?php

namespace App\Console;

use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            $usersRevenueInfo = [];
            $users = User::where('is_admin', 0)->with('links', 'links.leads')->get()->toArray();

            foreach ($users as $user) {
                $usersRevenueInfo[$user['name']] = [
                    'revenue' => 0,
                    'percent' => $user['percent'],
                ];
            }

            $leads = Lead::whereBetween('created_at', [
                Carbon::now()->subDays(7)->startOfWeek(),
                Carbon::now()->subDays(7)->endOfWeek()
            ])
                ->with('link', 'link.user')
                ->get();

            foreach ($leads as $lead) {
                if (!empty($lead->link->user)) {
                    $userName = $lead->link->user->name;
                    $usersRevenueInfo[$userName]['revenue'] += $lead->revenue / 100 * $usersRevenueInfo[$userName]['percent'];
                }
            }

            $msg = 'Статистика по пользователям за неделю: ' . PHP_EOL . PHP_EOL;

            foreach ($usersRevenueInfo as $name => $info) {
                $msg .= '<b>' . $name . '</b>: ' . $info['revenue'] . ' $' . PHP_EOL;
            }

            file_get_contents('https://api.telegram.org/bot5043913618:AAFFkBpRrxEzJ1HXUEb3XJ65HBK7DN-5_bM/sendMessage?chat_id=802265944&parse_mode=html&text=' . urlencode($msg));
        })->weeklyOn(1, '00:01');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
