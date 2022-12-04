<template>
    <div>
        <Navigation :auth="auth"></Navigation>
            <!-- Desktop banner -->
            <div class="absolute cursor-pointer overflow-hidden bg-no-repeat bg-cover w-full hidden md:block"
                style="background-position: 50%; height: 400px;">
                    <img :src="restaurant.banner" 
                        class="block w-full relative"
                    >
                    <input type="file" ref="image" @change="imageChangeRestaurant('image', $event)" accept="image/png, image/jpeg" style="display:none">
                    <input type="file" ref="banner" @change="imageChangeRestaurant('banner', $event)" accept="image/png, image/jpeg" style="display:none">
            </div>

            <!-- Mobile banner -->
            <div class="absolute cursor-pointer overflow-hidden bg-no-repeat bg-cover w-full sm:hidden py-20"
                    style="background-position: 50%; height: 400px;">
                    <img :src="restaurant.banner" 
                        class="block w-full relative"
                    >
                    <input type="file" ref="image" @change="imageChangeRestaurant('image', $event)" accept="image/png, image/jpeg" style="display:none">
                    <input type="file" ref="banner" @change="imageChangeRestaurant('banner', $event)" accept="image/png, image/jpeg" style="display:none">
            </div>
            
            <section class="relative text-gray-600 body-font w-full py-10">
                <div class="container px-5 py-12 mx-auto flex flex-wrap items-center">
                    <div class="sm:text-3xl text-2xl font-medium title-font mb-2 text-gray-900 lg:mt-40 md:mt-40 sm:mt-40">
                        <img alt="image" class="cursor-pointer w-30 h-25 rounded-full shadow-xl inline-flex items-center justify-center bg-gray-200 text-gray-400"
                        :src="restaurant.image" @click="uploadImage('image')" >
                        <h1 class="sm:text-3xl text-2xl font-medium title-font text-gray-900">
                            {{ restaurant.restaurant_name }} <hr class="mr-4 h-1 w-20 bg-yellow-500 rounded"></hr>
                        </h1>
                        <button type="button" class="mt-4 w-full inline-flex justify-center ml-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-4 sm:w-auto sm:text-sm"
                            @click="uploadImage('banner')">Change Banner</button>
                    </div>
                </div>
            </section>

            <section class="text-gray-600 body-font">
                <div class="container px-5 mx-auto">
                    <div class="flex flex-wrap w-full">
                        <div class="lg:w-1/2 w-full " >
                            <div class="flex mx-auto flex-wrap mb-10 cursor-pointer">
                                <a @click="activeTab = 'menus'" :class="{'bg-gray-100 border-yellow-500 text-yellow-500': activeTab == 'menus'}" class="sm:px-6 py-3 w-1/2 sm:w-auto justify-center sm:justify-start border-b-2 title-font font-medium inline-flex items-center leading-none tracking-wider rounded-t">
                                    Menu
                                </a>
                                <a @click="activeTab = 'places'" :class="{'bg-gray-100 border-yellow-500 text-yellow-500': activeTab == 'places'}" class="sm:px-6 py-3 w-1/2 sm:w-auto justify-center sm:justify-start border-b-2 title-font font-medium inline-flex items-center leading-none border-gray-200 hover:text-gray-900 tracking-wider">
                                    Places
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="flex mx-auto flex-wrap  cursor-pointer justify-start" v-if="activeTab == 'menus'">
                        
                            <a @click="activeCategory = 'Food'" :class="{'bg-gray-100 border-yellow-500 text-yellow-500': activeCategory == 'Food'}" class="sm:px-6 py-3 w-1/2 sm:w-auto justify-center sm:justify-start border-b-2 title-font font-medium inline-flex items-center leading-none tracking-wider rounded-t">
                                Foods
                            </a>
                            <a @click="activeCategory = 'Drink'" :class="{'bg-gray-100 border-yellow-500 text-yellow-500': activeCategory == 'Drink'}" class="sm:px-6 py-3 w-1/2 sm:w-auto justify-center sm:justify-start border-b-2 title-font font-medium inline-flex items-center leading-none border-gray-200 hover:text-gray-900 tracking-wider">
                                Drinks
                            </a>
                        <div  class="mt-4 w-full inline-flex justify-center ml-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-4 sm:w-auto sm:text-sm"
                            @click="openModal()"
                        >
                             +
                        </div>
                    </div>


                    <section class="text-gray-600 body-font" v-if="activeTab == 'places'">
                        <div class="container  mx-auto">
                            <div class="flex flex-wrap sm:mb-2 -mx-2">
                                <div class="w-full overflow-hidden rounded-lg shadow-lg">
                                    <div class="bg-white px-2 py-5 border-b border-gray-200 sm:px-6">
                                        <div class="ml-2 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
                                        <div class="ml-2 mt-4">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                    Address
                                                </h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    Showing list of address
                                                </p>
                                            </div>
                                            <div class="mt-4 flex sm:mt-0 sm:ml-2">
                                                <button type="button" class="w-full inline-flex justify-center ml-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm" @click="openAddressModal()">Add Address</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full overflow-x-auto">
                                        <table class="w-full whitespace-no-wrap">
                                            <thead>
                                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                                    <th class="px-2 py-3"></th>
                                                    <th class="px-2 py-3"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y">

                                                <tr v-for="add in options.address" :key="add.id" 
                                                    class="text-gray-700">
                                                    <td class="px-2 py-3">
                                                        {{add.address}}
                                                    </td>
                                                    <td class="px-2 py-3">

                                                        <button type="button" class="w-full inline-flex justify-center ml-2 rounded-md border-0 shadow-sm px-4 py-2 bg-rose-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:mt-0 sm:w-auto sm:text-sm" @click="placeSelected = add">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t bg-gray-50 sm:grid-cols-9">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </section>

            <section class="text-gray-600 body-font" v-if="activeTab == 'menus'">
                <div class="container px-5 py-24 mx-auto">
                    <div class="flex flex-wrap -m-4">
                        <div class="lg:w-1/4 md:w-1/2 p-4 w-full" v-for="product in products.filter( x => { return x.category == activeCategory})" :key="product.id">
                            <div class="w-full inline-flex mb-2">
                                <p @click="viewProduct(product)">
                                    <i class="fa-solid fa-pen-to-square fa-lg mx-1 cursor-pointer p-1"></i>
                                </p>
                                <Toggle :value="product.is_active" :url="'/restaurants/product/deactivate-reactivate'" :id="product.id" class="ml-1 mt-0.5 mb-2"/>
                            </div>
                            <a class="block relative h-48 rounded overflow-hidden" @click="openDescriptionModal(product)" v-if="product.description">
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

		<!-- Modal edit product -->
		<div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalEditProduct" @keydown.escape.prevent.stop="closeModal()" x-transition>
			<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

			<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
				<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
			</div>

			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

				<div
					class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
					role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
					<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
					<button type="button" @click="closeModal()"
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
								{{activeCategory}}
							</h3>
						</div>
					</div>
					<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
						<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
							
                            <div class="relative mb-2">
                                <label for="name" class="leading-7 text-sm text-gray-600">Name</label>
                                <input type="text" id="name" name="name" placeholder="Name" required v-model="form.name" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

                                <span class="text-red-500 text-xs ml-2">
                                    {{validationError('name', saveError)}} 
                                </span>
                            </div>
                            <div class="relative mb-2">
                                <label for="description" class="leading-7 text-sm text-gray-600">Description</label>
                                <textarea placeholder="Description" rows="5" cols="50" id="description" required v-model="form.description" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"/>

                                <span class="text-red-500 text-xs ml-2">
                                    {{validationError('description', saveError)}}
                                </span>
                            </div>
                            <div class="relative mb-2">
                                <label for="amount" class="leading-7 text-sm text-gray-600">Amount</label>
                                <input type="text" inputmode="decimal" id="amount" placeholder="Amount" required v-model="form.amount" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

                                <span class="text-red-500 text-xs ml-2">
                                    {{validationError('amount', saveError)}}
                                </span>
                            </div>
                            <div class="relative mb-2">
                                <label for="id-picture" class="leading-7 text-sm text-gray-600">Image</label>
                                <input type="file" id="id-picture" accept="image/png, image/jpeg" @change="imageChange('image', $event)" required class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <span class="text-red-500 text-xs ml-2">
                                    {{validationError('image', saveError)}}
                                </span>

                            </div>

						</div>
					</div>
					<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
						<button class="w-full inline-flex justify-center ml-2 mb-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm"
							@click="createProduct()"
						>
							Save
						</button>
						<button type="button" @click="closeModal()" class="ml-2 mb-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
					</div>
									
				</div>
			</div>
		</div>

		<!-- Modal add address -->
		<div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalAddAddress" @keydown.escape.prevent.stop="closeAddressModal()" x-transition>
			<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

			<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
				<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
			</div>

			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

				<div
					class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
					role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
					<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
					<button type="button" @click="closeAddressModal()"
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
								New address
							</h3>
						</div>
					</div>
					<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
						<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
							
                            <div class="relative mb-2">
                                <label for="address" class="leading-7 text-sm text-gray-600">Address</label>
                                <input type="text" id="address" name="address" placeholder="address" required v-model="formAddress.address" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

                                <span class="text-red-500 text-xs ml-2">
                                    {{validationError('address', saveError)}} 
                                </span>
                            </div>

						</div>
					</div>

					<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
						<button class="w-full inline-flex justify-center ml-2 mb-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm"
							@click="createAddress()"
						>
							Save
						</button>
						<button type="button" @click="closeAddressModal()" class="ml-2 mb-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
					</div>
									
				</div>
			</div>
		</div>

		<!-- Modal remove address -->
		<div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalRemoveAddress" @keydown.escape.prevent.stop="closeRemoveModal()" x-transition>
			<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

			<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
				<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
			</div>

			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

				<div
					class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
					role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
					<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
					<button type="button" @click="closeRemoveModal()"
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
						</div>
					</div>
					<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
						<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
							
                            <div class="relative mb-2">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                    Are you sure to delete this address ?
                                </h3>
                            </div>

						</div>
					</div>

					<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
						<button class="w-full inline-flex justify-center ml-2 mb-2 rounded-md border border-rose-300 shadow-sm px-4 py-2 bg-rose-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:mt-0 sm:w-auto sm:text-sm"
							 @click="removeAddress(placeSelected)"
						>
							Yes
						</button>
						<button type="button"  @click="closeRemoveModal();" class="ml-2 mb-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
					</div>
									
				</div>
			</div>
		</div>
    </div>
