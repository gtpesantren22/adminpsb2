<div>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">

        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl">

            <div>
                <div class="flex justify-center">
                    <div class="bg-gradient-to-r from-primary-blue to-secondary-blue p-3 rounded-xl">
                        <i class="fas fa-user-shield text-white text-3xl"></i>
                    </div>
                </div>

                <h2 class="mt-6 text-center text-3xl font-bold gradient-text">
                    Login page
                </h2>

                <p class="mt-2 text-center text-medium-gray text-sm">
                    Masukkan kredensial untuk masuk ke sistem
                </p>
            </div>

            {{-- FORM LOGIN LIVEWIRE --}}
            <form wire:submit.prevent="masuk" class="mt-8 space-y-6">

                <div class="rounded-md  -space-y-px">

                    {{-- EMAIL --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-dark-gray mb-1">
                            Email
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-light-gray"></i>
                            </div>

                            <input type="email" wire:model.defer="email" required placeholder="Masukkan email"
                                class="appearance-none rounded-lg relative block w-full pl-10 pr-3 py-3
                                   border border-gray-300 placeholder-gray-400 text-gray-900
                                   focus:outline-none focus:ring-2 focus:ring-secondary-blue
                                   focus:border-transparent transition duration-150">
                        </div>

                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div>
                        <label class="block text-sm font-medium text-dark-gray mb-1">
                            Password
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-light-gray"></i>
                            </div>

                            <input type="password" wire:model.defer="password" required placeholder="Masukkan password"
                                class="appearance-none rounded-lg relative block w-full pl-10 pr-3 py-3
                                   border border-gray-300 placeholder-gray-400 text-gray-900
                                   focus:outline-none focus:ring-2 focus:ring-secondary-blue
                                   focus:border-transparent transition duration-150">
                        </div>

                        @error('password')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ERROR GLOBAL --}}
                @if ($errorMessage)
                    <div class="text-sm text-red-600 text-center">
                        {{ $errorMessage }}
                    </div>
                @endif

                {{-- REMEMBER --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox"
                            class="h-4 w-4 text-secondary-blue focus:ring-secondary-blue border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-dark-gray">
                            Ingat saya
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#"
                            class="font-medium text-secondary-blue hover:text-primary-blue transition duration-150">
                            Lupa password?
                        </a>
                    </div>
                </div>

                {{-- BUTTON --}}
                <button type="submit" wire:loading.attr="disabled"
                    class="group relative w-full flex justify-center items-center gap-2 py-3 px-4
                        border border-transparent text-sm font-medium rounded-lg text-white
                        bg-gradient-to-r from-primary-blue to-secondary-blue
                        hover:from-primary-blue hover:to-primary-blue
                        focus:outline-none focus:ring-2 focus:ring-offset-2
                        focus:ring-secondary-blue shadow-md hover:shadow-lg transition duration-150">

                    <!-- SPINNER -->
                    <svg wire:loading wire:target="masuk" class="w-5 h-5 animate-spin text-white" viewBox="0 0 24 24"
                        fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                    </svg>

                    <!-- TEXT -->
                    <span wire:loading.remove wire:target="masuk">
                        Masuk ke Sistem
                    </span>

                </button>


            </form>
        </div>
    </div>

</div>
