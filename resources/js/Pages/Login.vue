<template>
    <div >
		<!-- Modal registration form -->
		<div class="fixed z-50 inset-0 overflow-y-auto" v-show="isRegister" @keydown.escape.prevent.stop="isRegister = false" x-transition>
			<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

			<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
				<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
			</div>

			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

				<div
					class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
					role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
					<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
					<button type="button" @click="isRegister = false"
						class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						<span class="sr-only">Close</span>
						<!-- Heroicon name: outline/x -->
						<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
						stroke="currentColor" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
					</div>

					<div class="sm:flex sm:items-start">
						<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
							<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
								Create an account
							</h3>
						</div>
					</div>
					<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
						<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
							
							<div class="relative mb-2">
								<label for="account-type" class="leading-7 text-sm text-gray-600">Account Type</label>
								<select v-model="formRegisterData.role" id="account-type" class="mt-1 block w-full py-2 px-3 border border-yellow-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm enabled:hover:border-yellow-500 disabled:opacity-50">
									<option value="3">Customer</option>
									<option value="2">Local Food Joints</option>
								</select>
							</div>
							<div class="relative mb-2" v-if="formRegisterData.role == 2">
								<label for="food-joint-name" class="leading-7 text-sm text-gray-600">Food Joint Name</label>
                            	<input type="text" id="food-joint-name" name="food-joint-name" placeholder="Food Joint Name" v-model="formRegisterData.restaurant_name" required class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('restaurant_name', saveError)}}
								</span>
							</div>
							<div class="relative mb-2">
								<label for="name" class="leading-7 text-sm text-gray-600" v-if="formRegisterData.role == 2">Owner Name</label>
								<label for="name" class="leading-7 text-sm text-gray-600" v-else>Name</label>
                            	<input type="text" id="name" name="name" :placeholder="formRegisterData.role == 2 ? 'Owner Name' : 'Name'" required v-model="formRegisterData.name" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('name', saveError)}} 
								</span>
							</div>
							<div class="relative mb-2">
								<label for="registration-email" class="leading-7 text-sm text-gray-600">Email</label>
                            	<input type="email" id="registration-email" name="Email" placeholder="Email" required v-model="formRegisterData.email" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('email', saveError)}}
								</span>
							</div>
							<div class="relative mb-2">
								<label for="phone" class="leading-7 text-sm text-gray-600">Contact Number</label>
                            	<input type="text" id="phone" name="contact" placeholder="Contact Number" required v-model="formRegisterData.phone" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('phone', saveError)}}
								</span>
							</div>
							<div class="relative mb-2">
								<label for="address" class="leading-7 text-sm text-gray-600">Address</label>
                            	<input type="text" id="address" name="address" placeholder="Street, Barangay, Town" required v-model="formRegisterData.address" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('address', saveError)}}
								</span>
							</div>
							<div class="relative mb-2">
								<label for="password" class="leading-7 text-sm text-gray-600">Password</label>
                            	<input type="password" id="password" name="password" placeholder="Password" required v-model="formRegisterData.password" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('password', saveError)}}
								</span>
							</div>
							<div class="relative mb-2">
								<label for="confirm-password" class="leading-7 text-sm text-gray-600">Confirm Password</label>
                            	<input type="password" id="confirm-password" name="password" placeholder="Confirm Password" required v-model="formRegisterData.confirm_password" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('confirm_password', saveError)}}
								</span>
							</div>
							<div class="relative mb-2">
								<label for="id-picture" class="leading-7 text-sm text-gray-600">ID Picture</label>
                            	<input type="file" id="id-picture" accept="image/png, image/jpeg" @change="imageChange('picture_id', $event)" multiple placeholder="Upload Id" required class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
								<span class="text-xs text-gray-600">Note: Please upload front and a selfie holding ID picture.  </span>
								<span class="text-red-500 text-xs ml-2">
									{{validationError('picture_id', saveError)}}
								</span>

								<div class="w-full flex flex-row mt-2" v-if="ids.length > 0">
									<div class="rounded-lg p-4 col-span-4 sm:col-span-4" v-for="i in ids" :key="i">
										<img :src="i.image" style="width: 100px; height: 100px" class="object-center w-full h-full block"/>
									</div>
									<span class="float-right mr-2 mt-4 cursor-pointer text-red-600" @click="removeIds()">
										<i class="fa-solid fa-trash"></i>
									</span>
								</div>
							</div>
							<div v-if="formRegisterData.role == 2">
								<div class="relative mb-2">
									<label for="food-joint-display-picture" class="leading-7 text-sm text-gray-600">Local Food Joint Display Picture</label>
									<input type="file" id="food-joint-display-picture" accept="image/png, image/jpeg" @change="imageChange('image', $event)" required class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
									<span class="text-red-500 text-xs ml-2">
										{{validationError('image', saveError)}}
									</span>
								</div>
								<div class="relative mb-2">
									<label for="food-joint-banner" class="leading-7 text-sm text-gray-600">Local Food Joint Banner</label>
									<input type="file" id="food-joint-banner" accept="image/png, image/jpeg" @change="imageChange('banner', $event)" required class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
									<span class="text-red-500 text-xs ml-2">
										{{validationError('banner', saveError)}}
									</span>
								</div>
								<div class="relative mb-2">
									<label for="food-joint-permit" class="leading-7 text-sm text-gray-600">Local Food Joint Business Permit</label>
									<input type="file" id="food-joint-permit" accept="image/png, image/jpeg" @change="imageChange('permit', $event)" required class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
									<span class="text-red-500 text-xs ml-2">
										{{validationError('permit', saveError)}}
									</span>
								</div>
							</div>
							<div class="relative mb-2">
							<input type="checkbox" :value="value" v-model="termsAndCondition" class="rounded border-yellow-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
								<label class="leading-7 text-sm text-gray-600">
								I have read, fully understood and agreed with the
								<a target="_blank" href="/terms-and-conditions" class="text-indigo-600 font-medium">Terms and Conditions</a>
								</label>
							</div>

						</div>
					</div>
					<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
						<button class="w-full inline-flex justify-center ml-2 mb-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm"
							@click="sendRegisterVerification();"
							:disabled="!termsAndCondition"
							:class="{ 'cursor-not-allowed' : !termsAndCondition }"
						>
							Register
						</button>
						<button type="button" @click="isRegister = false" class="ml-2 mb-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
					</div>
									
				</div>
			</div>
		</div>
		
		<!-- Modal registration verification code form -->
		<div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalVerificationRegistration" @keydown.escape.prevent.stop="showModalVerificationRegistration = false" x-transition>
			<div class="flex items-start justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

			<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
				<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
			</div>

			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

				<div
					class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
					role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
					<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
					</div>

					<div class="sm:flex sm:items-start">
						<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
							<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
								Verification
							</h3>
						</div>
					</div>
					<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
						<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
							
							<div class="relative mb-2">
								<label for="verification-code" class="leading-7 text-sm text-gray-600">Code</label>
                            	<input type="text" id="verification-code" name="food-joint-name" placeholder="Enter Code" v-model="formRegisterData.code" required class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

								<span class="text-red-500 text-xs ml-2">
									{{validationError('code', saveError)}}
								</span>
							</div>

						</div>
					</div>
					<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
						<button class="w-full inline-flex justify-center ml-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm"
							@click="otherRegister()"
						>
							Proceed
						</button>
					</div>
									
				</div>
			</div>
		</div>

            <header class="text-gray-600 body-font bg-white shadow-lg z-10 fixed w-full">
                <div class="container mx-auto flex flex-wrap p-3 flex-col md:flex-row items-center">
                    <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
                    <span class="ml-3 text-2xl text-yellow-400 font-bold">FoodHub</span>
                    </a>
                    <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
                    </nav>
                </div>
            </header>
            <div
                class="absolute overflow-hidden bg-no-repeat bg-cover w-full"
                style="background-position: 50%; height: 400px;">
                <carousel :navigationEnabled="false" :perPage="1" :paginationEnabled="false" :autoplay="true" :loop="true" >
                    <slide v-for="banner in banners" :key="banner" class="w-full" >
                        <div class="w-full">
                            <img
                                :src="'/images/banners/' +banner"
                                class="block w-full imageSliders"
                                alt=""
                            />
                        </div>
                    </slide>
                </carousel>
            </div>

            <section class="relative text-gray-600 body-font w-full">
                <div class="container px-5 py-24 mx-auto flex flex-wrap items-center">
                    <div class="lg:w-2/6 md:w-1/2 bg-white rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0 bg-white shadow-lg">
                        <h2 class="text-gray-900 text-lg font-medium title-font mb-5">Login</h2>
                        <div class="relative mb-4">
                            <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
                            <input type="text" id="email" name="email" 
                                v-model="formloginData.email"
                                @keyup.enter="login()"
                                required
                                class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            
                        </div>
                        <div class="relative mb-4">
                            <label for="password" class="leading-7 text-sm text-gray-600">Password</label>
                            <input type="password" id="password" name="password"
                            v-model="formloginData.password"
                            @keyup.enter="login()"
                            required class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            
                            <span class="text-red-500 text-xs ml-2" v-if="message && message != 'success'">
                                {{ message }}
                            </span>
                        </div>
                        <button class="text-white bg-yellow-500 border-0 py-2 px-8 focus:outline-none hover:bg-yellow-600 rounded text-lg font-bold" 
                            @click="login()"
                            :disabled="disableButton()"
                            :class="{ 'cursor-not-allowed' : disableButton() }">
                            Login
                        </button>
                        <p class="text-xs text-gray-500 mt-3">Don't have an account? <a href="#" @click="isRegister = true" class="text-indigo-500 font-bold">Sign up</a></p>
                    </div>
                </div>
            </section>
            
			<section class="text-gray-600 body-font"  v-if="!restaurant">
				<div class="container px-5  mx-auto">
					<div class="flex flex-wrap -m-4">
						<div class="lg:w-1/3 md:w-1/2 p-4 w-full cursor-pointer" v-for="(arg, i) in restaurants" :key="i" @click="selectShop(arg)">
							<a class="block relative h-48 rounded overflow-hidden " href="#restaurant-detail" >
							<img alt="ecommerce" class="object-cover object-center w-full h-full block" :src="'/images/uploads/' + arg.banner">
							</a>
							<div class="mt-4">
								<h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">{{ arg.restaurant_name.toUpperCase() }}</h3>
							</div>
						</div>
					</div>
				</div>
			</section>

            <div id="restaurant-detail" v-else>
				<section class="text-gray-600 body-font">
					<div class="container px-5 mx-auto">
						<div class="flex flex-wrap w-full">
							<div class="lg:w-1/2 w-full mb-6 lg:mb-0" >
								<span class="cursor-pointer"  @click="restaurant = null">
									<i class="fa-solid fa-arrow-left"></i> Back
								</span>
								<div class="sm:text-3xl text-2xl font-medium title-font mb-2 text-gray-900 mt-4"  @click="restaurant = null">
									<img alt="image" class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-gray-200 text-gray-400" :src="'/images/uploads/' + restaurant.image">
									<h1 class="sm:text-3xl text-2xl font-medium title-font text-gray-900">
										{{ restaurant.restaurant_name }} <hr class="mr-4 h-1 w-20 bg-yellow-500 rounded"></hr>
									</h1>
								</div>
								<div class="flex mx-auto flex-wrap mb-2 mt-10">
									<a @click="activeCategory = 'Food'" :class="{'bg-gray-100 border-yellow-500 text-yellow-500': activeCategory == 'Food'}" class="sm:px-6 py-3 w-1/2 sm:w-auto justify-center sm:justify-start border-b-2 title-font font-medium inline-flex items-center leading-none tracking-wider rounded-t">
										FOODS
									</a>
									<a @click="activeCategory = 'Drink'" :class="{'bg-gray-100 border-yellow-500 text-yellow-500': activeCategory == 'Drink'}"class="sm:px-6 py-3 w-1/2 sm:w-auto justify-center sm:justify-start border-b-2 title-font font-medium inline-flex items-center leading-none border-gray-200 hover:text-gray-900 tracking-wider">
										DRINKS
									</a>
								</div>
							</div>
						</div>
					</div>
				</section>

				<section class="text-gray-600 body-font">
					<div class="container px-5 py-24 mx-auto">
						<div class="flex flex-wrap -m-4">
							<div class="lg:w-1/4 md:w-1/2 p-4 w-full" @click="product.description ? openDescriptionModal(product) : ''" v-for="product in restaurant.products.filter( x => { return x.category == activeCategory})" :key="product.id">
								<a class="block relative h-48 rounded overflow-hidden">
								<img alt="ecommerce" class="cursor-pointer object-cover object-center w-full h-full block" :src="'/images/uploads/' + product.image">
								</a>
								<div class="mt-4">
								<h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">{{ product.name.toUpperCase() }}</h3>
								<p class="leading-relaxed text-base">₱{{ parseFloat(product.amount).toFixed(2) }}</p>
								</div>
							</div>
						</div>
					</div>
				</section>
				
				<!-- Modal view product -->
				<div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalViewProduct" @keydown.escape.prevent.stop="showModalViewProduct = false" x-transition>
					<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

					<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
						<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
					</div>

					<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

						<div
							class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
							role="dialog" aria-modal="true" aria-labelledby="" @click.stop x-trap.noscroll.inert="">
							<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
							<button type="button" @click="closeDescriptionModal()"
								class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
								<span class="sr-only">Close</span>
								<!-- Heroicon name: outline/x -->
								<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
								stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
								</svg>
							</button>
							</div>

							<div class="sm:flex sm:items-start">
								<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
									<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
										{{productName}}
									</h3>
								</div>
							</div>
							<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
								<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
									<div class="col-span-4 sm:col-span-4">
										<div class="block relative h-80 w-80 overflow-hidden items-center rounded-lg">
											<img alt="foodhubs" class="object-center w-full h-full block" :src="productImage" >
										</div>
											<div class="mt-4">
											<h3 class="text-lg tracking-widest title-font mb-1">₱ {{ parseFloat(productAmount).toFixed(2) }}</h3>
											<p class="leading-relaxed text-xs text-gray-500">{{description}}</p>
										</div>
									</div>
								</div>
							</div>
							<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
								<button type="button" @click="closeDescriptionModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Close</button>
							</div>
											
						</div>
					</div>
				</div>

				

            </div>

			<hr class="text-gray-600 body-font mt-32">
			<footer class="text-gray-600 body-font ">
				<div class="container px-5 py-8 mx-auto flex items-center sm:flex-row flex-col">
					<a class="flex title-font font-medium items-center md:justify-start justify-center text-gray-900">

					<svg fill="currentColor" class="w-20 h-20" width="500" zoomAndPan="magnify" viewBox="0 0 375 374.999991" height="500" preserveAspectRatio="xMidYMid meet" version="1.0">
						<defs>
						<g/>
						<clipPath id="8462c1e79f">
						<path d="M 133.804688 64.960938 L 241.054688 64.960938 L 241.054688 187.183594 L 133.804688 187.183594 Z M 133.804688 64.960938 " clip-rule="nonzero"/>
						</clipPath>

						<clipPath id="6eaa360b11">
						<path d="M 83 257.496094 L 299 257.496094 L 299 259.746094 L 83 259.746094 Z M 83 257.496094 " clip-rule="nonzero"/>
						</clipPath>
						<clipPath id="56e19b34d2">
						<path d="M 83 197.78125 L 299 197.78125 L 299 200.03125 L 83 200.03125 Z M 83 197.78125 " clip-rule="nonzero"/>
						</clipPath>
						</defs>
						<rect x="-37.5" width="450" fill="#ffffff" y="-37.499999" height="449.999989" fill-opacity="1"/>
						<rect x="-37.5" width="450" fill="#ffffff" y="-37.499999" height="449.999989" fill-opacity="1"/>
						<rect x="-37.5" width="450" fill="#ffffff" y="-37.499999" height="449.999989" fill-opacity="1"/>
						<g clip-path="url(#8462c1e79f)">
						<path fill="#f8b01b" d="M 204.398438 121.074219 L 200.191406 128.378906 C 199.675781 129.234375 199.972656 130.285156 200.828125 130.800781 C 201.660156 131.261719 202.734375 130.96875 203.25 130.164062 L 207.457031 122.835938 L 209.5625 119.242188 C 210.027344 118.414062 211.105469 118.09375 211.984375 118.609375 C 212.816406 119.121094 213.085938 120.148438 212.621094 121.027344 L 210.515625 124.617188 L 206.257812 131.921875 C 205.792969 132.777344 206.089844 133.828125 206.894531 134.339844 C 207.753906 134.855469 208.851562 134.511719 209.316406 133.707031 L 213.574219 126.402344 L 215.679688 122.761719 C 216.144531 121.929688 217.222656 121.660156 218.054688 122.125 C 218.910156 122.640625 219.203125 123.714844 218.691406 124.546875 L 216.632812 128.136719 L 211.519531 136.929688 L 210.296875 139.03125 C 208.460938 142.183594 204.914062 143.773438 201.488281 143.308594 L 192.433594 158.945312 L 188.617188 152.273438 L 195.738281 140.011719 C 193.585938 137.226562 193.21875 133.363281 195.050781 130.214844 L 196.277344 128.113281 L 201.339844 119.269531 L 203.445312 115.675781 C 203.960938 114.847656 205.035156 114.527344 205.867188 115.042969 C 206.699219 115.503906 207.019531 116.582031 206.503906 117.460938 Z M 155.828125 84.40625 L 164.953125 84.40625 C 165.6875 79.445312 167.964844 75.023438 171.390625 71.601562 C 175.5 67.5 181.152344 64.980469 187.46875 64.980469 C 193.730469 64.980469 199.382812 67.5 203.496094 71.601562 C 206.921875 75.023438 209.246094 79.445312 209.929688 84.40625 L 219.058594 84.40625 C 222.261719 84.40625 225.003906 85.675781 227.058594 87.726562 C 229.113281 89.78125 230.433594 92.664062 230.851562 95.789062 L 240.957031 174.921875 C 241.007812 175.386719 241.054688 175.777344 241.054688 176.144531 C 241.054688 179.027344 239.78125 181.617188 237.800781 183.519531 C 235.917969 185.355469 233.421875 186.625 230.679688 186.941406 C 230.140625 187.039062 229.628906 187.089844 229.164062 187.089844 L 198.699219 187.089844 L 195.003906 180.636719 L 173.347656 143.210938 C 169.015625 143.578125 164.023438 140.621094 161.015625 135.417969 C 157.222656 128.894531 158.101562 121.320312 162.925781 118.535156 C 167.71875 115.75 174.71875 118.804688 178.488281 125.328125 C 181.449219 130.53125 181.546875 136.320312 179.074219 139.910156 L 202.664062 180.636719 L 229.1875 180.636719 C 229.503906 180.636719 229.726562 180.636719 229.921875 180.589844 C 231.195312 180.417969 232.394531 179.808594 233.296875 178.953125 C 234.082031 178.171875 234.570312 177.21875 234.570312 176.167969 C 234.570312 175.949219 234.570312 175.800781 234.570312 175.703125 L 224.441406 96.570312 C 224.21875 94.886719 223.535156 93.320312 222.433594 92.296875 C 221.578125 91.441406 220.425781 90.878906 219.058594 90.878906 L 210.148438 90.878906 L 210.148438 98.820312 C 211.839844 99.917969 212.941406 101.773438 212.941406 103.875 C 212.941406 107.199219 210.25 109.886719 206.921875 109.886719 C 203.59375 109.886719 200.902344 107.199219 200.902344 103.875 C 200.902344 101.773438 202.050781 99.917969 203.691406 98.820312 L 203.691406 90.878906 L 171.242188 90.878906 L 171.242188 98.820312 C 172.933594 99.917969 174.03125 101.773438 174.03125 103.875 C 174.03125 107.199219 171.339844 109.886719 168.011719 109.886719 C 164.6875 109.886719 161.996094 107.199219 161.996094 103.875 C 161.996094 101.773438 163.09375 99.917969 164.785156 98.820312 L 164.785156 90.878906 L 155.875 90.878906 C 154.507812 90.878906 153.355469 91.417969 152.5 92.296875 C 151.449219 93.347656 150.714844 94.886719 150.492188 96.570312 L 140.410156 175.703125 C 140.363281 175.800781 140.363281 175.921875 140.363281 176.167969 C 140.363281 177.21875 140.875 178.171875 141.636719 178.953125 C 142.539062 179.808594 143.738281 180.417969 145.011719 180.589844 C 145.183594 180.636719 145.429688 180.636719 145.746094 180.636719 L 172.269531 180.636719 L 182.5 163 L 186.339844 169.621094 L 176.210938 187.136719 L 145.699219 187.136719 C 145.234375 187.136719 144.742188 187.089844 144.179688 186.992188 C 141.488281 186.671875 138.917969 185.402344 137.058594 183.570312 C 135.101562 181.664062 133.804688 179.050781 133.804688 176.191406 C 133.804688 175.824219 133.855469 175.410156 133.902344 174.96875 L 144.035156 95.839844 C 144.449219 92.6875 145.769531 89.828125 147.875 87.777344 C 149.90625 85.675781 152.648438 84.40625 155.828125 84.40625 Z M 171.539062 84.40625 L 203.371094 84.40625 C 202.734375 81.203125 201.144531 78.394531 198.941406 76.195312 C 196.03125 73.238281 191.96875 71.457031 187.492188 71.457031 C 182.964844 71.457031 178.953125 73.238281 175.992188 76.195312 C 173.789062 78.394531 172.171875 81.203125 171.539062 84.40625 Z M 171.539062 84.40625 " fill-opacity="1" fill-rule="nonzero"/></g><g clip-path="url(#6eaa360b11)"><path fill="#f8b01b" d="M 298.339844 259.730469 L 83.785156 259.730469 L 83.785156 257.488281 L 298.339844 257.488281 L 298.339844 259.730469 " fill-opacity="1" fill-rule="nonzero"/>
						</g>
						<g fill="#f8b01b" fill-opacity="1">
						<g transform="translate(85.122014, 240.020771)">
						<g>
						<path d="M 10.269531 0 L 10.269531 -11.285156 L 22.679688 -11.285156 L 22.679688 -17.25 L 10.269531 -17.25 L 10.269531 -24.277344 L 24.730469 -24.277344 L 24.730469 -30.628906 L 3.128906 -30.628906 L 3.128906 0 Z M 10.269531 0 "/>
						</g>
						</g>
						<g transform="translate(111.134891, 240.020771)">
						<g>
						<path d="M 13.714844 0.742188 C 21.125 0.742188 26.238281 -4.796875 26.238281 -11.667969 C 26.238281 -18.492188 21.125 -24.074219 13.738281 -24.074219 C 6.394531 -24.074219 1.328125 -18.46875 1.328125 -11.667969 C 1.328125 -4.820312 6.417969 0.742188 13.714844 0.742188 Z M 13.785156 -5.652344 C 10.519531 -5.652344 8.199219 -8.109375 8.199219 -11.667969 C 8.199219 -15.203125 10.519531 -17.679688 13.785156 -17.679688 C 17.050781 -17.679688 19.367188 -15.203125 19.367188 -11.667969 C 19.367188 -8.109375 17.050781 -5.652344 13.785156 -5.652344 Z M 13.785156 -5.652344 "/>
						</g>
						</g>
						<g transform="translate(138.701784, 240.020771)">
						<g>
						<path d="M 13.714844 0.742188 C 21.125 0.742188 26.238281 -4.796875 26.238281 -11.667969 C 26.238281 -18.492188 21.125 -24.074219 13.738281 -24.074219 C 6.394531 -24.074219 1.328125 -18.46875 1.328125 -11.667969 C 1.328125 -4.820312 6.417969 0.742188 13.714844 0.742188 Z M 13.785156 -5.652344 C 10.519531 -5.652344 8.199219 -8.109375 8.199219 -11.667969 C 8.199219 -15.203125 10.519531 -17.679688 13.785156 -17.679688 C 17.050781 -17.679688 19.367188 -15.203125 19.367188 -11.667969 C 19.367188 -8.109375 17.050781 -5.652344 13.785156 -5.652344 Z M 13.785156 -5.652344 "/>
						</g>
						</g>
						<g transform="translate(166.268676, 240.020771)">
						<g>
						<path d="M 12.453125 0.765625 C 16.035156 0.765625 18.355469 -1.148438 19.617188 -2.949219 L 19.617188 0 L 26.191406 0 L 26.191406 -31.734375 L 19.300781 -31.734375 L 19.300781 -23.109375 L 19.4375 -20.539062 C 18.308594 -22.183594 15.832031 -24.074219 12.523438 -24.074219 C 5.652344 -24.074219 1.328125 -18.46875 1.328125 -11.667969 C 1.328125 -4.820312 5.789062 0.765625 12.453125 0.765625 Z M 13.738281 -5.609375 C 10.359375 -5.609375 8.152344 -8.242188 8.152344 -11.667969 C 8.152344 -15.246094 10.539062 -17.703125 13.738281 -17.703125 C 16.914062 -17.703125 19.347656 -15.042969 19.347656 -11.667969 C 19.347656 -8.21875 16.890625 -5.609375 13.738281 -5.609375 Z M 13.738281 -5.609375 "/>
						</g>
						</g>
						<g transform="translate(195.164365, 240.020771)">
						<g/>
						</g>
						<g transform="translate(206.245175, 240.020771)">
						<g>
						<path d="M 10.269531 0 L 10.269531 -12.363281 L 23.648438 -12.363281 L 23.648438 0 L 30.808594 0 L 30.808594 -30.628906 L 23.648438 -30.628906 L 23.648438 -18.691406 L 10.269531 -18.691406 L 10.269531 -30.628906 L 3.128906 -30.628906 L 3.128906 0 Z M 10.269531 0 "/>
						</g>
						</g>
						<g transform="translate(240.185786, 240.020771)">
						<g>
						<path d="M 13.601562 0.742188 C 19.863281 0.742188 24.863281 -2.386719 24.863281 -10.878906 L 24.863281 -23.332031 L 17.996094 -23.332031 L 17.996094 -11.101562 C 17.996094 -7.726562 16.710938 -5.652344 13.601562 -5.652344 C 10.5625 -5.652344 9.234375 -7.636719 9.234375 -11.101562 L 9.234375 -23.332031 L 2.34375 -23.332031 L 2.34375 -10.878906 C 2.34375 -2.386719 7.34375 0.742188 13.601562 0.742188 Z M 13.601562 0.742188 "/>
						</g>
						</g>
						<g transform="translate(267.414849, 240.020771)">
						<g>
						<path d="M 16.464844 0.765625 C 22.972656 0.765625 27.589844 -4.820312 27.589844 -11.667969 C 27.589844 -18.445312 23.039062 -24.054688 16.351562 -24.054688 C 13.648438 -24.054688 10.992188 -22.816406 9.460938 -20.539062 L 9.617188 -23.492188 L 9.617188 -31.734375 L 2.679688 -31.734375 L 2.679688 0 L 9.300781 0 L 9.300781 -2.949219 C 10.808594 -0.699219 13.46875 0.765625 16.464844 0.765625 Z M 15.15625 -5.609375 C 12.160156 -5.609375 9.550781 -8.0625 9.550781 -11.667969 C 9.550781 -15.042969 12.027344 -17.703125 15.113281 -17.703125 C 18.199219 -17.703125 20.742188 -15.425781 20.742188 -11.667969 C 20.742188 -7.726562 18.109375 -5.609375 15.15625 -5.609375 Z M 15.15625 -5.609375 "/>
						</g>
						</g>
						</g>
						<g clip-path="url(#56e19b34d2)">
						<path fill="#f8b01b" d="M 298.339844 200.011719 L 83.785156 200.011719 L 83.785156 197.773438 L 298.339844 197.773438 L 298.339844 200.011719 " fill-opacity="1" fill-rule="nonzero"/>
						</g>
					</svg>
					</a>
					<p class="text-sm text-gray-500 sm:ml-4 sm:pl-4 sm:border-l-2 sm:border-gray-200 sm:py-2 sm:mt-0 mt-4">© 2022 FoodHub
					</p>
					<span class="inline-flex sm:ml-auto sm:mt-0 mt-4 justify-center sm:justify-start">
					<a class="text-gray-500">
						<svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
						<path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
						</svg>
					</a>
					<a class="ml-3 text-gray-500">
						<svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
						<path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
						</svg>
					</a>
					<a class="ml-3 text-gray-500">
						<svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
						<rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
						<path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
						</svg>
					</a>
					<a class="ml-3 text-gray-500">
						<svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="w-5 h-5" viewBox="0 0 24 24">
						<path stroke="none" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path>
						<circle cx="4" cy="4" r="2" stroke="none"></circle>
						</svg>
					</a>
					</span>
				</div>
			</footer>
	</div>
            
    
        


