<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StatsController extends Controller
{
    public function index()
    {
        $linksPerDay = Visit::select('id', 'link_id', 'created_at')
            ->with(['link', 'link.domain'])
            ->whereHas('link.user', function (Builder $query) {
                $query->where('id', Auth::user()->id);
            })
            ->orderBy('created_at', 'DESC')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('d.m.Y');
            })
            ->toArray();

        $linksWithVisits = [];
        foreach ($linksPerDay as $day => $arrayOfLinks) {
            foreach ($arrayOfLinks as $link) {
                if (!isset($linksWithVisits[$day][$link['link_id']])) {
                    $lastVisit = Visit::where('link_id', $link['link_id'])
                        ->orderBy('created_at', 'DESC')
                        ->first();
                    $lastVisitTime = Carbon::parse($lastVisit->created_at)->format('d.m H:i');
                    $linksWithVisits[$day][$link['link_id']] = [
                        'link_short' => $link['link']['domain']['domain'] . '/' . $link['link']['link_short'],
                        'link_full' => $link['link']['link_full'],
                        'last_visit' => $lastVisitTime,
                        'tiktok' => $link['link']['tiktok'],
                        'country' => $link['link']['country'],
                        'count' => 1,
                    ];
                }else {
                    $linksWithVisits[$day][$link['link_id']]['count']++;
                }
            }
        }

        $linksInfo = [];
        foreach ($linksWithVisits as $day => $links) {
            $countAll = 0;
            $visitLast = null;
            $leadsCount = 0;
            $leadsSum = 0;
            foreach ($links as $id => $link) {
                $revenueSum = 0;
                $dateFormat = explode('.', $day);
                $linkLeads = Lead::where('link_id', $id)
                    ->whereDay('created_at', $dateFormat[0])
                    ->whereMonth('created_at', $dateFormat[1])
                    ->whereYear('created_at', $dateFormat[2])
                    ->get();
                $countLinkLeads = $linkLeads->count();

                foreach ($linkLeads as $lead) {
                    $revenueSum += $lead->revenue;
                }

                $linksWithVisits[$day][$id]['leads_count'] = $countLinkLeads;
                $leadsCount += $countLinkLeads;

                $linksWithVisits[$day][$id]['leads_sum'] = $revenueSum;
                $leadsSum += $revenueSum;

                $countAll += $link['count'];
                if ($visitLast == null) {
                    $visitLast = $link['last_visit'];
                }elseif ($visitLast > $link['last_visit']) {
                    $visitLast = $link['last_visit'];
                }
            }
            $linksInfo[$day] = [
                'count' => $countAll,
                'last_visit' => $visitLast,
                'leads_count' => $leadsCount,
                'leads_sum' => $leadsSum,
            ];
        }

