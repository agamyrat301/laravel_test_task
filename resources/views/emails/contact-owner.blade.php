<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-8 px-4">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- Header --}}
        <div class="bg-gray-900 px-8 py-6">
            <h1 class="text-white text-xl font-semibold">New Contact Form Submission</h1>
            <p class="text-gray-400 text-sm mt-1">{{ now()->format('F j, Y \a\t H:i') }} UTC</p>
        </div>

        {{-- Contact details --}}
        <div class="px-8 py-6 space-y-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Name</p>
                <p class="text-gray-800 text-base mt-0.5">{{ $contactData['name'] }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Email</p>
                <a href="mailto:{{ $contactData['email'] }}" class="text-indigo-600 text-base mt-0.5 block">{{ $contactData['email'] }}</a>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Phone</p>
                <p class="text-gray-800 text-base mt-0.5">{{ $contactData['phone'] }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Message</p>
                <div class="bg-gray-50 border-l-4 border-indigo-500 rounded-r-lg p-4 text-gray-600 text-sm leading-relaxed">
                    {{ $contactData['comment'] }}
                </div>
            </div>
        </div>

        {{-- AI Analysis --}}
        <div class="mx-8 mb-8 bg-indigo-50 rounded-xl p-5">
            <h2 class="text-indigo-700 text-xs font-bold uppercase tracking-widest mb-4">AI Analysis</h2>

            <div class="flex flex-wrap gap-4 mb-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Sentiment</p>
                    @if($aiResult['sentiment'] === 'positive')
                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full">
                            Positive ({{ number_format($aiResult['sentiment_score'], 2) }})
                        </span>
                    @elseif($aiResult['sentiment'] === 'negative')
                        <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">
                            Negative ({{ number_format($aiResult['sentiment_score'], 2) }})
                        </span>
                    @else
                        <span class="inline-block bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full">
                            Neutral ({{ number_format($aiResult['sentiment_score'], 2) }})
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Request Type</p>
                    <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ ucwords(str_replace('_', ' ', $aiResult['request_type'])) }}
                    </span>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Suggested Auto-Response</p>
                <div class="bg-white rounded-lg p-4 text-sm text-gray-600 leading-relaxed border border-gray-200">
                    {{ $aiResult['auto_response'] }}
                </div>
            </div>

            @if(!($aiResult['ai_enabled'] ?? true))
            <p class="text-xs text-gray-400 mt-3">* AI analysis was unavailable; default values were used.</p>
            @endif
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 border-t border-gray-100 px-8 py-4 text-center text-xs text-gray-400">
            Automated notification from {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