</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import axios from "axios";

export default {
    props:['options'],
    data(){
        return {
            formloginData : {
                email: null,
                password: null,
                code: null
            },
            formRegisterData: {
                name: '',
                phone: '',
                email: '',
                password: '',
                confirm_password: '',
                role: 3,
                user_type: 'customer',
                restaurant_name: '',
                address: '',
                code: ''
            },
            isRegister: false,
            saveError: null,
            formData: new FormData(),
            restaurants: [],
            restaurant: null,
            isLogin: false,
            activeCategory: 'Food',
            banners: [
                'slider1.jpg',
                'slider2.jpg',
                'slider3.jpg',
                'slider4.jpg',
                'slider5.jpg',
            ],
            description: '',
            productName: '',
            productImage: '',
            isMobile: window.screen.width <= 700,
            ids: [],
            isDone: false,
            message: null,
            termsAndCondition: false,
			showModalViewProduct: false,
			showModalVerificationRegistration: false,
        }
    },

    watch: {
        isRegister: function(arg) {
            this.saveError = null
            this.message = null
        },

        'formRegisterData.role': function(arg) {
            if(arg == 2) {
                this.formRegisterData.user_type = 'owner'
            } else {
                this.formRegisterData.user_type = 'customer'
            }
        },
        
    },

    mounted(){
        this.restaurants = this.options.restaurants

        if(this.isMobile) {
            this.isLogin = false
        }

        // this.banners = this.banners.concat(this.options.banners)
    },

    methods: {
        login() {
            // Inertia.post(this.$root.route + "/users/login", this.formloginData,
            // {
            //  onSuccess: (res) => {
            //      if(res.props.message == 'success') {
            //          this.openLoginModal()
            //      }
            //  },
            //  orError: (err) => {
            //  }
            // });

            axios.post(this.$root.route + "/users/login", this.formloginData)
                .then(response => {

                    if(response.data.status == 422) {
                        // this.saveError = response.data.errors
                        this.message = response.data.message
                    } else {
                        location.reload()
                        // this.openLoginModal()
                    }
                })
        },
        
        otherLogin() {
            axios.post(this.$root.route + "/users/verify-otp", this.formloginData)
                .then(response => {
                    if(response.data.status == 422) {
                        this.saveError = response.data.errors
                    } else {
                        location.reload()
                    }
                })
        },

        disableButton(){
            if(!this.formloginData.email){
                return true;
            }

            if(!this.formloginData.password){
                return true;
            }

            return false;
        },

        imageChange(arg, e) {
            const image = e.target.files[0];

            // this.formData = new FormData()
            if(arg == 'picture_id') {
                this.formData.append('picture_id[]', image); 

                const reader = new FileReader();

                reader.readAsDataURL(image);

                reader.onload = e =>{
                    this.ids.push({image: e.target.result})
                }
            } else {
                this.formData.append(arg, image);
            }
            
        },

        register() {
            this.formData.append('name', this.formRegisterData.name);
            this.formData.append('phone', this.formRegisterData.phone);
            this.formData.append('email', this.formRegisterData.email);
            this.formData.append('password', this.formRegisterData.password);
            this.formData.append('confirm_password', this.formRegisterData.confirm_password);
            this.formData.append('role', this.formRegisterData.role);
            this.formData.append('user_type', this.formRegisterData.user_type);
            this.formData.append('restaurant_name', this.formRegisterData.restaurant_name);
            this.formData.append('address', this.formRegisterData.address);
            
            axios.post(this.$root.route + "/users/create-account", this.formData)
                .then(response => {
                    if(response.data.status == 422) {
                        this.saveError = response.data.errors
                    } else {
                        this.saveError = null
                    }

                    if(this.saveError == null) {
                        axios.post(this.$root.route + "/users/save-verification", {phone: this.formRegisterData.phone })
                            .then(response => {
                                if(response.data.status == 422) {
                                    
                                } else {
                                    this.isDone = true
                                    this.formData.append('isDone', this.isDone);
									this.isRegister = false;
                                    this.openRegisterModal()
                                }
                            })
                    }
                })
        },

        otherRegister() {
            this.formData.append('name', this.formRegisterData.name);
            this.formData.append('phone', this.formRegisterData.phone);
            this.formData.append('email', this.formRegisterData.email);
            this.formData.append('password', this.formRegisterData.password);
            this.formData.append('confirm_password', this.formRegisterData.confirm_password);
            this.formData.append('role', this.formRegisterData.role);
            this.formData.append('user_type', this.formRegisterData.user_type);
            this.formData.append('restaurant_name', this.formRegisterData.restaurant_name);
            this.formData.append('address', this.formRegisterData.address);
            this.formData.append('isDone', this.isDone);
            this.formData.append('code', this.formRegisterData.code);
            
            axios.post(this.$root.route + "/users/create-account", this.formData)
                .then(response => {
                    if(response.data.status == 422) {
                        this.saveError = response.data.errors
                    } else {
                        this.saveError = null

                        if(this.isDone) {
                            this.formRegisterData = {
                                name: '',
                                phone: '',
                                email: '',
                                password: '',
                                confirm_password: '',
                                role: 3,
                                user_type: 'customer',
                                restaurant_name: '',
                                address: '',
                                code: ''
                            }

                            alert("Account successfully created.");

                            this.isRegister = false

                            this.formData = new FormData()
							
							this.showModalVerificationRegistration = false
                            
                            location.reload()
                        }
                    }
                })
        },
        sendRegisterVerification(){
            this.register()
        },

        selectShop(arg){
            this.restaurant = arg
        },

        openDescriptionModal(arg){
			this.showModalViewProduct = true;

            this.description = arg.description
            this.productName = arg.name
            this.productImage = '/images/uploads/' + arg.image
            this.productAmount = arg.amount
        },

        closeDescriptionModal(){
			this.showModalViewProduct = false;

            this.description = null
            this.productName = null
            this.productImage = ''
            this.productAmount = ''
        },

        openRegisterModal(){
			this.showModalVerificationRegistration = true

            this.isLogin = false
        },

        closeRegisterModal(){
            var modal = document.getElementById("registerModal");

            modal.style.display = "none";
        },

        openLoginModal(){
            var modal = document.getElementById("loginModal");

            modal.style.display = "block";
        },

        closeLoginModal(){
            var modal = document.getElementById("loginModal");

            modal.style.display = "none";
        },

        openTermsAndCondition() {
            var modal = document.getElementById("termsModal");

            modal.style.display = "block";
        },

        closeTermsAndCondition() {
            var modal = document.getElementById("termsModal");

            modal.style.display = "none";

            this.isLogin = true
        },

        removeIds() {
            this.ids = [];
            this.formData.delete('picture_id[]');
        }
    }
}

</script>
