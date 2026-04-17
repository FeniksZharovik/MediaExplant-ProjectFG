<div class="container mx-auto px-4 py-8 flex justify-center items-center min-h-screen">
    <div class="bg-white w-full max-w-6xl h-[90vh] rounded-xl shadow-lg relative flex flex-col">
        <!-- Body / DearFlip -->
        <div class="flex-1 overflow-hidden">
            <div class="_df_custom w-full h-full" source="{{ route('buletin.pdfPreview', ['id' => $buletin->id]) }}"></div>
        </div>
    </div>
</div>

<!-- DearFlip CSS & JS -->
<link rel="stylesheet" href="{{ asset('dflip/css/dflip.min.css') }}">
<script src="{{ asset('dflip/js/libs/jquery.min.js') }}"></script>
<script src="{{ asset('dflip/js/dflip.min.js') }}"></script>

<!-- Load DearFlip -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        new DFLIP.Book({
            source: "{{ route('buletin.pdfPreview', ['id' => $buletin->id]) }}",
            container: document.querySelector("._df_custom"),
        });
    });
</script>
