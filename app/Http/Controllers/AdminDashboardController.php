<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Album;
use App\Models\Photo;
use App\Models\BannedIp;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $albums = Album::with('photos')->get();

        $albumCount = Album::count();

        $photoCount = Photo::count();

        $diskFreeSpace = $this->getDiskFreeSpace();

        return view('admin_pages.index', [
            'albumCount' => $albumCount,
            'photoCount' => $photoCount,
            'diskFreeSpace' => $diskFreeSpace,
            'albums' => $albums,
        ]);
    }

    protected function getDiskFreeSpace()
    {
        $freeSpaceBytes = disk_free_space("/");
        $freeSpaceGB = round($freeSpaceBytes / (1024 * 1024 * 1024), 2);

        return $freeSpaceGB . ' GB';
    }

    public function banList()
    {

        $albumCount = Album::count();

        $photoCount = Photo::count();

        $diskFreeSpace = $this->getDiskFreeSpace();

        $bannedIps = BannedIp::all();

        return view('admin_pages.banList',[
            'albumCount' => $albumCount,
            'photoCount' => $photoCount,
            'diskFreeSpace' => $diskFreeSpace,
            'bannedIps' => $bannedIps,
            ]);
    }

    public function banIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip'
        ]);

        BannedIp::updateOrCreate(
            ['ip_address' => $request->ip_address],
            ['blocked_at' => now()]
        );

        return redirect()->route('admin.banList')->with('success', 'IP адрес успешно заблокирован.');
    }

    public function unbanIp(Request $request)
    {
        $ip = $request->input('ip');
        $bannedIp = BannedIp::where('ip_address', $ip)->first();

        if ($bannedIp) {
            $bannedIp->delete();
            return redirect()->route('admin.banList')->with('success', 'IP успешно разблокирован.');
        }

        return redirect()->route('admin.banList')->with('error', 'IP не найден.');
    }

    public function deletePosts(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);



        $startTime = $request->start_time ?? '00:00';
        $endTime = $request->end_time ?? '23:59';


        $startDateTime = $request->start_date . ' ' . $startTime . ':00';
        $endDateTime = $request->end_date . ' ' . $endTime . ':00';


        Album::whereBetween('created_at', [$startDateTime, $endDateTime])->each(function($album) {
            $album->photos()->delete();
            $album->delete();
        });

        return redirect()->route('admin.dashboard')->with('success', 'Альбомы и фотографии успешно удалены.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        if (!Hash::check($request->current_password, auth()->guard('admin')->user()->password)) {
            return back()->withErrors(['current_password' => 'Неверный текущий пароль']);
        }

        $admin = auth()->guard('admin')->user();
        $admin->password = $request->new_password;
        $admin->save();

        return redirect()->route('admin.dashboard')->with('success', 'Пароль успешно изменен');
    }

    public function showChangePasswordForm()
    {
        return view('admin_pages.changePassword');
    }

    public function adminList()
    {
        $admins = Admin::all();

        $albumCount = Album::count();

        $photoCount = Photo::count();

        $diskFreeSpace = $this->getDiskFreeSpace();

        return view('admin_pages.my_admins', ['admins' => $admins, 'albumCount' => $albumCount, 'photoCount' => $photoCount, 'diskFreeSpace' => $diskFreeSpace]);
    }

    public function addAdmin(Request $request)
    {
        if (Auth::guard('admin')->user()->role !== 'Owner') {
            return redirect()->route('admin.adminList')->with('error', 'Only Owner can add administrators.');
        }

        $request->validate([
            'username' => 'required|unique:admins,username',
            'password' => 'required|min:8',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => $request->password,
            'role' => 'Admin',
        ]);

        return redirect()->route('admin.adminList')->with('success', 'Administrator added.');
    }

    public function removeAdmin($id)
    {
        $admin = Admin::findOrFail($id);

        if (Auth::guard('admin')->user()->role !== 'Owner') {
            return redirect()->route('admin.adminList')->with('error', 'Only Owner can remove administrators.');
        }

        if ($admin->role === 'Owner') {
            return redirect()->route('admin.adminList')->with('error', 'You cannot remove the Owner.');
        }

        $admin->delete();

        return redirect()->route('admin.adminList')->with('success', 'Administrator removed.');
    }

}
