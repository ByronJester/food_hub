<template>
	<div>
		<Navigation :auth="auth"></Navigation>
		<section class="text-gray-600 body-font">
			<div class="container px-5 py-20 mx-auto">
				<!-- Desktop -->
				<div class="flex flex-col text-center w-full mb-8 hidden md:block">
					<h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900 mt-10">It's the food and groceries you love, delivered</h1>
					<p class="lg:w-2/3 mx-auto leading-relaxed text-base"></p>
				</div>
				<!-- Mobile -->
				<div class="flex flex-col text-center w-full mb-2 sm:hidden">
					<h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900 mt-20">It's the food and groceries you love, delivered</h1>
					<p class="lg:w-2/3 mx-auto leading-relaxed text-base"></p>
				</div>
				<div class="flex lg:w-2/3 w-full sm:flex-row flex-col mx-auto px-8 sm:space-x-4 sm:space-y-0 space-y-4 sm:px-0 items-end">
					<div class="relative flex-grow w-full">
						<input type="text" v-model="search" placeholder="Search..." class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-yellow-500 focus:bg-transparent focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
					</div>
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
							<div class="flex mx-auto flex-wrap mb-4 mt-10">
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
						<div class="lg:w-1/4 md:w-1/2 p-4 w-full" v-for="(product, index) in products.filter( x => { return x.category == activeCategory})" :key="product.id">
							<a class="block relative h-48 rounded overflow-hidden cursor-pointer" @click="product.description ? openDescriptionModal(product) : ''" >
							<img alt="ecommerce" class="object-cover object-center w-full h-full block" :src="'/images/uploads/' + product.image">
							</a>
							<div class="mt-4">
								<h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">{{ product.name.toUpperCase() }}</h3>
								<p class="leading-relaxed text-base">₱{{ parseFloat(product.amount).toFixed(2) }}</p>
							</div>
							<div class="px-4 mt-4">
								<button class="w-full inline-flex justify-center mb-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:text-sm"
									
									@click="buyNow(forms[index], index, product)"
								>
								Buy Now
								</button>
								<button class="w-full inline-flex justify-center mb-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-yellow-500 hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:text-sm"
									@click="addToCart(forms[index], index)"
								>
									<i class="fa-solid fa-cart-shopping"></i> &nbsp; Add to cart
								</button>
									<input type="number" min="1"  class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out text-center" v-model="forms[index].quantity">
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

			
			<!-- Modal buy now product -->
			<div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalBuyNow" @keydown.escape.prevent.stop="closeCheckoutModal()" x-transition>
				<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

				<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
					<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
				</div>

				<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

					<div
						class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
						role="dialog" aria-modal="true" aria-labelledby="" @click.stop x-trap.noscroll.inert="">
						<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
						<button type="button" @click="closeCheckoutModal()"
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
									Checkout Order
								</h3>
							</div>
						</div>
						<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
							<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
								
								<div class="w-full flex flex-col mt-4" v-if="orderProduct && orderDescription">
									<div class="flex flex-col px-5 py-2">
										<div class="w-full flex flex-row" style="border-bottom: 1px solid #E4B934">
											<div class="h-full flex justify-center items-center my-2" style="width: 40%">
												<img :src="'/images/uploads/' + orderProduct.image" style="width: 100%; height: 80px;border: 1px solid #E4B934" class="p-1" />
											</div>

											<div class="flex flex-col justify-center items-center" style="width: 60%">
												<div class="w-full text-center">
													<p class="text-md font-bold">
														{{ orderProduct.name }}
													</p>
												</div>
												
												<div class="w-full text-center">
													<p class="text-md mt-1">
														₱ {{ orderProduct.amount }}
													</p>
												</div>
											</div>
										</div>
									</div>

									<div class="w-full flex flex-row">
										<div class="w-full text-left pl-5"> 
											Payment Details <i class="fa-solid fa-file-invoice-dollar ml-1"></i>
										</div>
									</div>

									<div class="w-full flex flex-row mt-2">
										<div class="w-full text-left pl-5">
											Subtotal: 
										</div>

										<div class="w-full text-left pl-5">
											₱ {{ parseFloat(orderProduct.amount * orderDescription.quantity).toFixed(2)  }}
										</div>
									</div>

									<div class="w-full flex flex-row">
										<div class="w-full text-left pl-5">
											Shipping Fee: 
										</div>

										<div class="w-full text-left pl-5">
											₱ 60.00
										</div>
									</div>

									<div class="w-full flex flex-row">
										<div class="w-full text-left pl-5">
											Total: 
										</div>

										<div class="w-full text-left pl-5">
											₱ {{ parseFloat((orderProduct.amount * orderDescription.quantity) + 60).toFixed(2) }}
										</div>
									</div>

									<div class="w-full flex flex-row mt-5">
										<div class="w-full flex justify-center items-center">
											Mode of Payment:
										</div>

										<div class="w-full flex justify-center items-center">
											<input class="ml-2" type="radio" value="cod" v-model="form.payment_method" />
											<label class="mx-2">COD</label>

											<input class="mr-1" type="radio" value="gcash" v-model="form.payment_method" />
											<label>&nbsp;G-Cash</label>
										</div>
									</div>

									<div class="w-full flex flex-row mt-5">
										<div class="w-full flex justify-start items-center pl-5">
											Other Address:
										</div>

										<div class="w-full flex justify-start items-center pl-5">
											<input type="checkbox" class="rounded border-yellow-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50" v-model="form.otherAddress">
										</div>
									</div>

									<div class="w-full pl-5 mt-2" v-if="form.otherAddress">
										<input type="text" id="address" name="address" placeholder="Street, Barangay, Town" v-model="form.address" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

										<span class="text-red-500 text-xs ml-2">
											{{validationError('address', saveError)}}
										</span>
									</div>

									<div class="w-full  pl-5 mt-5">
										<div class="w-full flex justify-start items-center pl-5">
											Available Delivery Address:
										</div>

										<div class="w-full flex justify-start items-center ">
											<select class="mt-1 block w-full py-2 px-3 border border-yellow-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm enabled:hover:border-yellow-500 disabled:opacity-50">
												<option v-for="p in restaurant.places" :value="p.id" :key="p.id">
													{{p.address}}
												</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
							<button type="button" @click="confirmBuyNow()" class="w-full inline-flex justify-center ml-2 mb-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm">
							Confirm
							</button>
							<button type="button" @click="closeCheckoutModal()" class="w-full inline-flex justify-center ml-2 mb-2  rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
						</div>
										
					</div>
				</div>
			</div>

		</div>
	</div>
