<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4=" :status="session('status')" />
    <div class="mt-2">
        <div class="flex items-center justify-center w-full max-w-md sm:max-w-lg mx-auto mb-4">
            <img class="h-[30px] w-[60px] sm:h-[50px] sm:w-[100px] mb-2" src="{{ asset('images/logoz.png') }}" alt="Description of Image">
            <a href="/" class="block">
                <h1 class="text-5xl xs:text-4xl sm:text-5xl md:text-5xl font-extrabold bg-gradient-to-r from-teal-800 to-cyan-300 text-transparent bg-clip-text">
                    OffSuppSys
                </h1>
            </a>
        </div>
        
        <form method="POST" action="{{ route('login') }}" class="w-full ">
            @csrf
        
            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input 
                    id="email" 
                    class="block w-full px-4 py-2 text-gray-800 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-0 focus:ring-teal-500 focus:border-teal-500 placeholder-gray-400 transition duration-150 ease-in-out" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    placeholder="example@gmail.com" 
                    required 
                    autofocus 
                    autocomplete="username" 
                />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
            </div>
        
            <!-- Password -->   
            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input 
                    id="password" 
                    class="block w-full px-4 py-2 text-gray-800 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:ring-0 focus:ring-teal-500 focus:border-teal-500 transition duration-150 ease-in-out"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password" 
                />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-600" />
            </div>
        
            <!-- Remember Me -->
            <div class="flex items-center mb-4">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500 transition duration-150 ease-in-out" 
                    name="remember"
                >
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                    {{ __('Remember me') }}
                </label>
            </div>
        
            <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                @if (Route::has('password.request'))
                    <a class="text-sm text-teal-600 hover:text-teal-800 focus:outline-none focus:underline transition duration-150 ease-in-out" 
                    href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
        
                <x-primary-button class="w-50 sm:w-auto px-6 py-2 bg-teal-600 hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-800 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150 ease-in-out">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>