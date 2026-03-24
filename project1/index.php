<?php include 'header.php'; ?>
<!-- Hero Section -->
    <section id="home" class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">
        <div class="absolute inset-0 bg-black/20"></div>
        
        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Content -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-2 max-w-max ">
                        <span class="text-sm font-medium maincolour">⭐⭐⭐⭐⭐ Premium Quality Since 1995</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                        Step Into
                        <span class="block text-amber-400">Premium Quality</span>
                        <span class="block">Footwear</span>
                    </h1>
                    
                    <p class="text-xl text-slate-300 max-w-lg leading-relaxed">
                        Discover our extensive collection of high-quality shoes for men, women, and kids. 
                        Experience comfort, style, and durability in every step.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="products.php" id="explore-collection" role="button" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-flex items-center cursor-pointer">
                            Explore Products →
                        </a>
                        
                        <?php
                      echo '<a href="javascript:void(0);" onclick="window.open(\'https://maps.app.goo.gl/hHX4Ps8LdPTdG3FeA\', \'_blank\', \'width=800,height=600\');" id="visit-store" role="button" class="border-2 border-amber-400 text-amber-400 hover:bg-amber-400 hover:text-slate-900 px-8 py-4 rounded-lg font-semibold transition-all duration-300">
                          Visit Store
                      </a>';
                      ?>
                    </div>
                </div>
                
                <!-- Hero Image -->
                <div class="relative">
                    <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                        <img src="https://images.pexels.com/photos/1240892/pexels-photo-1240892.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Premium Shoes Collection" class="w-full h-[500px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                    </div>
                    
                    <!-- Floating Card -->
                    <div class="absolute -bottom-6 -left-6 bg-white text-slate-900 p-6 rounded-xl shadow-xl">
                        <div class="flex items-center space-x-4">
                            <div class="bg-amber-100 p-3 rounded-lg">
                                <span class="text-amber-600 font-bold">🛍️</span>
                            </div>
                            <div>
                                <p class="font-bold text-lg">1000+</p>
                                <p class="text-sm text-slate-600">Happy Customers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>  
                   

    
<!-- sale -->
 <!-- Eye-Catching Sale Section -->
    <section id="SALE" class="py-20 bg-gradient-to-r from-red-400 via-red-500 to-orange-600 relative overflow-hidden">
        <!-- Animated background shapes -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/10 rounded-full blur-3xl translate-y-1/2"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
                    Mega Sale: Up to 50% Off!
                </h2>
                <p class="text-xl text-white/90 max-w-2xl mx-auto">
                    Don't miss out on our limited-time offers on premium footwear for the whole family. Shop now and step up your style!
                </p>
            </div>
            <div class="flex justify-center">
                <a href="saleproduct.php" id="explore-collection" role="button" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-flex items-center cursor-pointer">
                            Shop Now →
                        </a>
                        
            </div>
