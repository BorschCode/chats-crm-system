<footer class="bg-white border-t border-orange-100 mt-auto">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col items-center gap-4 text-center">
            {{-- Pexels Attribution --}}
            <div class="flex flex-col gap-2">
                <p class="text-gray-700">
                    Photos provided by
                    <a href="https://www.pexels.com"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="font-semibold text-orange-600 hover:text-orange-700 transition-colors underline decoration-2 underline-offset-2">
                        Pexels
                    </a>
                </p>
                <p class="text-sm text-gray-600">
                    Product images are credited to talented photographers on Pexels
                </p>
            </div>

            {{-- Copyright --}}
            <div class="text-sm text-gray-500 pt-4 border-t border-gray-200 w-full">
                <p>&copy; {{ date('Y') }} Product Catalog. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
