<div class="mt-4">
    <h2 class="text-lg font-semibold mb-2">Bookmark</h2>
    @if ($user->bookmarks->isNotEmpty())
        <ul class="list-disc list-inside text-gray-700">
            @foreach ($user->bookmarks as $bookmark)
                <li>{{ $bookmark->title }}</li>
            @endforeach
        </ul>
    @else
        <p>No bookmarks found.</p>
    @endif
</div>