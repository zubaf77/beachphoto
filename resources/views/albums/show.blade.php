<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Album</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .background-blur {
            background-image: url('{{ asset('img/beach.jpg') }}');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -2; /* Позиционируем фоновое изображение под контентом */
        }

        .album-link {
            max-width: 400px;
            width: 100%;
            bottom: 1rem;
            right: 1rem;
        }

        .quote-container {
            max-width: 800px;
            margin: auto;
        }
    </style>
</head>
<body class="relative min-h-screen bg-black text-white">

<div class="background-blur"></div>
<div class="absolute inset-0 bg-purple-700 bg-opacity-50 z-0"></div>

<main class="min-h-[100vh] py-[100px] flex flex-col justify-center items-center relative z-10">
    <section class="container mx-auto px-4 relative">
        <div id="slider-wrapper" class="hidden xl:flex xl:flex-row xl:gap-8 items-center justify-center relative">
            <div id="image-slider" class="keen-slider relative z-[1] overflow-hidden bg-white/40 rounded-xl w-full max-w-[800px] h-[800px]">
                @foreach($album->photos as $photo)
                    <div class="keen-slider__slide flex flex-col justify-center items-center w-full h-full">
                        <div class="rounded-xl overflow-hidden w-full h-full flex justify-center items-center mb-4">
                            <a href="{{ asset($photo->path) }}" target="_blank">
                                <img class="block max-w-full max-h-full object-contain" src="{{ asset($photo->path) }}" alt="{{ $photo->description }}" />
                            </a>
                        </div>
                        <div class="text-lg text-white font-medium text-center mb-20">
                            <a href="{{ asset($photo->path) }}" class="text-blue-500" target="_blank">{{ $photo->path }}</a>
                        </div>
                    </div>
                @endforeach
                <div class="keen-slider__slide flex flex-col justify-center items-center w-full h-full">
                    <div class="text-center mt-4 p-4 bg-black/70 rounded-lg text-white">
                        <p class="italic">{{ $quote }}</p>
                        <p class="font-bold">{{ $author }}</p>
                    </div>
                </div>
            </div>

            <div class="absolute album-link bg-orange-500 text-white p-4 rounded-lg lg:relative lg:max-w-[400px] mt-8 lg:mt-0">
                <p class="font-bold mb-2">{{__("show.link")}}</p>
                <input type="text" id="albumLink" value="{{ route('albums.show', ['secure_token' => $album->secure_token]) }}" readonly class="w-full bg-white text-black p-2 rounded">
                <button onclick="copyToClipboard()" class="mt-2 bg-white text-red-500 font-bold p-2 rounded">{{__("show.copy")}}</button>
            </div>
        </div>

        <div class="relative xl:hidden flex flex-col gap-6 [&>*:nth-child(2)>img]:order-1">
            @foreach($album->photos as $photo)
                <div class="grid grid-cols-2 gap-3 md:gap-5 lg:md:gap-8 items-center">
                    <img class="block w-full h-full object-cover rounded-md" src="{{ asset($photo->path) }}" alt="{{ $photo->description }}" />
                    <p class="m-0 text-sm md:text-md lg:text-lg text-white -mt-16 sm:-mt-20">
                        <a href="{{ asset($photo->path) }}" class="text-blue-500" target="_blank">{{ $photo->path }}</a>
                    </p>
                </div>
            @endforeach

            <div class="text-center mt-4 p-4 bg-black/70 rounded-lg text-white quote-container">
                <p class="italic">{{ $quote }}</p>
                <p class="font-bold">{{ $author }}</p>
            </div>

            <div class="bg-orange-500 text-white p-4 rounded-lg mt-4">
                <p class="font-bold mb-2">{{__("show.link")}}</p>
                <input type="text" id="albumLink" value="{{ route('albums.show', ['secure_token' => $album->secure_token]) }}" readonly class="w-full bg-white text-black p-2 rounded">
                <button onclick="copyToClipboard()" class="mt-2 bg-white text-red-500 font-bold p-2 rounded">{{__("show.copy")}}</button>
            </div>
        </div>
    </section>
</main>


<script>
    function copyToClipboard() {
        const copyText = document.getElementById("albumLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Ссылка скопирована: " + copyText.value);
    }

</script>
</body>
</html>
