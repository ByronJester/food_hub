<template>
    <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col">
			<div class="w-full mt-3" :class="{ 'px-5' : isMobile }">
				<input type="text" style="height: 50px; border: 1px solid black; border-radius: 10px;" class="px-5" :style="{width: isMobile ? '100%' : '300px'}"
					placeholder="Search..." v-model="search" :class="{'float-right' : !isMobile}"
				>
			</div>

            <div class="p-5 w-full" v-if="!restaurant"
				:class="{'grid' : !isMobile, 'grid-cols-5': !isMobile, 'gap-4': !isMobile, 'flex': isMobile, 'flex-col': isMobile}"
			>
				<div class="w-full --restaurant__list cursor-pointer" v-for="(arg, i) in restaurants" :key="i" 
					@click="selectShop(arg)"
				>
					<div>
						<img :src="'/images/uploads/' + arg.image" 
							style="height: 220px; width: 100%"
							class="p-5"
						>
					</div>

					<div class="text-center font-bold text-xl" :class="{'--text': !isMobile, 'text-lg': isMobile}">
						{{ arg.restaurant_name }}
					</div>
				</div>
			</div>

            <div class="w-full" v-else>
				<div class="w-full text-2xl font-bold px-5 pt-5">
					<span class="cursor-pointer" @click="restaurant = null" :class="{'--text': !isMobile, 'text-lg': isMobile}">
						<i class="fa-solid fa-arrow-left"></i> Back
					</span>

                    <span class="cursor-pointer float-right" @click="restaurant = null" :class="{'--text': !isMobile, 'text-lg': isMobile}">
						{{ restaurant.restaurant_name }}
					</span>
				</div>

				<div class="w-full relative">
					<div class="w-full">
						<img :src="'/images/uploads/' + restaurant.banner" 
							style="width: 100%;"
							class="p-5 relative"
							:style="{'height' : isMobile ? '250px' : '400px'}"
						>

						<img :src="'/images/uploads/' + restaurant.image" class="absolute"
							style="top: 17rem; left: 4rem; border: 1px solid #E4B934"
							:style="{'width': isMobile ? '40%': '20%', 'height' : isMobile ? '130px' : '180px', 'top' : isMobile ? '10rem': '17rem'}"
						>
					</div>

					<div class="w-full mt-5 px-5">
						<div class="flex flex-row float-right" style="width: 30%" :class="{'--text': !isMobile, 'text-lg': isMobile}">
							<div class="w-full cursor-pointer mx-2 text-center"
								style="border: 1px solid #E4B934;"
								:class="{'bg-yellow-500': activeCategory == 'Food'}"
								@click="activeCategory = 'Food'"
							>
								Foods
							</div>

							<div class="w-full cursor-pointer mx-2 text-center"
								style="border: 1px solid #E4B934;"
								:class="{'bg-yellow-500': activeCategory == 'Drink'}"
								@click="activeCategory = 'Drink'" 
							>
								Drinks
							</div>
						</div>
					</div>
				</div>

				<div class="w-full my-20 px-5">
					<div class="flex justify-center items-center"
						:class="{'grid' : !isMobile, 'grid-cols-5': !isMobile, 'gap-4': !isMobile, 'flex': isMobile, 'flex-col': isMobile}"
					>
						<div class="w-full flex flex-col" v-for="(product, index) in products.filter( x => { return x.category == activeCategory})" :key="product.id"
							style="border: 1px solid #E4B934"
						>
							<div class="w-full inline-flex mt-1" :style="{cursor: product.description ? 'pointer' : 'not-allowed'}">
								<p @click="product.description ? openDescriptionModal(product) : ''">
									<i class="fa-solid fa-eye fa-lg p-1"></i>
								</p>
							</div>

							<div class="w-full">
								<img class="w-full p-4" :src="'/images/uploads/' + product.image"
									style="height: 200px"
								/>
							</div>

							<div class="w-full text-center flex flex-col mb-2">
                                <div class="px-4" style="width: 100%">
                                    <button class="w-full py-1 cursor-default"
                                        style="border-radius: 5px; background: #000000"
                                    >
                                        <span class="px-2" :class="{'--text': !isMobile, 'text-lg': isMobile}"> 
                                            <b class="text-white mr-2">{{ product.name.toUpperCase() }}</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">₱{{ product.amount.toFixed(2) }}</b>
                                        </span>
                                    </button>
                                </div>

								<div class="px-4 mt-4" style="width: 100%">
                                    <button class="w-full py-1"
                                        style="border-radius: 5px; background: #000000"
										@click="buyNow(forms[index], index, product)"
                                    >
                                        <span class="px-2" :class="{'--text': !isMobile, 'text-lg': isMobile}"> 
                                            <b class="text-white mr-2">BUY</b><b style="background: #E4B934; border-radius: 5px" class="px-1 text-black">NOW</b>
                                        </span>
                                    </button>
                                </div>

								<div class="w-full flex flex-row mt-2 justify-center items-center">
									<div class="pr-1 w-full" style="width: 15%">
										<input type="number" min="1" style="border: 1px solid #E4B934; width: 100%" class="text-center pt-2 pb-2" v-model="forms[index].quantity">
									</div>

									<div class="pr-4 w-full" style="width: 15%">
										<button class="w-full cursor-poineter"
											:class="{'--text': !isMobile, 'text-lg': isMobile}"
											style="border-radius: 5px; background: #E4B934"
											@click="addToCart(forms[index], index)"
										>
											<i class="fa-solid fa-cart-shopping pt-3 pb-2" style="color: #000000"></i> 
										</button>
									</div>
								</div>
							</div>
						</div> 
					</div>
				</div>

				<div id="descriptionModal" class="descriptionModal" v-if="productName">
					<div class="description-content flex flex-col" style="width: 20%; border: 2px solid #E4B934">
						<div class="w-full">
							<span class="text-4xl font-bold">
								{{productName}}
							</span>

							<span class="float-right cursor-pointer"
								@click="closeDescriptionModal()"
							>
								<i class="fa-solid fa-xmark"></i>
							</span>
						</div>

						<div class="w-full flex flex-col mt-4">
							<div class="w-full flex flex-col justify-center items-center">
								<div class="w-full">
									<img class="w-full p-4" :src="productImage"
										style="height: 200px; border: 2px solid #E4B934"
									/>
								</div>

								<div class="w-full">
									<p class="w-full text-center text-4xl">
										₱ {{ productAmount.toFixed(2) }}
									</p>
								</div>
							
								
							</div>

							<div class="w-full mt-5">
								<p>
									{{ description }}
								</p>
							</div>
						</div>
					</div>
				</div>

				<div id="checkoutModal" class="checkoutModal" v-if="!!orderProduct && orderProduct.name">
					<div class="checkout-content flex flex-col" style="width: 20%; border: 2px solid #E4B934">
						<div class="w-full">
							<span class="text-xl font-bold">
								Checkout Order
							</span>

							<span class="float-right cursor-pointer"
								@click="closeCheckoutModal()"
							>
								<i class="fa-solid fa-xmark"></i>
							</span>
						</div>

						<div class="w-full flex flex-col mt-4">
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
									₱ {{ (orderProduct.amount * orderDescription.quantity).toFixed(2)  }}
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
									₱ {{ ((orderProduct.amount * orderDescription.quantity) + 60).toFixed(2) }}
								</div>
							</div>

							<div class="w-full flex flex-row mt-5">
								<div class="w-full flex justify-center items-center">
									Mode of Payment:
								</div>

								<div class="w-full flex justify-center items-center">
									<input class="mr-1" type="radio" value="cod" v-model="form.payment_method" />
									<label class="mr-1">COD</label>

									<input class="mr-1" type="radio" value="gcash" v-model="form.payment_method" />
									<label>G-Cash</label>
								</div>
							</div>

							<div class="w-full flex flex-row mt-5" v-if="form.payment_method == 'gcash'">
								<div class="w-full flex justify-center items-center">
									Owner G-Cash #:
								</div>

								<div class="w-full flex justify-center items-center">
									{{ restaurant.phone }}
								</div>
							</div>

							<div class="w-full pl-5 mt-2" v-if="form.payment_method == 'gcash'">
								<input v-model="form.reference_number" style="height: 30px; border: 1px solid black; border-radius: 5px; width: 93%; padding: 5px"
									placeholder="G-Cash Ref. No."
									class="text-center"
								>
								<span class="text-xs text-red-500">{{validationError('reference_number', saveError)}} </span>
							</div>

							<div class="w-full flex flex-row mt-5">
								<div class="w-full flex justify-start items-center pl-5">
									Other Address:
								</div>

								<div class="w-full flex justify-start items-center pl-5">
									<input type="checkbox" v-model="form.otherAddress">
								</div>
							</div>

							<div class="w-full pl-5 mt-2" v-if="form.otherAddress">
								<input v-model="form.address" style="height: 30px; border: 1px solid black; border-radius: 5px; width: 93%; padding: 5px"
									placeholder="Street No, Barangay, Town"
									class="text-center"
								>
								<span class="text-xs text-red-500">{{validationError('address', saveError)}} </span>
							</div>

							<div class="w-full flex flex-col mt-5">
								<div class="w-full flex justify-start items-center pl-5">
									Available Address:
								</div>

								<div class="w-full flex justify-start items-center px-5">
									<select class="w-full" style="border: 1px solid black; height: 30px">
										<option v-for="p in restaurant.places" :value="p.id" :key="p.id">
											{{p.address}}
										</option>
									</select>
								</div>
							</div>

							<div class="w-full pl-5 mt-4">
								<button style="border-radius: 5px; width: 93%; background: #E4B934" class="py-2" @click="confirmBuyNow()">
									Confirm
								</button>
							</div>

						</div>
					</div>
				</div>
			</div>

			
        </div>
    </Navigation>
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
			orderDescription: {},
			orderProduct: {},
			form: {
				payment_method: 'cod',
				reference_number: null,
				otherAddress: false,
				address: null,
				order: null,
				user_id: null,
				restaurant_id: null
			},
			saveError: null
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
            var modal = document.getElementById("descriptionModal");

            modal.style.display = "block";

            this.description = arg.description
            this.productName = arg.name
            this.productImage = '/images/uploads/' + arg.image
            this.productAmount = arg.amount
        },

        closeDescriptionModal(){
            var modal = document.getElementById("descriptionModal");

            modal.style.display = "none";

            this.description = null
            this.productName = null
        },

		buyNow(arg, index, product) {
			this.orderDescription = Object.assign({}, arg);
			this.orderProduct = Object.assign({}, product);
			this.restaurant = this.restaurant

			this.openCheckoutModal()
		},

		openCheckoutModal(){
            var modal = document.getElementById("checkoutModal");

            modal.style.display = "block";

        },

        closeCheckoutModal(){
            var modal = document.getElementById("checkoutModal");

            modal.style.display = "none";
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
                        // this.closeCheckoutModal()

						location.reload()
					}
				})
		}
    }
}
</script>

<style scoped>
.--restaurant__list{
	border: 1px solid #E4B934;
	border-radius: 5px;
	height: 250px;
}

.--text {
	font-size: calc(.1em + 1vw);
}

.descriptionModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 40%;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
}

/* Modal Content */
.description-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.checkoutModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.checkout-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}


/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
</style>