<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We received your message</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-8 px-4">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- Header --}}
        <div class="bg-gray-900 px-8 py-6">
            <h1 class="text-white text-xl font-semibold">Message received!</h1>
            <p class="text-gray-400 text-sm mt-1">{{ config('app.name') }}</p>
        </div>

        {{-- Body --}}
        <div class="px-8 py-8">
            <p class="text-gray-800 text-base mb-4">Hi <span class="font-semibold">{{ $contactData['name'] }}</span>,</p>

            <p class="text-gray-600 text-base leading-relaxed mb-6">
                {{ $aiResult['auto_response'] }}
            </p>

            <hr class="border-gray-100 mb-6">

            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Your message</p>
            <div class="bg-gray-50 border-l-4 border-indigo-500 rounded-r-lg p-4 text-gray-500 text-sm leading-relaxed">
                {{ $contactData['comment'] }}
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 border-t border-gray-100 px-8 py-4 text-center text-xs text-gray-400">
            This is an automated confirmation. Please do not reply to this email.
        </div>
    </div>
</body>
</html>
