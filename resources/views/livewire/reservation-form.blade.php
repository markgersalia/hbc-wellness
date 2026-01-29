<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Book Your Appointment</h1>
            <p class="text-gray-600">Choose your service and pick a convenient time</p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                @for ($i = 1; $i <= $totalSteps; $i++)
                    <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 {{ $currentStep >= $i ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                @if ($currentStep > $i)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    {{ $i }}
                                @endif
                            </div>
                            <span class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-xs text-gray-600 whitespace-nowrap">
                                @if ($i == 1) Service
                                @elseif ($i == 2) Date & Time
                                @elseif ($i == 3) Details
                                @else Confirm
                                @endif
                            </span>
                        </div>
                        @if ($i < $totalSteps)
                            <div class="flex-1 h-1 mx-2 {{ $currentStep > $i ? 'bg-indigo-600' : 'bg-gray-200' }} transition-all duration-300"></div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 backdrop-blur-sm bg-opacity-90 mt-12">
            @if (!$bookingConfirmed)
                <!-- Step 1: Service Selection -->
                @if ($currentStep === 1)
                    <div class="space-y-4 animate-fade-in">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Select a Service</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($services as $service)
                                <label class="relative cursor-pointer">
                                    <input 
                                        type="radio" 
                                        wire:model.live="selectedService" 
                                        value="{{ $service['id'] }}" 
                                        class="peer sr-only"
                                    >
                                    <div class="p-6 border-2 rounded-xl transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:shadow-md {{ $errors->has('selectedService') ? 'border-red-300' : 'border-gray-200' }}">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-semibold text-gray-900">{{ $service['name'] }}</h3>
                                            <span class="text-indigo-600 font-bold">{{ $service['price'] }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ $service['duration'] }}</p>
                                        <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 transition-all duration-200 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 12 12">
                                                <path d="M10 3L4.5 8.5L2 6"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedService') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                    </div>
                @endif

                <!-- Step 2: Date & Time Selection -->
                @if ($currentStep === 2)
                    <div class="space-y-6 animate-fade-in">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Choose Date & Time</h2>
                        
                        <!-- Date Picker -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                            <input 
                                type="date" 
                                wire:model.live="selectedDate" 
                                min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                            >
                            @error('selectedDate') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Time Slots -->
                        @if ($selectedDate && count($availableTimes) > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Select Time</label>
                                <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach ($availableTimes as $time)
                                        <label class="cursor-pointer">
                                            <input 
                                                type="radio" 
                                                wire:model.live="selectedTime" 
                                                value="{{ $time }}" 
                                                class="peer sr-only"
                                            >
                                            <div class="p-3 text-center border-2 rounded-lg transition-all duration-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 peer-checked:text-white hover:border-indigo-400 border-gray-200">
                                                <span class="font-medium">{{ $time }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('selectedTime') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Step 3: Personal Information -->
                @if ($currentStep === 3)
                    <div class="space-y-6 animate-fade-in">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Your Information</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input 
                                type="text" 
                                wire:model="name" 
                                placeholder="John Doe"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                            >
                            @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input 
                                type="email" 
                                wire:model="email" 
                                placeholder="john@example.com"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                            >
                            @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input 
                                type="tel" 
                                wire:model="phone" 
                                placeholder="+1 (555) 000-0000"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                            >
                            @error('phone') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                            <textarea 
                                wire:model="notes" 
                                rows="4" 
                                placeholder="Any special requests or information we should know..."
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                            ></textarea>
                            @error('notes') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                <!-- Step 4: Review & Confirm (before submission) -->
                @if ($currentStep === 4 && !$bookingConfirmed)
                    <div class="space-y-6 animate-fade-in">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Review Your Booking</h2>
                        
                        <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Service</span>
                                <span class="font-semibold text-gray-900">
                                    @if ($services)
                                        {{ collect($services)->firstWhere('id', $selectedService)['name'] ?? 'N/A' }}                                        
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Date</span>
                                <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Time</span>
                                <span class="font-semibold text-gray-900">{{ $selectedTime }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Name</span>
                                <span class="font-semibold text-gray-900">{{ $name }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Email</span>
                                <span class="font-semibold text-gray-900">{{ $email }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Phone</span>
                                <span class="font-semibold text-gray-900">{{ $phone }}</span>
                            </div>
                        </div>

                        <button 
                            wire:click="submitBooking" 
                            class="w-full bg-indigo-600 text-white py-4 px-6 rounded-xl font-semibold hover:bg-indigo-700 transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl"
                        >
                            Confirm Booking
                        </button>
                    </div>
                @endif

                <!-- Navigation Buttons -->
                @if ($currentStep < 4)
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        @if ($currentStep > 1)
                            <button 
                                wire:click="previousStep" 
                                class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200"
                            >
                                ← Previous
                            </button>
                        @else
                            <div></div>
                        @endif

                        <button 
                            wire:click="nextStep" 
                            class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all duration-200 transform hover:scale-[1.02]"
                        >
                            Continue →
                        </button>
                    </div>
                @endif
            @else
                <!-- Success Message -->
                <div class="text-center py-12 animate-fade-in">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Booking Confirmed! 🎉</h2>
                    <p class="text-gray-600 mb-2">Your appointment has been successfully scheduled.</p>
                    <p class="text-gray-600 mb-8">We've sent a confirmation email to <strong>{{ $email }}</strong></p>
                    
                    <div class="bg-indigo-50 rounded-xl p-6 mb-8 text-left max-w-md mx-auto">
                        <h3 class="font-semibold text-gray-900 mb-4">Appointment Details:</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><span class="font-medium">Service:</span> 
                                  @if ($services)
                                        {{ collect($services)->firstWhere('id', $selectedService)['name'] }}
                                  @else
                                        N/A
                                    @endif
                            </p>
                            <p><span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</p>
                            <p><span class="font-medium">Time:</span> {{ $selectedTime }}</p>
                        </div>
                    </div>

                    <button 
                        wire:click="resetForm" 
                        class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all duration-200"
                    >
                        Book Another Appointment
                    </button>
                </div>
            @endif
        </div>

        <!-- Additional Info -->
        <div class="text-center mt-8 text-gray-600 text-sm">
            <p>Need help? Contact us at <a href="mailto:support@example.com" class="text-indigo-600 hover:text-indigo-700">support@example.com</a></p>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }
    </style>
</div>
