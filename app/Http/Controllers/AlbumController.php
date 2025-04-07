<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Album;
use App\Models\Photo;
use Imagick;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::all();
        return view('albums.index', compact('albums'));
    }

    public function create()
    {
        return view('albums.create');
    }

    public function show($secure_token, Request $request)
    {
        if (Auth::guard('admin')->check()) {

            $album = Album::with('photos')->where('secure_token', $secure_token)->firstOrFail();
        } else {

            $album = Album::with('photos')->where('secure_token', $secure_token)->firstOrFail();

            $password = session('album_password_' . $secure_token);
            if ($album->password && !$password) {
                return view('albums.password', ['secure_token' => $secure_token])
                    ->with('error', 'Введите правильный пароль');
            }
            $album->incrementViews();
        }
        $quote = 'No quote available';
        $author = 'Unknown';

        try {
            $response = Http::get('https://zenquotes.io/api/random');
            if ($response->successful()) {
                $quoteData = $response->json();
                $quote = $quoteData[0]['q'] ?? 'No quote available';
                $author = $quoteData[0]['a'] ?? 'Unknown';
            } else {
                Log::warning('Quote API returned an error: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch quote: ' . $e->getMessage());
        }

        $locale = App::getLocale();
        if ($locale !== 'en') {
            $translatedQuote = $this->translateText($quote, $locale);
            $translatedAuthor = $this->translateText($author, $locale);
        } else {
            $translatedQuote = $quote;
            $translatedAuthor = $author;
        }

        return view('albums.show', [
            'album' => $album,
            'quote' => $translatedQuote,
            'author' => $translatedAuthor
        ]);
    }

    private function translateText($text, $locale)
    {
        $client = new Client();
        $apiKey = env("YANDEX_API_KEY");

        try {
            $response = $client->post('https://translate.api.cloud.yandex.net/translate/v2/translate', [
                'headers' => [
                    'Authorization' => 'Api-Key ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'folderId' => env("YANDEX_KEY_FOLDER"),
                    'texts' => [$text],
                    'targetLanguageCode' => $locale
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['translations'][0]['text'] ?? $text;

        } catch (\Exception $e) {
            Log::error('Translation API error: ' . $e->getMessage());
            return $text;
        }
    }

    public function checkPassword($secure_token, Request $request)
    {
        $album = Album::where('secure_token', $secure_token)->firstOrFail();

        $request->validate([
            'password' => 'required',
            //'captcha' => 'required|captcha',
        ], [
            'password.required' => 'Пароль обязателен.',
            //'captcha.required' => 'Капча обязательна.',
            //'captcha.captcha' => 'Неверно введена капча.',
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = config('services.recaptcha.secret_key');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
        ]);

        $responseData = $response->json();

        if (!$responseData['success']) {
            return redirect()->back()->withErrors(['captcha' => 'Неверно введена капча.']);
        }

        if (!Hash::check($request->input('password'), $album->password)) {
            return redirect()->back()->with('error', __('password.message'));
        }

        session(['album_password_' . $secure_token => $request->input('password')]);

        return redirect()->route('albums.show', ['secure_token' => $secure_token]);
    }

    public function store(Request $request)
    {

        // Валидация данных
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'password' => 'nullable|string',
            'delete_after_views' => 'nullable|integer',
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:35840', // 2MB ограничение
        ], [
            'name.required' => 'Название альбома обязательно.',
            'photos.required' => 'Вы должны загрузить хотя бы одно фото.',
            'photos.*.mimes' => 'Формат изображения должен быть jpeg, png, jpg, gif или webp.',
            'photos.*.max' => 'Размер изображения не должен превышать 35MB.',
        ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = config('services.recaptcha.secret_key');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
        ]);

        $responseData = $response->json();

        if (!$responseData['success']) {
            return redirect()->back()->withErrors(['captcha' => 'Неверно введена капча.']);
        }

        $ipAddress = $request->server->get('HTTP_X_FORWARDED_FOR') ?? $request->ip();


        try {

            $data = $request->only(['name', 'description', 'delete_after_views']);
            $data['ip_address'] = $ipAddress;

            if ($request->filled('password')) {
                $data['password'] = $request->input('password');
            }

            $album = Album::create($data);

            $resize = $request->input('resize_image');
            $newWidth = $request->input('width');
            $newHeight = $request->input('height');

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photoFile) {
                    $tempPath = $photoFile->store('temp', 'uploads');

                    if (!Storage::disk('uploads')->exists($tempPath)) {
                        Log::error('Temporary file not saved.');
                        return redirect()->back()->with('error', 'Temporary file not saved.');
                    }

                    try {
                        $imagick = new Imagick(Storage::disk('uploads')->path($tempPath));
                        $imagick->stripImage();

                        $imageSize = filesize(Storage::disk('uploads')->path($tempPath));

                        if ($imageSize > 2.5 * 1024 * 1024) {
                            $imagick->resizeImage($imagick->getImageWidth() * 0.4, $imagick->getImageHeight() * 0.4, Imagick::FILTER_LANCZOS, 1);
                            //$newImageSize = strlen($imagick->getImageBlob());
                        }

                        if ($imageSize > 2.5 * 1024 * 1024) {
                            $imagick->setImageFormat('jpeg');
                            $compressionQuality = 85;
                            $imagick->setCompressionQuality($compressionQuality);
                            $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);

                        }

                        if ($resize && $newWidth && $newHeight) {
                            $imagick->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1);
                        }

                        $imagePath = '/img/uploads/' . $photoFile->hashName();
                        $fullImagePath = storage_path('app' . $imagePath);

                        if ($imagick->writeImage($fullImagePath)) {
                            Log::info('Image successfully written to: ' . $fullImagePath);
                        } else {
                            Log::error('Failed to write image to: ' . $fullImagePath);
                            return redirect()->back()->with('error', 'Failed to write image.');
                        }

                        if (!file_exists($fullImagePath)) {
                            Log::error('Image not saved in expected path.');
                            return redirect()->back()->with('error', 'Image not saved in expected path: ' . $fullImagePath);
                        }


                        Storage::disk('uploads')->delete($tempPath);
                        Log::info('Temporary file deleted: ' . $tempPath);


                        Photo::create([
                            'album_id' => $album->id,
                            'path' => $imagePath,
                            'description' => $request->description,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error processing image: ' . $e->getMessage());
                        return redirect()->back()->with('error', 'Error processing image.');
                    }
                }
            }

            session(['album_password_' . $album->secure_token => $album->password]);

            return redirect()->route('albums.show', ['secure_token' => $album->secure_token])
                ->with('success', 'Album and photos uploaded successfully');
        } catch (\Exception $e) {
            Log::error('Error creating album: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create album.');
        }
    }

    public function destroy($id)
    {
        $album = Album::findOrFail($id);

        $album->destroyAlbum();

        return redirect()->route('admin.dashboard')->with('success', 'Album deleted successfully.');
    }
}