</div>
    </section>
   
    <!-- Featured Products -->
                        <section id="featured" class="py-20 bg-white">
                            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                                <?php include 'crudop/function.php';?>
                                <div class="text-center mb-16">
                                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 mb-4">
                                        Featured Products
                                    </h2>
                                    <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                                        Our most popular and highest-rated shoes, loved by customers worldwide
                                    </p>
                                </div>
                                <!-- Horizontal scroll panel for featured products + "View All" card -->
                                <div class="relative">
                                    <!-- Left/Right buttons -->
                                    <button aria-label="Scroll left" onclick="document.getElementById('featured-scroll').scrollBy({ left: -400, behavior: 'smooth' })" class="hidden md:flex items-center justify-center absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 z-20 h-10 w-10 rounded-full  shadow-md text-slate-700  transition-all">
                                        ←
                </button>
                <button aria-label="Scroll right" onclick="document.getElementById('featured-scroll').scrollBy({ left: 400, behavior: 'smooth' })" class="hidden md:flex items-center justify-center absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 z-20 h-10 w-10 rounded-full shadow-md text-slate-700   transition-all">
                    →
                </button>

                <!-- Scroll container (Horizontal) -->
                <div id="featured-scroll" class="overflow-x-auto overflow-y-hidden no-scrollbar snap-x snap-mandatory flex gap-4 px-2 py-4">
                    <?php if(isset($conn)) { showfeatures($conn); } else { echo '<p>Database connection error</p>'; } ?>
                    <!-- View All card -->
                    <a href="products.php" class="snap-start flex-shrink-0 w-72 md:w-80 flex items-center justify-center bg-amber-50 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                        <div class="p-6 text-center">
                            <p class="text-sm text-amber-600 font-medium">Explore More</p>
                            <h4 class="font-bold text-amber-700 text-xl mt-2">View All Featured</h4>
                            <p class="text-sm text-slate-600 mt-2">See the full collection</p>
                            <div class="mt-4">
                                <button class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition-colors">View All</button>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Scrollbar styling (keep scrollbars visible) -->
            <style>
                .no-scrollbar {
                    -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
                }
            </style>
        </div>
    </section>

    

    <!-- Categories Section -->
    <section id="categories" class="py-20 bg-slate-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 mb-4">
                    Shop by Category
                </h2>
                <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                    Discover our carefully curated collections designed for every member of your family
                </p>
            </div>


            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Men's Collection -->
                <a href="mensproduct.php" class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 block">
                    <div class="relative overflow-hidden">
                        <img src="https://images.pexels.com/photos/1478442/pexels-photo-1478442.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Men's Collection" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300"></div>
                        
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-3 rounded-lg">
                            <span class="text-amber-600">👨</span>
                        </div>
                        
                        <div class="absolute bottom-4 right-4 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            250+ Styles
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Men's Collection</h3>
                        <p class="text-slate-600 mb-4">Professional and casual shoes crafted for the modern man</p>
                        
                        <button class="w-full bg-slate-900 hover:bg-amber-600 text-white py-3 rounded-lg font-semibold transition-colors duration-300 group-hover:bg-amber-500">
                            View Collection
                        </button>
                    </div>
                </a>

                <!-- Women's Collection -->
                <a href="womensproduct.php" class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                    <div class="relative overflow-hidden">
                        <img src="https://images.pexels.com/photos/1449667/pexels-photo-1449667.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Women's Collection" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300"></div>
                        
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-3 rounded-lg">
                            <span class="text-amber-600">👩</span>
                        </div>
                        
                        <div class="absolute bottom-4 right-4 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            300+ Styles
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Women's Collection</h3>
                        <p class="text-slate-600 mb-4">Elegant and comfortable footwear for every occasion</p>
                        
                        <button class="w-full bg-slate-900 hover:bg-amber-600 text-white py-3 rounded-lg font-semibold transition-colors duration-300 group-hover:bg-amber-500">
                            View Collection
                        </button>
                    </div>
                 </a>

                <!-- Kids' Collection -->
                <a href="kidsproduct.php" class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 block">
                    <div class="relative overflow-hidden">
                        <img src="https://images.pexels.com/photos/1670766/pexels-photo-1670766.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Kids' Collection" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300"></div>
                        
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-3 rounded-lg">
                            <span class="text-amber-600">👶</span>
                        </div>
                        
                        <div class="absolute bottom-4 right-4 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            150+ Styles
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Kids' Collection</h3>
                        <p class="text-slate-600 mb-4">Fun, durable, and comfortable shoes for growing feet</p>
                        
                        <button class="w-full bg-slate-900 hover:bg-amber-600 text-white py-3 rounded-lg font-semibold transition-colors duration-300 group-hover:bg-amber-500">
                            View Collection
                        </button>
                    </div>
                </a>
                <a href="unisexproducts.php" class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 block">
                    <div class="relative overflow-hidden">
                        <img src="https://images.pexels.com/photos/1670766/pexels-photo-1670766.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Kids' Collection" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors duration-300"></div>
                        
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-3 rounded-lg">
                            <span class="text-amber-600">👨👩</span>
                        </div>
                        
                        <div class="absolute bottom-4 right-4 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            150+ Styles
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">unisex Collection</h3>
                        <p class="text-slate-600 mb-4">shose that are suitable for all genders</p>
                        
                        <button class="w-full bg-slate-900 hover:bg-amber-600 text-white py-3 rounded-lg font-semibold transition-colors duration-300 group-hover:bg-amber-500">
                            View Collection
                        </button>
                    </div>
                </a>
            </div>
        </div>
    </section>
    

    <!-- Footer -->
     <?php include 'footer.php'; ?>
</body>
</html>