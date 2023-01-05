<template>
    <Navigation :auth="auth">
        <div class="w-full h-full px-2 py-2 flex flex-col --main--div">
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

					<div class="text-center font-bold text-xl" :class="{'--text': !isMobile, 'text-lg': isMobile}" style="text-transform: capitalize;">
						{{ arg.restaurant_name }}
					</div>

					<div class="text-center font-bold text-md">
						{{ arg.opening }} - {{ arg.closing }}
					</div>
				</div>
			</div>

            <div class="w-full" v-else>
				<div class="w-full text-2xl font-bold px-5 pt-5">
					<span class="cursor-pointer" @click="restaurant = null" :class="{'--text': !isMobile, 'text-lg': isMobile}">
						<i class="fa-solid fa-arrow-left"></i> Back
					</span>

                    <span class="float-right" :class="{'--text': !isMobile, 'text-lg': isMobile}" style="text-transform: capitalize;">
						{{ restaurant.restaurant_name }} ({{ restaurant.address }})
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

					<div class="w-full mt-5 md:px-5">
						<div class="flex flex-row float-right mr-8" style="width: 30%">
							<div class="w-full cursor-pointer mx-2 text-center"
								style="border: 1px solid #E4B934;"
								:class="{'bg-yellow-500': activeCategory == 'Food', '--text': !isMobile, 'text-lg': isMobile}"
								@click="activeCategory = 'Food'"
							>
								Foods
							</div>

							<div class="w-full cursor-pointer text-center"
								style="border: 1px solid #E4B934;"
								:class="{'bg-yellow-500': activeCategory == 'Drink', '--text': !isMobile, 'text-lg': isMobile}"
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
							:class="{'mb-2': isMobile}"
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
                                            <b class="text-white mr-2">{{ product.name.toUpperCase() }}</b>
                                        </span>
                                    </button>

									<button class="w-full py-1 cursor-default mt-2"
                                        style="border-radius: 5px; border: 1px solid black"
                                    >
                                        <span class="px-2" :class="{'--text': !isMobile, 'text-lg': isMobile}"> 
                                            <b style="background: #FFFFFF; border-radius: 5px" class="px-1 text-black">₱{{ parseFloat(product.amount).toFixed(2) }}</b>
                                        </span>
                                    </button>
                                </div>

								<div class="px-4 mt-4 flex flex-row" style="width: 100%;">
									<div class="pr-1" style="width: 20%">
										<input type="number" min="1" style="border: 1px solid #E4B934; width: 100%" class="text-center pt-2 pb-2" v-model="forms[index].quantity">
									</div>

									<div class="pr-1" style="width: 20%">
										<button class="w-full cursor-poineter"
											:class="{'--text': !isMobile, 'text-lg': isMobile, 'cursor-not-allowed': !!restaurant.lock || !product.is_active}"
											style="border-radius: 5px; background: #E4B934"
											:disabled="!!restaurant.lock || !product.is_active"
											@click="addToCart(forms[index], index)"
										>
											<i class="fa-solid fa-cart-shopping pt-3 pb-2"></i> 
										</button>
									</div>

									<div class="" style="width: 60%">
										<button class="w-full py-1 text-center"
											style="border-radius: 5px; background: #000000"
											@click="buyNow(forms[index], index, product)" 
											:disabled="!!restaurant.lock || !product.is_active"
											:class="{'--text': !isMobile, 'text-lg': isMobile, 'cursor-not-allowed': !!restaurant.lock || !product.is_active}"
										>
											<span class="px-2" :class="{'--text': !isMobile, 'text-lg': isMobile}" > 
												<b class="text-white mr-2" style="">BUY NOW</b>
											</span>
										</button>
									</div>
                                </div>

								<!-- <div class="w-full flex flex-row mt-2 justify-center items-center">
									
								</div> -->
							</div>
						</div> 
					</div>
				</div>

				<div id="descriptionModal" class="descriptionModal">
					<div class="description-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width': isMobile ? '90%': '40%'}"> 
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
										style="height: 400px; border: 2px solid #E4B934"
									/>
								</div>

								<div class="w-full">
									<p class="w-full text-center text-4xl">
										₱ {{ parseFloat(productAmount).toFixed(2) }}
									</p>
								</div>
							
								
							</div>

							<div class="w-full mt-5 text-2xl">
								<p>
									{{ description }}
								</p>
							</div>
						</div>
					</div>
				</div>

				<div id="checkoutModal" class="checkoutModal overflow-y-scroll">
					<div class="checkout-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width': isMobile ? '85%': '30%'}">
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

						<div class="w-full mt-5">
							<gmap-map 
								:center="{lat: coordinates.latitude, lng: coordinates.longitude}"
								:zoom="15"
								style="width: 100%; height: 320px; border: 1px solid #E4B934"
							>
								<gmap-marker
									:position="{lat: coordinates.latitude, lng: coordinates.longitude}"
									:clickable="form.otherAddress"
									:draggable="form.otherAddress"
									@click="clickMap"
									@dragend="showLocation" 
								></gmap-marker>
							</gmap-map>
						
						</div>

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
												₱ {{ parseFloat(orderProduct.amount).toFixed(2) }}
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

							<div class="w-full flex flex-row">
								<div class="w-full text-left pl-5">
									Mode of Payment:
								</div>

								<div class="w-full text-left pl-5">
									<div class="w-full flex">
										<input class="mr-1" type="radio" value="cod" v-model="form.payment_method" v-if="!auth.is_reported"/>
										<label class="mr-1" v-if="!auth.is_reported">COD</label>

										<input class="mr-1" type="radio" value="gcash" v-model="form.payment_method" />
										<label>G-Cash</label>
									</div>
								</div>
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
								<input style="height: 30px; border: 1px solid black; border-radius: 5px; width: 100%; padding: 5px; text-transform: capitalize;"
									placeholder="Street, Barangay, Town" type="text"
									class="text-center" v-model="form.address"
									:disabled="true"
								>
								<span class="text-xs text-red-500">{{validationError('address', saveError)}} </span>
							</div>


							<div class="w-full pl-5 mt-4">
								<button style="border-radius: 5px; width: 100%; background: #E4B934" class="py-2" @click="buyNowConfirm()"
									:class="{'cursor-not-allowed': getDistanceFromLatLonInKm(coordinates.latitude, coordinates.longitude, retaurantCoordinates.latitude, retaurantCoordinates.longitude) > 10 || checkoutDisabled}"
                                	:disabled="getDistanceFromLatLonInKm(coordinates.latitude, coordinates.longitude, retaurantCoordinates.latitude, retaurantCoordinates.longitude) > 10 || checkoutDisabled"
								>
									Confirm
								</button>

								<span class="text-xs text-red-500" v-if="getDistanceFromLatLonInKm(coordinates.latitude, coordinates.longitude, retaurantCoordinates.latitude, retaurantCoordinates.longitude) > 10">
									Your address is too far on {{ restaurant.name }} address. Maximum delivery address is 10km.
								</span>
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
			orderDescription: null,
			orderProduct: null,
			form: {
				payment_method: 'cod',
				otherAddress: false,
				address: null,
				order: null,
				user_id: null,
				restaurant_id: null,
				lat: null,
				long: null
			},
			saveError: null,
			coordinates : {
                latitude: 10,
                longitude: 10
            },
            retaurantCoordinates: {
                latitude: 10,
                longitude: 10
            },
			checkoutDisabled: false
        }
    },
    created(){
        this.restaurants = this.options.restaurants

		this.getCoordinates(this.auth.address, 'customer')
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
		},
		'form.otherAddress'(arg) {
			if(!arg) {
				// this.getCoordinates(this.auth.address, 'customer')
				this.checkoutDisabled = false
			}
		}
	},
    methods: {
		getCoordinates(address, type){
            axios.get(`https://api.tomtom.com/search/2/geocode/${address}.json?key=hJqRLbgHIo29NdQ2CESAH8lDNN96vJ3E`)
				.then(response => {
                    if(type == 'customer') {
                        this.coordinates.latitude = response.data.results[0].position.lat
                        this.coordinates.longitude = response.data.results[0].position.lon
                    } else {
                        this.retaurantCoordinates.latitude = response.data.results[0].position.lat
                        this.retaurantCoordinates.longitude = response.data.results[0].position.lon
                    }

					this.checkoutDisabled = false
					
				})
        },
        initiateSearch(e) {
			// this.checkoutDisabled = true

            // var self = this

            // if(!e.target.value) {
            //     self.form.address = null

            //     self.getCoordinates(self.auth.address, 'customer')
            // } 

            // clearTimeout(self.timeOut);

            // this.timeOut = setTimeout(
            //     function(){
            //         self.form.address = e.target.value

            //         self.getCoordinates(e.target.value, 'customer')
                    
            //     }
            // , 2000);
        },
        getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
            var R = 6371; // Radius of the earth in km
            var dLat = this.deg2rad(lat2-lat1);  // deg2rad below
            var dLon = this.deg2rad(lon2-lon1); 
            var a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) * 
                Math.sin(dLon/2) * Math.sin(dLon/2)
                ; 
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
            var d = R * c; // Distance in km

            return d;
        },

        deg2rad(deg) {
            return deg * (Math.PI/180)
        },
        selectShop(arg){
			this.restaurant = arg

			this.getCoordinates(arg.address, 'foodhub')

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

					// Inertia.get(
					// 	this.$root.route + '/orders',
					// 	{
					// 		onSuccess: () => { },
					// 	},
					// );
					swal({
						title: "Successful",
						text: "Added to tray successfully",
						icon: "success",
					});
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
			this.orderDescription = arg;
			this.orderProduct = product;
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

		buyNowConfirm(){
			swal({
                title: 'Are you sure to buy this menu ?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((proceed) => {
                if (proceed) {
                    this.confirmBuyNow()
                } else {
                    
                }
            });
		},

		confirmBuyNow() {
			console.log(this.form)
            this.form.restaurant_id = this.restaurant.id
            this.form.order = this.orderDescription

			// if(!this.form.otherAddress) {
			// 	delete this.form.address
			// }
			
            
            axios.post(this.$root.route + "/orders/buy-now", this.form)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        
						if(!!response.data.url) {
							window.location.href = response.data.url;
						} else {
							this.closeCheckoutModal()

							// location.reload()
							swal({
								title: "Successful",
								text: "You successfully buy this menu.",
								icon: "success",
							});
						}
						
					}
				})
		},
		clickMap(evt){
			
		},
		showLocation(evt){
			var coordinates = evt.latLng.toString()
			var coordinates = coordinates.replace(/[- )(]/g,'')

			var c = coordinates.split(',')
			var lat = c[0]
			var long = c[1]

			this.coordinates.latitude = parseFloat(lat)
			this.coordinates.longitude = parseFloat(long)

			this.form.lat = parseFloat(lat)
			this.form.long = parseFloat(long)

			axios.get(`https://api.tomtom.com/search/2/reverseGeocode/${coordinates}.json?key=hJqRLbgHIo29NdQ2CESAH8lDNN96vJ3E&radius=100`)
				.then(response => {
					this.form.address = response.data.addresses[0].address.freeformAddress
				})

			
		}
    }
}
</script>

<style scoped>
.--restaurant__list{
	border: 1px solid #E4B934;
	border-radius: 5px;
	height: 100%;
}

.--text {
	font-size: calc(.1em + 1vw);
}

.descriptionModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 10%;
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