<?php

namespace App\Http\Controllers;

use App\Models\Masjid;
use App\Models\Message;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    public function home()
    {
        $featured = Masjid::query()->orderBy('name')->get();
        $defaultMasjid = $featured->first();
        $nextPrayer = $defaultMasjid ? $this->nextPrayer($defaultMasjid) : ['name' => 'Fajr', 'time' => '04:30'];
        $total = Masjid::count();
        $sunniCount = Masjid::query()->where('sect', 'Sunni')->count();
        $shiaCount = Masjid::query()->where('sect', 'Shia')->count();

        return view('public.home', compact('featured', 'defaultMasjid', 'nextPrayer', 'total', 'sunniCount', 'shiaCount'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function aboutDetails()
    {
        return view('public.about-details');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Message::create($validated);

        return back()->with('status', 'Your message has been received.');
    }

    public function masjids(Request $request)
    {
        $query = Masjid::query();
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('address', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('sect') && in_array($request->sect, ['Sunni', 'Shia'])) {
            $query->where('sect', $request->sect);
        }

        $masjids = $query->orderBy('name')->paginate(12);

        return view('public.masjids', compact('masjids'));
    }

    public function showMasjid(Masjid $masjid)
    {
        $nearby = Masjid::query()
            ->where('id', '!=', $masjid->id)
            ->get()
            ->map(function (Masjid $item) use ($masjid) {
                $item->distance = $this->distanceInKm(
                    (float) $masjid->latitude,
                    (float) $masjid->longitude,
                    (float) $item->latitude,
                    (float) $item->longitude
                );

                return $item;
            })
            ->sortBy('distance')
            ->values()
            ->take(5);

        $nextPrayer = $this->nextPrayer($masjid);

        return view('public.masjid-details', compact('masjid', 'nearby', 'nextPrayer'));
    }

    public function map()
    {
        $masjids = Masjid::query()->orderBy('name')->get();

        return view('public.map', compact('masjids'));
    }

    public function timings(Request $request)
    {
        $masjids = Masjid::query()->orderBy('name')->get();
        $selectedMasjid = $request->filled('masjid_id')
            ? Masjid::find($request->integer('masjid_id'))
            : null;
        $selectedMasjid ??= $masjids->first();
        $nextPrayer = $selectedMasjid ? $this->nextPrayer($selectedMasjid) : null;

        return view('public.timings', compact('masjids', 'selectedMasjid', 'nextPrayer'));
    }

    public function jumaEid()
    {
        $masjids = Masjid::query()->whereNotNull('juma_time')->orderBy('name')->get();

        return view('public.juma-eid', compact('masjids'));
    }

    public function jumaEidDetails(Masjid $masjid)
    {
        return view('public.juma-eid-details', compact('masjid'));
    }

    public function timingsDetails()
    {
        $masjids = Masjid::query()->orderBy('name')->get();

        return view('public.timings-details', compact('masjids'));
    }

    private function distanceInKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    private function nextPrayer(Masjid $masjid): array
    {
        $labels = [
            'fajr' => 'Fajr',
            'zuhr' => 'Zuhr',
            'asr' => 'Asr',
            'maghrib' => 'Maghrib',
            'isha' => 'Isha',
        ];

        $now = now();
        foreach ($labels as $key => $label) {
            if (!$masjid->{$key}) {
                continue;
            }

            [$hour, $minute] = array_pad(explode(':', $masjid->{$key}), 2, 0);
            $time = now()->setTime((int) $hour, (int) $minute);
            if ($time->greaterThan($now)) {
                return ['key' => $key, 'name' => $label, 'time' => $masjid->{$key}];
            }
        }

        return ['key' => 'fajr', 'name' => 'Fajr', 'time' => $masjid->fajr];
    }
}
