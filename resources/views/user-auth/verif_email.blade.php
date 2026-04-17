<link rel="shortcut icon" href="{{ asset('assets/ukpm-explant-ic.png') }}" type="image/png">
@extends('layouts.auth-layout')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12">
        <div class="max-w-md w-full bg-white p-10 rounded-lg shadow-lg">
            <!-- Judul -->
            <h2 class="text-3xl font-bold text-center text-[#D44040] mb-4">Verifikasi OTP</h2>
            <p class="text-center text-gray-600 mb-4 text-sm">
                Silahkan buka email anda dan konfirmasi <br>
                Kami telah mengirimkan kode OTP ke email <br>
                <span class="font-bold text-black">{{ session('email') }}</span>
            </p>

            <!-- Notifikasi -->
            @if (session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- OTP Input -->
            <form action="{{ route('password.verifyOtp') }}" method="POST">
                @csrf
                <div class="flex justify-center space-x-2 mb-8 mt-4">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" name="otp[]"
                            class="w-12 h-12 border rounded-lg text-center text-xl focus:outline-none focus:ring-2 focus:ring-[#D44040]"
                            maxlength="1" pattern="[0-9]" required oninput="moveToNext(this, {{ $i }})"
                            onkeydown="handleBackspace(event, {{ $i }})">
                    @endfor
                </div>
            </form>

            <!-- Kirim Ulang -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600 mb-3">Tidak menerima kode OTP?</p>
                <form action="{{ route('password.resendOtp') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-64 bg-[#D44040] text-white py-3 rounded-lg hover:bg-red-700 transition">
                        Kirim Ulang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function moveToNext(element, index) {
            let inputs = document.querySelectorAll('input[name="otp[]"]');
            if (element.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            if (index === inputs.length - 1 && element.value.length === 1) {
                document.querySelector('form').submit();
            }
        }

        function handleBackspace(event, index) {
            let inputs = document.querySelectorAll('input[name="otp[]"]');
            if (event.key === "Backspace" && index > 0 && inputs[index].value === '') {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            let inputs = document.querySelectorAll('input[name="otp[]"]');

            inputs.forEach((input, index) => {
                input.addEventListener("input", function (event) {
                    let value = event.target.value;

                    // Jika ada lebih dari satu karakter (misalnya saat paste)
                    if (value.length > 1) {
                        let values = value.split("");
                        for (let i = 0; i < values.length; i++) {
                            if (i < inputs.length) {
                                inputs[i].value = values[i];
                            }
                        }
                        inputs[inputs.length - 1].focus(); // Fokus ke input terakhir
                    } else {
                        if (value.length === 1 && index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    }

                    // Jika semua input terisi, submit form otomatis
                    if ([...inputs].every(input => input.value.length === 1)) {
                        document.querySelector('form').submit();
                    }
                });

                input.addEventListener("paste", function (event) {
                    event.preventDefault();
                    let pastedData = (event.clipboardData || window.clipboardData).getData("text");

                    if (/^\d{6}$/.test(pastedData)) { // Pastikan hanya angka 6 digit
                        let values = pastedData.split("");
                        values.forEach((char, i) => {
                            if (i < inputs.length) {
                                inputs[i].value = char;
                            }
                        });
                        inputs[inputs.length - 1].focus(); // Fokus ke input terakhir

                        // Submit otomatis setelah paste
                        document.querySelector('form').submit();
                    }
                });

                input.addEventListener("keydown", function (event) {
                    if (event.key === "Backspace" && index > 0 && input.value === '') {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                    }
                });
            });
        });
    </script>

@endsection
