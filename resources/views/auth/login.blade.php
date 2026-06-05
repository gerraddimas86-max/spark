<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        🔐 Login menggunakan <strong>NIM</strong> (untuk mahasiswa) atau <strong>Email</strong> (untuk mentor/developer)
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- NIM atau Email -->
        <div>
            <x-input-label for="login" :value="__('NIM atau Email')" />
            <x-text-input 
                id="login" 
                class="block mt-1 w-full" 
                type="text" 
                name="login" 
                :value="old('login')" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="Masukkan NIM atau Email"
            />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input 
                id="password" 
                class="block mt-1 w-full" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
                placeholder="Masukkan password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                    name="remember"
                >
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a 
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                    href="{{ route('password.request') }}"
                >
                    {{ __('Lupa password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Login') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Info tambahan -->
    <div class="mt-6 p-4 bg-gray-50 rounded-lg text-sm text-gray-600">
        <p class="font-semibold mb-2">📌 Info Akun Demo:</p>
        <ul class="space-y-1 text-xs">
            <li>🔹 <strong>Developer:</strong> developer@spark.com / password</li>
            <li>🔹 <strong>Mentor:</strong> mentor1@spark.com / password</li>
            <li>🔹 <strong>Mahasiswa:</strong> (NIM yang didaftarkan mentor) / spark123</li>
        </ul>
    </div>
</x-guest-layout>