<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Imagick;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhotoController extends Controller
{
    use HasFactory;

    protected $fillable = ['album_id', 'path', 'description', 'views'];

    public function create()
    {
        return view('photos.create');
    }

    public function show(Photo $photo)
    {
        $photo->incrementViews();
        return view('photos.show', compact('photo'));
    }
}
