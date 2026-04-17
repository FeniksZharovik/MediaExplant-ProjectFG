@extends('layouts.auth-layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-[#D44040] mb-4">Verifikasi OTP</h2>
        <p class="text-sm text-gray-600 text-center mb-4">
            Silahkan buka email anda dan konfirmasi<br>
            Kami telah mengirimkan kode OTP ke email<br>
            <span class="font-bold">{{ session('register_data.email') ?? 'Email tidak ditemukan' }}</span>
        </p>

        <!-- Pesan sukses jika OTP berhasil dikirim -->
        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-lg mb-4 text-center">
            {{ session('success') }}
        </div>
        @endif

        <!-- Pesan error jika OTP salah -->
        @if ($errors->has('otp'))
        <div id="error-message" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-lg mb-4 text-center">
            {{ $errors->first('otp') }}
        </div>
        @endif

        <form id="otp-form" action="{{ route('verifikasi-akun') }}" method="POST">
            @csrf
            <div class="flex justify-center gap-2 mb-2">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1" class="otp-input w-12 h-12 border border-gray-300 rounded-lg text-center text-xl font-bold focus:outline-none focus:ring-2 focus:ring-[#D44040]" />
                @endfor
                <input type="hidden" id="otp" name="otp">
            </div>
        </form>
        <p class="text-sm text-center text-gray-600 mt-4">Tidak menerima kode OTP?</p>
        <form action="{{ route('verifikasi-akun.resendOtp') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-[#D44040] text-white py-2 rounded-lg hover:bg-red-600 transition mt-2">
                Kirim Ulang
            </button>
        </form>
    </div>
</div>

<script>
    const inputs = document.querySelectorAll(".otp-input");
    const hiddenInput = document.getElementById("otp");
    const form = document.getElementById("otp-form");
    const errorMessage = document.getElementById("error-message");

    // Reset input jika ada error
    if (errorMessage) {
        inputs.forEach(input => input.value = ""); // Kosongkan semua input OTP
        hiddenInput.value = ""; // Reset nilai hidden input
    }

    inputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
            if (e.inputType !== "deleteContentBackward" && input.value) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            }
            updateHiddenInput();
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener("paste", (e) => {
            e.preventDefault();
            const pastedData = e.clipboardData.getData("text").trim().slice(0, 6);
            if (pastedData.length === 6) {
                fillInputs(pastedData);
            }
        });
    });

    function fillInputs(data) {
        inputs.forEach((input, index) => {
            input.value = data[index] || "";
        });
        updateHiddenInput();
    }

    function updateHiddenInput() {
        hiddenInput.value = Array.from(inputs).map(input => input.value).join("");
        if (hiddenInput.value.length === 6) {
            form.submit();
        }
    }
</script>
@endsection