</template>

<script>
import Navigation from '../Layouts/Navigation.vue'
import axios from "axios";
import Toggle from '../Components/Toggle.vue';

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Toggle
    },
    data(){
        return {
            restaurant: null,
            activeCategory: 'Food',
            form:{
                restaurant_id: '',
                category: '',
                name: '',
                amount: '0.00',
                image: '',
                description: null
            },
            formData: new FormData(),
            saveError: null,
            products: [],
            description: null,
            productName: null,
            productAmount: 0,
            productImage: null,
            activeTab: 'menus',
            formAddress: {
                restaurant_id: null,
                address: null
            },
            placeSelected: null,
            isMobile: window.screen.width <= 700,
            showModalViewProduct: false,
            showModalEditProduct: false,
            showModalAddAddress: false,
            showModalRemoveAddress: false,
        }
    },

    created(){
        this.restaurant = this.options.restaurant

        this.restaurant.image = '/images/uploads/' + this.restaurant.image;
        this.restaurant.banner = '/images/uploads/' + this.restaurant.banner;

        this.form.restaurant_id = this.restaurant.id

        this.form.category = this.activeCategory

        this.products = this.restaurant.products

        this.formAddress.restaurant_id = this.restaurant.id
    },

    watch: {
        activeCategory(arg){
            this.form.category = arg
        },

        // 'form.amount': function (arg) {
        //     this.form.amount = parseFloat(arg).toFixed(2);
        // }

        placeSelected(arg) {
            if(!arg) return;

            this.openRemoveModal()
        },
    },

    methods: {
        changeAmount(){
            this.form.amount = parseFloat(this.form.amount).toFixed(2);
        },

        openModal(){
            this.showModalEditProduct = true
        },

        closeModal(){
            this.showModalEditProduct = false

            this.form.name = ''
            this.form.amount = ''
            this.form.category = this.activeCategory
            this.form.image = ''
            this.form.restaurant_id = this.restaurant.id
            this.form.description = null

            this.formData = new FormData()
        },

        openDescriptionModal(arg){
            this.showModalViewProduct = true

            this.description = arg.description
            this.productName = arg.name
            this.productImage = '/images/uploads/' + arg.image
            this.productAmount = arg.amount
        },

        closeDescriptionModal(){

            this.showModalViewProduct = false
            this.description = null
            this.productName = null
        },

        openAddressModal(){
            this.showModalAddAddress = true
        },

        closeAddressModal(){
            this.showModalAddAddress = false

            this.address = null
        },

        imageChange(arg, e) {
	      	const image = e.target.files[0];

	      	this.formData.append(arg, image);
		},

        createProduct(){
            this.formData.append('restaurant_id', this.form.restaurant_id);
            this.formData.append('category', this.form.category);
            this.formData.append('name', this.form.name);
            this.formData.append('amount', this.form.amount);
            this.formData.append('description', this.form.description);

            axios.post(this.$root.route + "/restaurants/create-product", this.formData)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
						alert("Successfully created product");
                        
						location.reload()
					}
				})
        },

        createAddress(){
            axios.post(this.$root.route + "/restaurants/create-address", this.formAddress)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
						alert("Successfully created new address");
                        
						location.reload()
					}
				})
        },

        removeAddress(arg){
            axios.post(this.$root.route + "/restaurants/remove-address", arg)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
						alert("Successfully remove address");
                        
						location.reload()
					}
				})
        },

        viewProduct(arg){
            this.openModal()

            this.formData.append('id', arg.id);
            this.form.name = arg.name
            this.form.amount = arg.amount
            this.form.category = arg.category
            this.form.image = ''
            this.form.restaurant_id = arg.restaurant_id
            this.form.description = arg.description

        },

        uploadImage(arg) {
            if(arg == 'image') {
                this.$refs.image.click()
            }

            if(arg == 'banner') {
                this.$refs.banner.click()
            }
        },

        imageChangeRestaurant(arg, e) {
            const image = e.target.files[0];

            var formData = new FormData()

            formData.append('id', this.restaurant.id);
            formData.append(arg, image);

            axios.post(this.$root.route + "/restaurants/change-image", formData)
				.then(response => {
				})

            const reader = new FileReader();

            reader.readAsDataURL(image);

            reader.onload = e =>{
                this.restaurant[arg] = e.target.result
            };
		},

        openRemoveModal(){
            this.showModalRemoveAddress = true

        },

        closeRemoveModal(){
            this.showModalRemoveAddress = false

            this.placeSelected = null
        },
    }
}
</script>
