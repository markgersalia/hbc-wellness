<div class="min-h-screen flex items-center justify-center bg-gray-100 ">
    <div class="w-full max-w-xl p-6 bg-white rounded-lg shadow-md">
        <div class="flex items-center space-x-4 mb-6">
            <img src="{{ asset('images/logo.jpg') }}" alt="Service provider logo"
                class="w-16 h-16 rounded-full object-cover flex-shrink-0">

            <div>
                <h3 class="text-lg font-medium text-gray-900">Schedule Your Service</h3>
                <p class="text-sm text-gray-500">Book therapist.</p>
            </div>
        </div>

        <form wire:submit.prevent="submit" class="space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Select Date</label>
                    <input type="date" wire:model.live="selected_date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                   focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Available Time Slot</label>

<select wire:model.live="time_slot" class="mt-1 block w-full ">
                        <option value="">Select time</option>

                        @foreach ($this->availableTimeSlots as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="service-name" class="block text-sm font-medium text-gray-700">Your Name</label>
                <input type="text" name="service-name" id="service-name" autocomplete="name" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                    placeholder="Your Full Name">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="service-email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="service-email" id="service-email" autocomplete="email" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                        placeholder="you@example.com">
                </div>
                <div>
                    <label for="service-phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" name="service-phone" id="service-phone" autocomplete="tel" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                        placeholder="(123) 456-7890">
                </div>
            </div>
            <div>
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    Schedule Service
                </button>
            </div>
        </form>
    </div>
</div>