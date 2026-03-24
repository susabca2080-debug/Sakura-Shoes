<?php include 'header.php'; ?>

   <main>
       About Section
    <section id="about" class="py-20 bg-slate-900 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Content -->
                <div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
                        About <span class="text-amber-400">Sakura Shoe Center</span>
                    </h2>
                    
                    <p class="text-xl text-slate-300 mb-8 leading-relaxed">
                        For over 25 years, Sakura Shoe Center has been the premier destination for 
                        high-quality footwear. We pride ourselves on offering an extensive collection 
                        of shoes that combine style, comfort, and durability.
                    </p>
                    
                    <p class="text-slate-300 mb-8 leading-relaxed">
                        From formal business shoes to casual everyday wear, from elegant women's heels 
                        to fun and durable kids' footwear, we have something for every member of your 
                        family. Our experienced staff is dedicated to helping you find the perfect fit 
                        and style for any occasion.
                    </p>

                    <div class="grid sm:grid-cols-2 gap-8">
                        <div class="flex items-start space-x-4">
                            <div class="bg-amber-500 p-3 rounded-lg flex-shrink-0">
                                <span class="text-white">🏆</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-2">Premium Quality</h3>
                                <p class="text-slate-400 text-sm">Every shoe is crafted with the finest materials and attention to detail</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-amber-500 p-3 rounded-lg flex-shrink-0">
                                <span class="text-white">🛡️</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-2">Warranty Protection</h3>
                                <p class="text-slate-400 text-sm">All products come with comprehensive warranty coverage</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-amber-500 p-3 rounded-lg flex-shrink-0">
                                <span class="text-white">🚚</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-2">Free Delivery</h3>
                                <p class="text-slate-400 text-sm">Complimentary shipping on orders over $75</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-amber-500 p-3 rounded-lg flex-shrink-0">
                                <span class="text-white">⏰</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-2">Quick Service</h3>
                                <p class="text-slate-400 text-sm">Fast processing and same-day pickup available</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="relative">
                    <div class="relative overflow-hidden rounded-2xl">
                        <img src="https://images.pexels.com/photos/1240892/pexels-photo-1240892.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Shoe Store Interior" class="w-full h-[600px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="absolute bottom-8 left-8 right-8">
                        <div class="bg-white/95 backdrop-blur-sm rounded-xl p-6">
                            <div class="grid grid-cols-3 gap-4 text-slate-900">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-amber-600">25+</p>
                                    <p class="text-sm">Years Experience</p>
                                </div>
                                <div class="text-center border-l border-r border-slate-200">
                                    <p class="text-2xl font-bold text-amber-600">700+</p>
                                    <p class="text-sm">Shoe Styles</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-amber-600">5000+</p>
                                    <p class="text-sm">Happy Customers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>
    

<?php include 'footer.php'; ?>