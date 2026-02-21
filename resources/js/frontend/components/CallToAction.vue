<template>
    <section class="relative py-28 overflow-hidden">
        <!-- Background Image -->
        <div 
            class="absolute inset-0 bg-cover bg-center bg-fixed"
            style="background-image: url('https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')"
        ></div>
        
        <!-- Overlay with gradient -->
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/95 via-gray-900/85 to-gray-900/70"></div>

        <!-- Animated Background Shapes -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-10 w-64 h-64 bg-amber-500/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-10 w-96 h-96 bg-amber-400/15 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>

        <!-- Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Text Content -->
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center px-4 py-2 bg-amber-500/30 backdrop-blur-sm text-amber-300 text-sm font-bold rounded-full mb-6">
                        <svg class="w-4 h-4 mr-2 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6.207.293a1 1 0 00-1.414 0l-6 6a1 1 0 101.414 1.414l6-6a1 1 0 000-1.414zM12.5 10a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd"></path>
                        </svg>
                        Limited Time Offer
                    </div>
                    
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Your Dream <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-400">Getaway</span> Awaits
                    </h2>
                    
                    <p class="text-gray-300 text-lg lg:text-xl mb-8 leading-relaxed max-w-xl">
                        Enjoy up to 25% off on all room bookings this season. Experience luxury at its finest with our exclusive packages.
                    </p>

                    <!-- Features Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
                        <div class="flex items-center bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                            <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Free Cancellation</p>
                                <p class="text-gray-400 text-sm">Up to 24 hours before</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                            <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Best Price Guarantee</p>
                                <p class="text-gray-400 text-sm">We match any price</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                            <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Complimentary Breakfast</p>
                                <p class="text-gray-400 text-sm">For 2 guests daily</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10">
                            <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Spa Credit</p>
                                <p class="text-gray-400 text-sm">$50 spa voucher</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <router-link 
                            to="/programs"
                            class="group px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-full hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-xl hover:shadow-2xl hover:shadow-amber-500/30 flex items-center"
                        >
                            <span>Explore Programs</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </router-link>
                        <a 
                            href="tel:+85512345678"
                            class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-bold rounded-full border-2 border-white/30 hover:bg-white hover:text-gray-900 transition-all duration-300 flex items-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Call Us Now
                        </a>
                    </div>
                </div>

                <!-- Promo Card -->
                <div class="lg:col-span-5 hidden lg:block">
                    <div class="relative">
                        <!-- Card Glow -->
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl blur-xl opacity-30"></div>
                        
                        <!-- Main Card -->
                        <div class="relative bg-white rounded-3xl p-8 shadow-2xl transform hover:scale-105 transition-transform duration-500">
                            <!-- Badge -->
                            <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center transform rotate-12 shadow-xl">
                                <div class="text-center -rotate-12">
                                    <div class="text-white text-2xl font-black">25%</div>
                                    <div class="text-white/80 text-xs font-bold">OFF</div>
                                </div>
                            </div>
                            
                            <div class="text-center mb-8">
                                <span class="text-gray-500 text-sm uppercase tracking-wider font-semibold">Exclusive Offer</span>
                                <div class="text-6xl font-black text-gray-900 mt-2">
                                    <span class="text-amber-500">$</span>199
                                </div>
                                <p class="text-gray-500 mt-1">Starting from per night</p>
                            </div>
                            
                            <hr class="border-gray-100 my-6">
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-gray-600 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Valid Until
                                    </span>
                                    <span class="font-bold text-gray-900">March 31, 2026</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-gray-600 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        Promo Code
                                    </span>
                                    <span class="font-mono font-bold text-amber-600 bg-amber-50 px-4 py-1.5 rounded-lg text-sm">LUXURY25</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-gray-600 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                        </svg>
                                        Minimum Stay
                                    </span>
                                    <span class="font-bold text-gray-900">2 Nights</span>
                                </div>
                            </div>
                            
                            <hr class="border-gray-100 my-6">
                            
                            <p class="text-xs text-gray-400 text-center">
                                *Terms and conditions apply. Subject to availability.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
// CTA Section - No additional logic needed
</script>
