<!-- Modal Share -->
<div id="shareModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
    <div class="bg-[#212121] rounded-2xl p-6 w-full max-w-md relative">

        <!-- Tombol Close -->
        <button id="closeShareModal" class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl">
            &times;
        </button>

        <!-- Title -->
        <h2 class="text-white text-center text-xl font-semibold mb-6">Bagikan</h2>

        <!-- Wrapper Icon + Slide Button -->
        <div class="relative flex items-center">
            <!-- Tombol Slide Kiri -->
            <button id="slideLeft"
                class="absolute left-0 z-10 bg-[#333] hover:bg-[#444] text-white p-2 rounded-full shadow-md">
                &#10094;
            </button>

            <!-- Slide Icon Bagikan -->
            <div id="iconContainer"
                class="flex overflow-x-auto space-x-6 px-10 py-2 scrollbar-none snap-x snap-mandatory">
                @php
                    $socials = [
                        [
                            'name' => 'WhatsApp',
                            'icon' => 'https://img.icons8.com/color/48/whatsapp.png',
                            'base' => 'https://wa.me/?text=',
                        ],
                        [
                            'name' => 'Facebook',
                            'icon' => 'https://img.icons8.com/color/48/facebook-new.png',
                            'base' => 'https://www.facebook.com/sharer/sharer.php?u=',
                        ],
                        [
                            'name' => 'X',
                            'icon' => 'https://img.icons8.com/ios-filled/50/000000/twitterx.png',
                            'base' => 'https://twitter.com/intent/tweet?url=',
                        ],
                        [
                            'name' => 'LinkedIn',
                            'icon' => 'https://img.icons8.com/color/48/linkedin.png',
                            'base' => 'https://www.linkedin.com/shareArticle?mini=true&url=',
                        ],
                        [
                            'name' => 'Email',
                            'icon' => 'https://img.icons8.com/fluency/48/new-post.png',
                            'base' => 'mailto:?body=',
                        ],
                        [
                            'name' => 'Reddit',
                            'icon' => 'https://img.icons8.com/color/48/reddit--v1.png',
                            'base' => 'https://reddit.com/submit?url=',
                        ],
                        [
                            'name' => 'VK',
                            'icon' => 'https://img.icons8.com/color/48/vk-circled.png',
                            'base' => 'https://vk.com/share.php?url=',
                        ],
                        [
                            'name' => 'OK',
                            'icon' => 'https://img.icons8.com/color/48/odnoklassniki.png',
                            'base' => 'https://connect.ok.ru/offer?url=',
                        ],
                        [
                            'name' => 'Pinterest',
                            'icon' => 'https://img.icons8.com/color/48/pinterest--v1.png',
                            'base' => 'https://pinterest.com/pin/create/button/?url=',
                        ],
                        [
                            'name' => 'Blogger',
                            'icon' => 'https://img.icons8.com/color/48/blogger.png',
                            'base' => 'https://www.blogger.com/blog-this.g?u=',
                        ],
                        [
                            'name' => 'Tumblr',
                            'icon' => 'https://img.icons8.com/color/48/tumblr.png',
                            'base' => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=',
                        ],
                    ];
                @endphp

                @foreach ($socials as $social)
                    <a href="#" target="_blank" class="flex flex-col items-center min-w-max"
                        data-base="{{ $social['base'] }}">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="{{ $social['icon'] }}" alt="{{ $social['name'] }}" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">{{ $social['name'] }}</span>
                    </a>
                @endforeach
            </div>

            <!-- Tombol Slide Kanan -->
            <button id="slideRight"
                class="absolute right-0 z-10 bg-[#333] hover:bg-[#444] text-white p-2 rounded-full shadow-md">
                &#10095;
            </button>
        </div>

        <!-- Link & Copy -->
        <div class="flex items-center bg-[#121212] rounded-lg overflow-hidden border border-gray-600 mt-6">
            <input id="shareLink" type="text"
                class="flex-1 bg-transparent text-white px-3 py-2 text-sm focus:outline-none" readonly>
            <button id="copyLink" class="bg-[#9A0605] hover:bg-[#7a0504] text-white px-4 py-2 text-sm">Salin</button>
        </div>
    </div>
</div>