</template>


<script>
import Navigation from '../Layouts/Navigation.vue'
import axios from "axios";
import { Inertia } from '@inertiajs/inertia';

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
    },
    data(){
        return {
            restaurants: [],
			searchRestaurants: [],
            restaurant: null,
            activeCategory: 'Food',
			forms: [],
			list: [],
			description: null,
            productName: null,
            productAmount: 0,
            productImage: null,
			search: null,
			isMobile: window.screen.width <= 700,
			products: [],
			orderDescription: null,
			orderProduct: null,
			form: {
				payment_method: 'cod',
				reference_number: null,
				otherAddress: false,
				address: null,
				order: null,
				user_id: null,
				restaurant_id: null
			},
			saveError: null,
			showModalViewProduct: false,
			showModalBuyNow: false,
        }
    },
    created(){
        this.restaurants = this.options.restaurants
    },
	watch: {
		activeCategory(arg) {
			this.list = this.restaurant.products.filter( x => { return x.category == arg})

			this.forms = [];

			for (let i = 0; i < this.list.length; i++){
				this.forms.push(
					{
						product_id: this.list[i].id,
						restaurant_id: arg.id,
						quantity: 1,
						status: 'pending',
					}
				);
			}
		},
		search(arg){
			if(!arg) {
				if(this.restaurant) {
					this.products = this.restaurant.products
				} else {
					this.restaurants = this.options.restaurants
				}
				
			} else {
				if(this.restaurant) {
					this.products = this.products.filter(x => {
						var name = x.name.toLowerCase();
						var search = arg.toLowerCase()
						return name.includes(search)
					});
				} else {
					this.restaurants = this.options.restaurants.filter(x => {
						var name = x.restaurant_name.toLowerCase();
						var search = arg.toLowerCase()
						return name.includes(search)
					});
				}
				
			}

			
			
		}
	},
    methods: {
        selectShop(arg){
			this.restaurant = arg

			this.products = arg.products
			
			this.list = arg.products.filter( x => { return x.category == this.activeCategory})

			this.forms = [];

			for (let i = 0; i < this.list.length; i++){
				this.forms.push(
					{
						product_id: this.list[i].id,
						restaurant_id: arg.id,
						quantity: 1,
						status: 'pending',
					}
				);
			}

		},

		addToCart(selected, index){
			axios.post(this.$root.route + "/customers/place-order", selected)
				.then(response => {
					this.forms[index].quantity = 1

					Inertia.get(
						this.$root.route + '/orders',
						{
							onSuccess: () => { },
						},
					);
				})
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
        },

		buyNow(arg, index, product) {
			this.orderDescription = arg;
			this.orderProduct = product;
			this.restaurant = this.restaurant

			this.openCheckoutModal()
		},

		openCheckoutModal(){
			this.showModalBuyNow = true

        },

        closeCheckoutModal(){
			this.showModalBuyNow = false
        },

		confirmBuyNow() {
            this.form.restaurant_id = this.restaurant.id
            this.form.order = this.orderDescription
            this.form.otherAddress = this.otherAddress

			if(!this.form.otherAddress) {
				delete this.form.address
			}
            
            axios.post(this.$root.route + "/orders/buy-now", this.form)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        
                        if(response.data.data.hasOwnProperty('url')) {
                            window.location.href = response.data.data.url
                        }
					}
				})
		}
    }
}
</script>
