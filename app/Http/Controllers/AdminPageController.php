<?php

namespace App\Http\Controllers;

use App\Models\Masjid;
use App\Models\Message;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminPageController extends Controller
{
    public function dashboard()
    {
        $totalMasjids = Masjid::count();
        $totalMessages = Message::count();
        $sunniCount = Masjid::query()->where('sect', 'Sunni')->count();
        $shiaCount = Masjid::query()->where('sect', 'Shia')->count();
        $unreadMessages = Message::query()->where('is_read', false)->count();
        $recentMasjids = Masjid::query()->orderByDesc('created_at')->take(5)->get();
        $recentMessages = Message::query()->orderByDesc('created_at')->take(5)->get();

        return view('admin.dashboard', compact('totalMasjids', 'totalMessages', 'sunniCount', 'shiaCount', 'unreadMessages', 'recentMasjids', 'recentMessages'));
    }

    public function masjids()
    {
        $masjids = Masjid::query()->orderBy('name')->get();

        return view('admin.masjids', compact('masjids'));
    }

    public function addMasjid()
    {
        return view('admin.add-masjid');
    }

    public function storeMasjid(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'area' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'sect' => 'required|in:Sunni,Shia',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'phone' => 'nullable|string|max:30',
            'description' => 'nullable|string',
            'fajr' => 'nullable|string|max:10',
            'zuhr' => 'nullable|string|max:10',
            'asr' => 'nullable|string|max:10',
            'maghrib' => 'nullable|string|max:10',
            'isha' => 'nullable|string|max:10',
            'juma_time' => 'nullable|string|max:10',
            'eid_time' => 'nullable|string|max:10',
            'is_featured' => 'nullable|boolean',
        ]);

        Masjid::create($validated);

        return redirect()->route('admin.masjids')->with('status', 'Masjid added successfully.');
    }

    public function editMasjid(Masjid $masjid)
    {
        return view('admin.edit-masjid', compact('masjid'));
    }

    public function updateMasjid(Request $request, Masjid $masjid)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'area' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'sect' => 'required|in:Sunni,Shia',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'phone' => 'nullable|string|max:30',
            'description' => 'nullable|string',
            'fajr' => 'nullable|string|max:10',
            'zuhr' => 'nullable|string|max:10',
            'asr' => 'nullable|string|max:10',
            'maghrib' => 'nullable|string|max:10',
            'isha' => 'nullable|string|max:10',
            'juma_time' => 'nullable|string|max:10',
            'eid_time' => 'nullable|string|max:10',
            'is_featured' => 'nullable|boolean',
        ]);

        $masjid->update($validated);

        return redirect()->route('admin.masjids')->with('status', 'Masjid updated successfully.');
    }

    public function deleteMasjid(Request $request)
    {
        Masjid::findOrFail($request->input('id'))->delete();

        return redirect()->route('admin.masjids')->with('status', 'Masjid deleted.');
    }

    public function timings()
    {
        $masjids = Masjid::query()->orderBy('name')->get();

        return view('admin.timings', compact('masjids'));
    }

    public function settings()
    {
        $settings = Setting::pluck('setting_value', 'setting_key');

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(['setting_key' => $key], ['setting_value' => $value]);
        }

        return back()->with('status', 'Settings updated.');
    }
}
