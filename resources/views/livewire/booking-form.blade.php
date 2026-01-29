<div>
    <div class="max-w-5xl mx-auto">
        
        @if (!$bookingConfirmed)
            <!-- Progress Steps -->
            <div class="mb-16">
                <div class="flex items-center justify-center">
                    @for ($i = 1; $i <= $totalSteps; $i++)
                        <div class="flex items-center">
                            <!-- Step Circle -->
                            <div class="relative">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-500 border-2 
                                    {{ $currentStep >= $i ? 'bg-primary border-primary text-white' : 'bg-white border-gray-300 text-gray-400' }}">
                                    @if ($currentStep > $i)
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <span class="font-display font-semibold">{{ $i }}</span>
                                    @endif
                                </div>
                                <!-- Step Label -->
                                <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs uppercase tracking-wider whitespace-nowrap
                                    {{ $currentStep >= $i ? 'text-primary font-medium' : 'text-gray-400' }}">
                                    @if ($i == 1) Service
                                    @elseif ($i == 2) Date & Time
                                    @elseif ($i == 3) Your Details
                                    @else Confirm
                                    @endif
                                </span>
                            </div>
                            
                            <!-- Connecting Line -->
                            @if ($i < $totalSteps)
                                <div class="w-24 h-0.5 mx-4 transition-all duration-500 {{ $currentStep > $i ? 'bg-primary' : 'bg-gray-300' }}"></div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-2xl p-8 lg:p-12 mt-12">
                
                <!-- Step 1: Service Selection -->
                @if ($currentStep === 1)
                    <div class="space-y-8">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl lg:text-4xl font-display font-light text-dark mb-3">Select Your Service</h2>
                            <p class="text-gray-600">Choose the treatment that's perfect for you</p>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            @foreach ($services as $service)
                                <label class="relative cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        wire:model.live="selectedService" 
                                        value="{{ $service['id'] }}" 
                                        class="peer sr-only">
                                    
                                    <div class="p-6 border-2 transition-all duration-300 
                                        peer-checked:border-primary peer-checked:bg-primary/5 hover:shadow-lg
                                        {{ $errors->has('selectedService') ? 'border-red-300' : 'border-gray-200' }}">
                                        
                                        <div class="flex items-start justify-between mb-3">
                                            <h3 class="font-display text-xl text-dark group-hover:text-primary transition-colors">
                                                {{ $service['name'] }}
                                            </h3>
                                            <span class="text-primary font-semibold text-lg">{{ $service['price'] }}</span>
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 mb-3">{{ $service['duration'] }}</p>
                                        
                                        <!-- Check Mark -->
                                        <div class="absolute top-4 right-4 w-6 h-6 rounded-full border-2 transition-all duration-300
                                            peer-checked:border-primary peer-checked:bg-primary border-gray-300 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedService') 
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p> 
                        @enderror
                    </div>
                @endif

                <!-- Step 2: Date & Time Selection -->
                @if ($currentStep === 2)
                    <div class="space-y-8">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl lg:text-4xl font-display font-light text-dark mb-3">Choose Date & Time</h2>
                            <p class="text-gray-600">Select your preferred appointment slot</p>
                        </div>
                        
                        <!-- Date Picker -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3 uppercase tracking-wider">
                                Select Date
                            </label>
                            <input 
                                type="date" 
                                wire:model.live="selectedDate" 
                                min="{{ date('Y-m-d') }}"
                                class="w-full px-6 py-4 border-2 border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 text-lg">
                            @error('selectedDate') 
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Time Slots -->
                        @if ($selectedDate && count($availableTimes) > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-4 uppercase tracking-wider">
                                    Select Time
                                </label>
                                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                    @foreach ($availableTimes as $time)
                                        <label class="cursor-pointer">
                                            <input 
                                                type="radio" 
                                                wire:model.live="selectedTime" 
                                                value="{{ $time }}" 
                                                class="peer sr-only">
                                            <div class="p-4 text-center border-2 transition-all duration-300
                                                peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white 
                                                hover:border-primary/50 border-gray-200">
                                                <span class="font-medium">{{ $time }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('selectedTime') 
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p> 
                                @enderror
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Step 3: Personal Information -->
                @if ($currentStep === 3)
                    <div class="space-y-6">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl lg:text-4xl font-display font-light text-dark mb-3">Your Information</h2>
                            <p class="text-gray-600">Please provide your contact details</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 uppercase tracking-wider">
                                Full Name *
                            </label>
                            <input 
                                type="text" 
                                wire:model="name" 
                                placeholder="John Doe"
                                class="w-full px-6 py-4 border-2 border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                            @error('name') 
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 uppercase tracking-wider">
                                Email Address *
                            </label>
                            <input 
                                type="email" 
                                wire:model="email" 
                                placeholder="john@example.com"
                                class="w-full px-6 py-4 border-2 border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                            @error('email') 
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 uppercase tracking-wider">
                                Phone Number *
                            </label>
                            <input 
                                type="tel" 
                                wire:model="phone" 
                                placeholder="+1 (555) 000-0000"
                                class="w-full px-6 py-4 border-2 border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
                            @error('phone') 
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 uppercase tracking-wider">
                                Additional Notes (Optional)
                            </label>
                            <textarea 
                                wire:model="notes" 
                                rows="4" 
                                placeholder="Any special requests or information we should know..."
                                class="w-full px-6 py-4 border-2 border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200"></textarea>
                            @error('notes') 
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>
                @endif

                <!-- Step 4: Review & Confirm -->
                @if ($currentStep === 4 && !$bookingConfirmed)
                    <div class="space-y-8">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl lg:text-4xl font-display font-light text-dark mb-3">Review Your Booking</h2>
                            <p class="text-gray-600">Please confirm your appointment details</p>
                        </div>
                        
                        <div class="bg-light p-8 space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-300">
                                <span class="text-gray-600 uppercase tracking-wider text-sm">Service</span>
                                <span class="font-display text-lg text-dark">
                                    {{ collect($services)->firstWhere('id', $selectedService)['name'] ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-300">
                                <span class="text-gray-600 uppercase tracking-wider text-sm">Date</span>
                                <span class="font-display text-lg text-dark">{{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-300">
                                <span class="text-gray-600 uppercase tracking-wider text-sm">Time</span>
                                <span class="font-display text-lg text-dark">{{ $selectedTime }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-300">
                                <span class="text-gray-600 uppercase tracking-wider text-sm">Name</span>
                                <span class="font-display text-lg text-dark">{{ $name }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-gray-300">
                                <span class="text-gray-600 uppercase tracking-wider text-sm">Email</span>
                                <span class="font-display text-lg text-dark">{{ $email }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 uppercase tracking-wider text-sm">Phone</span>
                                <span class="font-display text-lg text-dark">{{ $phone }}</span>
                            </div>
                        </div>

                        <button 
                            wire:click="submitBooking" 
                            class="w-full bg-primary text-white py-5 px-8 text-sm tracking-widest uppercase font-medium hover:bg-secondary transition-all duration-300 transform hover:scale-[1.02] shadow-lg">
                            Confirm Booking
                        </button>
                    </div>
                @endif

                <!-- Navigation Buttons -->
                @if ($currentStep < 4)
                    <div class="flex justify-between items-center mt-10 pt-8 border-t border-gray-200">
                        @if ($currentStep > 1)
                            <button 
                                wire:click="previousStep" 
                                class="px-8 py-3 border-2 border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-all duration-200 uppercase tracking-wider text-sm">
                                ← Previous
                            </button>
                        @else
                            <div></div>
                        @endif

                        <button 
                            wire:click="nextStep" 
                            class="px-10 py-3 bg-primary text-white font-medium hover:bg-secondary transition-all duration-300 transform hover:scale-[1.02] uppercase tracking-wider text-sm">
                            Continue →
                        </button>
                    </div>
                @endif
            </div>
        @else
            <!-- Success Message -->
            <div class="text-center py-16 bg-white shadow-2xl px-8">
                <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <h2 class="text-4xl font-display font-light text-dark mb-4">Booking Confirmed!</h2>
                <p class="text-gray-600 mb-2 text-lg">Your appointment has been successfully scheduled.</p>
                <p class="text-gray-600 mb-10">We've sent a confirmation email to <strong class="text-primary">{{ $email }}</strong></p>
                
                <div class="bg-light p-8 mb-10 text-left max-w-md mx-auto">
                    <h3 class="font-display text-xl text-dark mb-6 text-center">Appointment Details</h3>
                    <div class="space-y-3 text-gray-700">
                        <div class="flex justify-between">
                            <span class="font-medium">Service:</span>
                            <span>{{ collect($services)->firstWhere('id', $selectedService)['name'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Date:</span>
                            <span>{{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Time:</span>
                            <span>{{ $selectedTime }}</span>
                        </div>
                    </div>
                </div>

                <button 
                    wire:click="resetForm" 
                    class="px-10 py-4 bg-primary text-white font-medium hover:bg-secondary transition-all duration-300 uppercase tracking-wider text-sm">
                    Book Another Appointment
                </button>
            </div>
        @endif
    </div>
</div>
