<header 
 class="fixed top-0 left-0 right-0 z-50 transition-all duration-500" 
        x-data="{ scrolled: false, mobileOpen: false }"
        @scroll.window="scrolled = window.scrollY > 50"
        :class="scrolled ? 'bg-white shadow-lg' : 'bg-transparent'">
    
    <div class="container mx-auto px-6 lg:px-12">
        <div class="flex items-center justify-between py-6 lg:py-8">
            
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-3 group">
                <div class="relative">
                    <svg class="w-10 h-10 lg:w-12 lg:h-12 transition-transform group-hover:scale-110" viewBox="0 0 50 50" fill="none">
                        <circle cx="25" cy="25" r="23" stroke="#B8936D" stroke-width="2" opacity="0.3"/>
                        <circle cx="25" cy="25" r="18" stroke="#B8936D" stroke-width="2"/>
                        <path d="M25 10 L25 40 M10 25 L40 25" stroke="#B8936D" stroke-width="1.5"/>
                        <circle cx="25" cy="25" r="3" fill="#B8936D"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-display font-semibold tracking-wide"
                        :class="scrolled ? 'text-dark' : 'text-white'">
                        HBC Wellness
                    </h1>
                    <p class="text-xs tracking-widest uppercase" 
                       :class="scrolled ? 'text-secondary' : 'text-white/80'">
                        Beauty & Spa
                    </p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center space-x-10">
                <a href="/" 
                   class="text-sm tracking-wider uppercase font-medium hover:text-primary transition-colors"
                   :class="scrolled ? 'text-dark' : 'text-white'">
                    Home
                </a>
                <a href="#about" 
                   class="text-sm tracking-wider uppercase font-medium hover:text-primary transition-colors"
                   :class="scrolled ? 'text-dark' : 'text-white'">
                    About
                </a>
                <a href="#services" 
                   class="text-sm tracking-wider uppercase font-medium hover:text-primary transition-colors"
                   :class="scrolled ? 'text-dark' : 'text-white'">
                    Services
                </a>
                <a href="#treatments" 
                   class="text-sm tracking-wider uppercase font-medium hover:text-primary transition-colors"
                   :class="scrolled ? 'text-dark' : 'text-white'">
                    Treatments
                </a>
                <a href="#testimonials" 
                   class="text-sm tracking-wider uppercase font-medium hover:text-primary transition-colors"
                   :class="scrolled ? 'text-dark' : 'text-white'">
                    Testimonials
                </a>
                <a href="#contact" 
                   class="text-sm tracking-wider uppercase font-medium hover:text-primary transition-colors"
                   :class="scrolled ? 'text-dark' : 'text-white'">
                    Contact
                </a>
            </nav>

            <!-- CTA Button -->
            <div class="hidden lg:block">
                <a href="#booking" 
                   class="px-8 py-3 bg-primary text-white text-sm tracking-wider uppercase font-medium hover:bg-secondary transition-all duration-300 inline-block border-2 border-primary hover:border-secondary">
                    Book Now
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button @click="mobileOpen = !mobileOpen" 
                    class="lg:hidden p-2"
                    :class="scrolled ? 'text-dark' : 'text-white'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="lg:hidden pb-6 bg-white rounded-b-xl shadow-xl"
             style="display: none;">
            <nav class="flex flex-col space-y-4 px-6">
                <a href="/" class="text-sm tracking-wider uppercase font-medium text-dark hover:text-primary transition-colors py-2 border-b border-light">Home</a>
                <a href="#about" class="text-sm tracking-wider uppercase font-medium text-dark hover:text-primary transition-colors py-2 border-b border-light">About</a>
                <a href="#services" class="text-sm tracking-wider uppercase font-medium text-dark hover:text-primary transition-colors py-2 border-b border-light">Services</a>
                <a href="#treatments" class="text-sm tracking-wider uppercase font-medium text-dark hover:text-primary transition-colors py-2 border-b border-light">Treatments</a>
                <a href="#testimonials" class="text-sm tracking-wider uppercase font-medium text-dark hover:text-primary transition-colors py-2 border-b border-light">Testimonials</a>
                <a href="#contact" class="text-sm tracking-wider uppercase font-medium text-dark hover:text-primary transition-colors py-2">Contact</a>
                <a href="#booking" class="mt-4 px-6 py-3 bg-primary text-white text-sm tracking-wider uppercase font-medium hover:bg-secondary transition-all text-center">
                    Book Now
                </a>
            </nav>
        </div>
    </div>
</header>
