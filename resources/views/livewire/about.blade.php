<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg overflow-hidden rounded-lg border border-orange-100">
        <div class="px-6 py-5 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-orange-100">
            <h2 class="text-3xl font-bold text-gray-900">About Us</h2>
            <p class="mt-1 text-sm text-gray-600">Learn more about our product catalog system</p>
        </div>
        <div class="px-6 py-8">
            <div class="prose max-w-none">
                <p class="text-gray-700 leading-relaxed mb-4">
                    Welcome to our Product Catalog System. This platform provides a comprehensive way to browse and explore our product offerings organized by categories.
                </p>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Our system allows you to easily navigate through different product groups, view detailed information about individual items, and discover products that meet your needs.
                </p>

                <h3 class="text-xl font-semibold text-gray-900 mt-6 mb-3">Features</h3>
                <ul class="list-disc list-inside space-y-2 text-gray-700 mb-6">
                    <li>Browse products by category</li>
                    <li>View detailed product information</li>
                    <li>Seamless navigation between items and groups</li>
                    <li>Responsive design for all devices</li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">Contact Us</h3>
                <p class="text-gray-700 leading-relaxed mb-6">
                    We offer multiple convenient ways to connect with us. Choose your preferred messaging platform below.
                </p>

                <!-- Tabbed Interface -->
                <div x-data="{ activeTab: 'whatsapp' }" class="mb-8">
                    <!-- Tab Navigation -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex gap-4" aria-label="Tabs">
                            @if ($whatsappPhone)
                                <button
                                    @click="activeTab = 'whatsapp'"
                                    :class="activeTab === 'whatsapp' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                    WhatsApp
                                </button>
                            @endif

                            @if ($telegramUsername)
                                <button
                                    @click="activeTab = 'telegram'"
                                    :class="activeTab === 'telegram' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                    </svg>
                                    Telegram
                                </button>
                            @endif

                            @if ($instagramUsername)
                                <button
                                    @click="activeTab = 'instagram'"
                                    :class="activeTab === 'instagram' ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                    </svg>
                                    Instagram
                                </button>
                            @endif
                        </nav>
                    </div>

                    <!-- WhatsApp Tab Content -->
                    @if ($whatsappPhone)
                        <div x-show="activeTab === 'whatsapp'" x-cloak>
                            <!-- Method 1: Click-to-Chat Link -->
                            <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">1. WhatsApp Click-to-Chat Link</h4>
                                        <p class="text-gray-700 mb-4">
                                            Click the button below to open WhatsApp and start chatting with us instantly. Works on any device with WhatsApp installed.
                                        </p>
                                        <a href="{{ $whatsappClickToChat }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            Chat with Us on WhatsApp
                                        </a>
                                        <div class="mt-3 p-3 bg-white rounded border border-green-300">
                                            <p class="text-sm text-gray-600 mb-1 font-medium">Direct Link:</p>
                                            <code class="text-xs text-green-700 break-all">{{ $whatsappClickToChat }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Method 2: QR Code -->
                            <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">2. Scan Our WhatsApp QR Code</h4>
                                        <p class="text-gray-700 mb-4">
                                            Generate a QR code for your physical store or website. Customers can scan it with their phone camera to start a WhatsApp conversation instantly.
                                        </p>
                                        <div class="bg-white p-4 rounded-lg border border-blue-300 mb-4">
                                            <p class="text-sm text-gray-700 mb-2 font-medium">Generate QR Code:</p>
                                            <p class="text-sm text-gray-600 mb-3">Use any QR code generator with this URL:</p>
                                            <code class="text-xs text-blue-700 break-all block mb-3 p-2 bg-blue-50 rounded">{{ $whatsappQrCodeUrl }}</code>
                                            <p class="text-xs text-gray-500">Recommended tools: <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($whatsappQrCodeUrl) }}" target="_blank" class="text-blue-600 hover:underline">QR Code API</a>, or search for "QR code generator"</p>
                                        </div>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
                                            <li>Print on packaging or product displays</li>
                                            <li>Display at checkout or reception</li>
                                            <li>Add to marketing materials</li>
                                            <li>Include in email signatures</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Method 3: Floating WhatsApp Button -->
                            <div class="mb-6 p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-200">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">3. Floating WhatsApp Contact Button</h4>
                                        <p class="text-gray-700 mb-4">
                                            Add a professional floating WhatsApp button to your website. It stays visible as users scroll, making it easy for them to contact you anytime.
                                        </p>
                                        <div class="bg-white p-4 rounded-lg border border-purple-300">
                                            <p class="text-sm font-medium text-gray-900 mb-2">Implementation Options:</p>
                                            <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
                                                <li><strong>Simple HTML/CSS:</strong> Add a fixed-position button with WhatsApp branding</li>
                                                <li><strong>Widget Plugins:</strong> Use tools like WhatsApp Chat Widget, Elfsight, or GetButton</li>
                                                <li><strong>Custom Integration:</strong> Build your own using the wa.me link</li>
                                            </ul>
                                            <div class="mt-3 p-3 bg-purple-50 rounded">
                                                <p class="text-xs text-gray-600 mb-1">Your WhatsApp Link:</p>
                                                <code class="text-xs text-purple-700 break-all">{{ $whatsappClickToChat }}</code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Telegram Tab Content -->
                    @if ($telegramUsername)
                        <div x-show="activeTab === 'telegram'" x-cloak>
                            <!-- Telegram Click-to-Chat -->
                            <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg border border-blue-200">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">1. Telegram Bot Link</h4>
                                        <p class="text-gray-700 mb-4">
                                            Click the button below to open our Telegram bot and start chatting instantly. Available on mobile and desktop.
                                        </p>
                                        <a href="{{ $telegramClickToChat }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                            </svg>
                                            Chat with Us on Telegram
                                        </a>
                                        <div class="mt-3 p-3 bg-white rounded border border-blue-300">
                                            <p class="text-sm text-gray-600 mb-1 font-medium">Direct Link:</p>
                                            <code class="text-xs text-blue-700 break-all">{{ $telegramClickToChat }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Telegram QR Code -->
                            <div class="mb-8 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">2. Telegram QR Code</h4>
                                        <p class="text-gray-700 mb-4">
                                            Create a QR code that customers can scan to instantly connect with your Telegram bot.
                                        </p>
                                        <div class="bg-white p-4 rounded-lg border border-indigo-300 mb-4">
                                            <p class="text-sm text-gray-700 mb-2 font-medium">Generate QR Code:</p>
                                            <p class="text-sm text-gray-600 mb-3">Use any QR code generator with this URL:</p>
                                            <code class="text-xs text-indigo-700 break-all block mb-3 p-2 bg-indigo-50 rounded">{{ $telegramClickToChat }}</code>
                                            <p class="text-xs text-gray-500">Recommended: <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($telegramClickToChat) }}" target="_blank" class="text-indigo-600 hover:underline">Generate QR Code</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Telegram Widget -->
                            <div class="mb-6 p-6 bg-gradient-to-r from-cyan-50 to-sky-50 rounded-lg border border-cyan-200">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">3. Add Telegram Widget to Your Site</h4>
                                        <p class="text-gray-700 mb-4">
                                            Embed a Telegram widget on your website for seamless customer engagement.
                                        </p>
                                        <div class="bg-white p-4 rounded-lg border border-cyan-300">
                                            <p class="text-sm font-medium text-gray-900 mb-2">Implementation Options:</p>
                                            <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
                                                <li><strong>Telegram Widget:</strong> Use official Telegram widget for website integration</li>
                                                <li><strong>Custom Button:</strong> Create a floating button linking to your bot</li>
                                                <li><strong>Deep Links:</strong> Use t.me links in buttons, menus, and CTAs</li>
                                            </ul>
                                            <div class="mt-3 p-3 bg-cyan-50 rounded">
                                                <p class="text-xs text-gray-600 mb-1">Your Bot Username:</p>
                                                <code class="text-xs text-cyan-700 break-all">@{{ $telegramUsername }}</code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Instagram Tab Content -->
                    @if ($instagramUsername)
                        <div x-show="activeTab === 'instagram'" x-cloak>
                            <!-- Instagram Direct Message -->
                            <div class="mb-8 p-6 bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg border border-pink-200">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">1. Instagram Direct Message Link</h4>
                                        <p class="text-gray-700 mb-4">
                                            Click the button below to open Instagram Direct Messages and start chatting with us.
                                        </p>
                                        <a href="{{ $instagramClickToChat }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                            </svg>
                                            Message Us on Instagram
                                        </a>
                                        <div class="mt-3 p-3 bg-white rounded border border-pink-300">
                                            <p class="text-sm text-gray-600 mb-1 font-medium">Direct Link:</p>
                                            <code class="text-xs text-pink-700 break-all">{{ $instagramClickToChat }}</code>
                                        </div>
                                        <div class="mt-3 p-3 bg-pink-50 rounded">
                                            <p class="text-sm text-gray-600 mb-1 font-medium">Instagram Username:</p>
                                            <code class="text-xs text-pink-700 break-all">@{{ $instagramUsername }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Instagram QR Code -->
                            <div class="mb-8 p-6 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg border border-purple-200">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">2. Instagram QR Code</h4>
                                        <p class="text-gray-700 mb-4">
                                            Generate a QR code that directs customers to your Instagram Direct Messages.
                                        </p>
                                        <div class="bg-white p-4 rounded-lg border border-purple-300 mb-4">
                                            <p class="text-sm text-gray-700 mb-2 font-medium">Generate QR Code:</p>
                                            <p class="text-sm text-gray-600 mb-3">Use any QR code generator with this URL:</p>
                                            <code class="text-xs text-purple-700 break-all block mb-3 p-2 bg-purple-50 rounded">{{ $instagramClickToChat }}</code>
                                            <p class="text-xs text-gray-500">Recommended: <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($instagramClickToChat) }}" target="_blank" class="text-purple-600 hover:underline">Generate QR Code</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Instagram Button Integration -->
                            <div class="mb-6 p-6 bg-gradient-to-r from-fuchsia-50 to-pink-50 rounded-lg border border-fuchsia-200">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-12 w-12 text-fuchsia-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">3. Add "Message on Instagram" Button</h4>
                                        <p class="text-gray-700 mb-4">
                                            Add a button or widget to your website that opens Instagram Direct Messages.
                                        </p>
                                        <div class="bg-white p-4 rounded-lg border border-fuchsia-300">
                                            <p class="text-sm font-medium text-gray-900 mb-2">Implementation Options:</p>
                                            <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
                                                <li><strong>Direct Link Button:</strong> Add a button with your ig.me link</li>
                                                <li><strong>Instagram Badge:</strong> Use Instagram's official "Message" badge</li>
                                                <li><strong>Social Media Icons:</strong> Include in your social media icon bar</li>
                                            </ul>
                                            <div class="mt-3 p-3 bg-fuchsia-50 rounded">
                                                <p class="text-xs text-gray-600 mb-1">Your Instagram Link:</p>
                                                <code class="text-xs text-fuchsia-700 break-all">{{ $instagramClickToChat }}</code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Benefits Section (Shown for all tabs) -->
                    <div class="mt-8 p-6 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-lg border border-amber-200">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Why Use Messaging Platforms?</h4>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-700">Instant communication</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-700">No template message restrictions</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-700">User-initiated conversations</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-700">Better for scaling your business</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-700">Works on mobile and desktop</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-700">Reduces friction for customers</span>
                            </li>
                        </ul>
                    </div>
                </div>

                @if (!$whatsappPhone && !$telegramUsername && !$instagramUsername)
                    <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-yellow-800">
                            <strong>Note:</strong> Messaging contact information is not configured. Please set the following environment variables:
                        </p>
                        <ul class="mt-2 list-disc list-inside text-sm text-yellow-700">
                            <li><code class="bg-yellow-100 px-2 py-1 rounded text-xs">WHATSAPP_BUSINESS_PHONE</code></li>
                            <li><code class="bg-yellow-100 px-2 py-1 rounded text-xs">TELEGRAM_BOT_USERNAME</code></li>
                            <li><code class="bg-yellow-100 px-2 py-1 rounded text-xs">INSTAGRAM_USERNAME</code></li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
