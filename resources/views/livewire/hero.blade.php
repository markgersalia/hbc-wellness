<section class="relative h-screen min-h-[700px] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Parallax Effect -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-700"
             style="background-image: url('https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=2070'); transform: scale(1.1);"
             x-data
             @scroll.window="$el.style.transform = `scale(1.1) translateY(${window.scrollY * 0.5}px)`">
        </div>
        <div class="absolute inset-0 bg-linear-to-b from-black/50 via-black/40 to-black/60"></div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 right-20 opacity-20">
        <svg width="120" height="120" viewBox="0 0 120 120" fill="none">
            <circle cx="60" cy="60" r="55" stroke="white" stroke-width="1"/>
            <circle cx="60" cy="60" r="40" stroke="white" stroke-width="1"/>
            <circle cx="60" cy="60" r="25" stroke="white" stroke-width="1"/>
        </svg>
    </div>

    <div class="absolute bottom-20 left-20 opacity-20 hidden lg:block">
        <svg width="100" height="100" viewBox="0 0 100 100" fill="none">
            <path d="M50 10 L90 50 L50 90 L10 50 Z" stroke="white" stroke-width="1" fill="none"/>
            <path d="M50 25 L75 50 L50 75 L25 50 Z" stroke="white" stroke-width="1" fill="none"/>
        </svg>
    </div>

    <!-- Content -->
    <div class="relative z-10 container mx-auto px-6 lg:px-12 text-center">
        <!-- Small decorative flower icon -->
        <div class="mb-6 flex justify-center animate-fade-in">
            <svg class="w-12 h-12 text-primary" viewBox="0 0 50 50" fill="currentColor">
                <circle cx="25" cy="25" r="3"/>
                <circle cx="25" cy="15" r="2.5" opacity="0.8"/>
                <circle cx="25" cy="35" r="2.5" opacity="0.8"/>
                <circle cx="15" cy="25" r="2.5" opacity="0.8"/>
                <circle cx="35" cy="25" r="2.5" opacity="0.8"/>
                <circle cx="18" cy="18" r="2" opacity="0.6"/>
                <circle cx="32" cy="32" r="2" opacity="0.6"/>
                <circle cx="32" cy="18" r="2" opacity="0.6"/>
                <circle cx="18" cy="32" r="2" opacity="0.6"/>
            </svg>
        </div>

        <!-- Subtitle -->
        <p class="text-white/90 text-sm lg:text-base tracking-[0.3em] uppercase font-light mb-6 animate-fade-in-up" style="animation-delay: 0.2s;">
            Welcome to HBC Wellness
        </p>

        <!-- Main Heading -->
        <h1 class="text-5xl md:text-6xl lg:text-8xl font-display font-light text-white mb-8 leading-tight animate-fade-in-up" style="animation-delay: 0.4s;">
            Beauty Centre
            <span class="block italic font-normal">& Spa</span>
        </h1>

        <!-- Description -->
        <div class="max-w-3xl mx-auto mb-12 animate-fade-in-up" style="animation-delay: 0.6s;">
            <p class="text-white/80 text-base lg:text-lg leading-relaxed font-light">
                Your beauty truly matters to us and we know you'll love your stay here at HBC Wellness, 
                <span class="italic">a place of splendor</span> made for you.
            </p>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6 animate-fade-in-up" style="animation-delay: 0.8s;">
            <a href="#services" 
               class="group px-10 py-4 bg-primary text-white text-sm tracking-widest uppercase font-medium hover:bg-white hover:text-dark transition-all duration-500 border-2 border-primary hover:border-white inline-flex items-center">
                Discover More
                <svg class="w-4 h-4 ml-3 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="#booking" 
               class="px-10 py-4 bg-transparent text-white text-sm tracking-widest uppercase font-medium hover:bg-white hover:text-dark transition-all duration-500 border-2 border-white">
                Book Appointment
            </a>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="#about" class="flex flex-col items-center text-white/60 hover:text-white transition-colors">
                <span class="text-xs tracking-widest uppercase mb-2">Scroll</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </a>
        </div>
    </div>
    
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
        opacity: 0;
    }
</style>
</section>