//        dd($linksPerDay, $linksWithVisits, $linksInfo);

        return view('admin.stats.index', compact('linksWithVisits', 'linksInfo'));
    }

    public function userWeek()
    {
        $searchUserId = Auth::user()->id;

        $linksPerDay = Visit::select('id', 'link_id', 'created_at')
            ->with(['link', 'link.domain'])
            ->whereHas('link.user', function (Builder $query) use ($searchUserId) {
                $query->where('id', $searchUserId);
            })
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->orderBy('created_at', 'DESC')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('d.m.Y');
            })
            ->toArray();

        $linksWithVisits = [];
        foreach ($linksPerDay as $day => $arrayOfLinks) {
            foreach ($arrayOfLinks as $link) {
                if (!isset($linksWithVisits[$day][$link['link_id']])) {
                    $lastVisit = Visit::where('link_id', $link['link_id'])
                        ->orderBy('created_at', 'DESC')
                        ->first();
                    $lastVisitTime = Carbon::parse($lastVisit->created_at)->format('d.m H:i');
                    $linksWithVisits[$day][$link['link_id']] = [
                        'link_short' => $link['link']['domain']['domain'] . '/' . $link['link']['link_short'],
                        'link_full' => $link['link']['link_full'],
                        'last_visit' => $lastVisitTime,
                        'tiktok' => $link['link']['tiktok'],
                        'country' => $link['link']['country'],
                        'count' => 1,
                    ];
                }else {
                    $linksWithVisits[$day][$link['link_id']]['count']++;
                }
            }
        }

        $linksUnique = [];
        $ourStats = [
            'links_count' => 0,
            'visits_count' => 0,
            'leads_count' => 0,
            'revenue' => 0,
        ];
        $linksInfo = [];
        foreach ($linksWithVisits as $day => $links) {
            $countAll = 0;
            $visitLast = null;
            $leadsCount = 0;
            $leadsSum = 0;
            $linksCount = count($links);
            foreach ($links as $id => $link) {
                if (!in_array($id, $linksUnique)) $linksUnique[] = $id;
                $revenueSum = 0;
                $dateFormat = explode('.', $day);
                $linkLeads = Lead::where('link_id', $id)
                    ->whereDay('created_at', $dateFormat[0])
                    ->whereMonth('created_at', $dateFormat[1])
                    ->whereYear('created_at', $dateFormat[2])
                    ->get();
                $countLinkLeads = $linkLeads->count();

                foreach ($linkLeads as $lead) {
                    $revenueSum += $lead->revenue;
                }

                $linksWithVisits[$day][$id]['leads_count'] = $countLinkLeads;
                $leadsCount += $countLinkLeads;
                $ourStats['leads_count'] += $countLinkLeads;

                $linksWithVisits[$day][$id]['leads_sum'] = $revenueSum;
                $leadsSum += $revenueSum;
                $ourStats['revenue'] += $revenueSum;

                $ourStats['links_count'] += $linksCount;

                $countAll += $link['count'];
                $ourStats['visits_count'] += $link['count'];
                if ($visitLast == null) {
                    $visitLast = $link['last_visit'];
                }elseif ($visitLast > $link['last_visit']) {
                    $visitLast = $link['last_visit'];
                }
            }
            $linksInfo[$day] = [
                'count' => $countAll,
                'last_visit' => $visitLast,
                'leads_count' => $leadsCount,
                'leads_sum' => $leadsSum,
            ];
        }
        $ourStats['links_count'] = count($linksUnique);

        return view('admin.stats.index', compact('linksWithVisits', 'linksInfo', 'ourStats'));
    }

    public function perUser()
    {
        $users = User::all();
        return view('admin.stats.search', compact('users'));
    }

    public function searchPerUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period' => [
                'required',
                Rule::in([1, 7, 30]),
            ],
        ]);

        $data = $request->all();
        $searchUserId = $data['user_id'];

        $searchDate = Carbon::now()->subDays($data['period']);

        $linksPerDay = Visit::select('id', 'link_id', 'created_at')
            ->with(['link', 'link.domain'])
            ->whereHas('link.user', function (Builder $query) use ($searchUserId) {
                $query->where('id', $searchUserId);
            })
            ->where('created_at', '>', $searchDate)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('d.m.Y');
            })
            ->toArray();

        $linksWithVisits = [];
        foreach ($linksPerDay as $day => $arrayOfLinks) {
            foreach ($arrayOfLinks as $link) {
                if (!isset($linksWithVisits[$day][$link['link_id']])) {
                    $lastVisit = Visit::where('link_id', $link['link_id'])
                        ->orderBy('created_at', 'DESC')
                        ->first();
                    $lastVisitTime = Carbon::parse($lastVisit->created_at)->format('d.m H:i');
                    $linksWithVisits[$day][$link['link_id']] = [
                        'link_short' => $link['link']['domain']['domain'] . '/' . $link['link']['link_short'],
                        'link_full' => $link['link']['link_full'],
                        'last_visit' => $lastVisitTime,
                        'tiktok' => $link['link']['tiktok'],
                        'country' => $link['link']['country'],
                        'count' => 1,
                    ];
                }else {
                    $linksWithVisits[$day][$link['link_id']]['count']++;
                }
            }
        }

        $linksUnique = [];
        $ourStats = [
            'links_count' => 0,
            'visits_count' => 0,
            'leads_count' => 0,
            'revenue' => 0,
        ];
        $linksInfo = [];
        foreach ($linksWithVisits as $day => $links) {
            $countAll = 0;
            $visitLast = null;
            $leadsCount = 0;
            $leadsSum = 0;
            $linksCount = count($links);
            foreach ($links as $id => $link) {
                if (!in_array($id, $linksUnique)) $linksUnique[] = $id;
                $revenueSum = 0;
                $dateFormat = explode('.', $day);
                $linkLeads = Lead::where('link_id', $id)
                    ->whereDay('created_at', $dateFormat[0])
                    ->whereMonth('created_at', $dateFormat[1])
                    ->whereYear('created_at', $dateFormat[2])
                    ->get();
                $countLinkLeads = $linkLeads->count();

                foreach ($linkLeads as $lead) {
                    $revenueSum += $lead->revenue;
                }

                $linksWithVisits[$day][$id]['leads_count'] = $countLinkLeads;
                $leadsCount += $countLinkLeads;
                $ourStats['leads_count'] += $countLinkLeads;

                $linksWithVisits[$day][$id]['leads_sum'] = $revenueSum;
                $leadsSum += $revenueSum;
                $ourStats['revenue'] += $revenueSum;

                $ourStats['links_count'] += $linksCount;

                $countAll += $link['count'];
                $ourStats['visits_count'] += $link['count'];
                if ($visitLast == null) {
                    $visitLast = $link['last_visit'];
                }elseif ($visitLast > $link['last_visit']) {
                    $visitLast = $link['last_visit'];
                }
            }
            $linksInfo[$day] = [
                'count' => $countAll,
                'last_visit' => $visitLast,
                'leads_count' => $leadsCount,
                'leads_sum' => $leadsSum,
            ];
        }
        $ourStats['links_count'] = count($linksUnique);

        return view('admin.stats.index', compact('linksWithVisits', 'linksInfo', 'ourStats'));
    }
}
