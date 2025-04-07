<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Album extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'views', 'delete_after_views', 'password','secure_token','ip_address'];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function incrementViews()
    {
        $this->increment('views');
        $this->checkAndDelete();
    }

    private function checkAndDelete()
    {
        if ($this->delete_after_views && $this->views >= $this->delete_after_views) {
            $this->destroyAlbum();
        }
    }

    public function checkPassword($password)
    {
        return Hash::check($password,$this->$password);
    }

    public function genToken()
    {
        return Str::random(32);
    }

    public function setNewToken()
    {
        $this->secure_token = $this->genToken();
        //$this->save(); Fucking Recursion Fuck Her
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            $album->setNewToken();
        });
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function destroyAlbum()
    {
        foreach ($this->photos as $photo) {
            $filePath = $photo->path;

            $fullPath = public_path($filePath);

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

        }
        $this->delete();
    }
}

