<template>
    <Navigation :auth="auth">
        <div class="w-full min-h-screen h-full px-2 py-2 flex flex-col --main--div">
            <div :style="{width: !orders ? '100%' : '100%'}">
                <div class="w-full flex flex-row mt-5" style="height: 10vh">
                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'oncart'">
                        <div class="w-full flex justify-center items-center relative">
                            <img :src="'/images/icons/tray.png'" :class="{'--bg-gray': activeTab == 'oncart'}"
                                :style="{'width': isMobile ? '30px': '100px'}"
                            />
                            <span class="absolute" style="color:red; top: -1vw; left: 4.1vw; font-size: 1vw ; font-weight: 700" v-if="options.tray > 0"> 
                                {{options.tray}}
                            </span>
                        </div>

                        <div class="w-full">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, '--xs': isMobile}">
                                Tray
                            </p>
                        </div>
                    </div>

                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'pending'">
                        <div class="w-full flex justify-center items-center relative">
                            <img :src="'/images/icons/pending.png'" :class="{'--bg-gray': activeTab == 'pending'}"
                                :style="{'width': isMobile ? '30px': '100px'}"
                            />

                            <span class="absolute" style="color:red; top: -1vw; left: 4.1vw; font-size: 1vw ; font-weight: 700" v-if="options.pending > 0"> 
                                {{options.pending}}
                            </span>
                        </div>

                        <div class="w-full">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, '--xs': isMobile}">
                                Pending
                            </p>
                        </div>
                    </div>

                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'to_process'">
                        <div class="w-full flex justify-center items-center relative">
                            <img :src="'/images/icons/process.png'" :style="{'width': isMobile ? '30px': '100px'}" :class="{'--bg-gray': activeTab == 'to_process'}"/>
                            
                            <span class="absolute" style="color:red; top: -1vw; left: 4.1vw; font-size: 1vw ; font-weight: 700" v-if="options.processing > 0"> 
                                {{options.processing}}
                            </span>
                        </div>

                        <div class="w-full">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, '--xs': isMobile}">
                                Preparing
                            </p>
                        </div>
                    </div>

                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'to_deliver'">
                        <div class="w-full flex justify-center items-center relative">
                            <img :src="'/images/icons/deliver.png'" :style="{'width': isMobile ? '30px': '100px'}"  :class="{'--bg-gray': activeTab == 'to_deliver'}"/>

                            <span class="absolute" style="color:red; top: -1vw; left: 4.1vw; font-size: 1vw ; font-weight: 700" v-if="options.deliver > 0"> 
                                {{options.deliver}}
                            </span>
                        </div>

                        <div class="w-full cursor-pointer">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, '--xs': isMobile}">
                                To Deliver
                            </p>
                        </div>
                    </div>

                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'to_receive'">
                        <div class="w-full flex justify-center items-center relative">
                            <img :src="'/images/icons/receive.png'" :style="{'width': isMobile ? '30px': '100px'}"  :class="{'--bg-gray': activeTab == 'to_receive'}"/>

                            <span class="absolute" style="color:red; top: -1vw; left: 4.1vw; font-size: 1vw ; font-weight: 700" v-if="options.receive > 0"> 
                                {{options.receive}}
                            </span>
                        </div>

                        <div class="w-full">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, '--xs': isMobile}">
                                To Receive
                            </p>
                        </div>
                    </div>
                    

                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'received'">
                        <div class="w-full flex justify-center items-center">
                            <img :src="'/images/icons/history.png'" :style="{'width': isMobile ? '30px': '100px'}"  :class="{'--bg-gray': activeTab == 'received'}"
                                
                            />
                        </div>

                        <div class="w-full">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, '--xs': isMobile}">
                                Order History
                            </p>
                        </div>
                    </div>

                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'cancel'">
                        <div class="w-full flex justify-center items-center">
                            <img :src="'/images/icons/cancel.png'" :style="{'width': isMobile ? '30px': '100px'}"  :class="{'--bg-gray': activeTab == 'cancel'}"
                                
                            />
                        </div>

                        <div class="w-full">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, '--xs': isMobile}">
                                Cancelled Orders
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="w-full px-10 pt-20" v-for="(orders, restaurant) in orders" :key="restaurant">
                <p class="font-bold" style="text-transform: capitalize;" :class="{'--text': !isMobile, 'text-lg': isMobile}"> {{ restaurant }} </p>

                <div :class="{'grid' : !isMobile, 'grid-cols-5': !isMobile, 'gap-4': !isMobile, 'flex': isMobile, 'flex-col': isMobile}">
                    <div class="flex flex-col px-5 py-2" v-for="order in orders" :key="order.id">
                        <div class="w-full flex flex-col" style="border: 1px solid #E4B934">
                            <div class="w-full">
                                <input type="checkbox" class="ml-1 pt-1" :value="order" @change="selectOrder($event, order)" v-model="selectedOrders" v-if="activeTab == 'oncart'">

                                <button class="float-right pt-1 pr-2 cursor-pointer" @click="orderSelected = order"
                                    :class="{'cursor-not-allowed': !!order && (order.payment_method == 'gcash' || !cancelStatus.includes(order.status))}"
                                    :disabled="!!order && (order.payment_method == 'gcash' || !cancelStatus.includes(order.status))" 
                                    v-if="order.status == 'oncart' || order.status == 'pending'"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            <div class="w-full">
                                <p class="text-center font-bold" :class="{'--text': !isMobile, 'text-md': isMobile}">
                                    {{ order.product.name }}
                                </p>
                            </div>

                            <div class="w-full h-full flex justify-center items-center my-2">
                                <img :src="'/images/uploads/' + order.product.image" style="width: 200px; height: 150px; border-radius: 5px" class="p-1"/>
                            </div>

                            <div class="w-full flex flex-row">
                                <div class="w-full py-1 px-1" v-if="activeTab == 'oncart'">
                                    <input type="number" min="1" style="border: 1px solid #E4B934; width: 100%" class="text-center pt-2 pb-2" :value="order.quantity" @change="changeQuantity($event, order)">
                                </div>

                                <div class="w-full text-center py-1">
                                    <p class="text-lg mt-1">
                                        ₱{{ parseFloat(order.amount).toFixed(2)}}
                                    </p>

                                    <p class="text-lg mt-2" v-if="!!order.reason">
                                        {{ order.reason}}
                                    </p>

                                    <p class="text-lg mt-2" v-if="order.status == 'received'">
                                        {{ order.display_date }}
                                    </p>

                                    <!-- <p class="text-lg mt-1" v-else>
                                        ₱{{ (parseFloat(order.amount) + parseFloat(60)).toFixed(2) }}
                                    </p> -->
                                </div>
                            </div>

                            <div class="w-full flex flex-row" v-if="activeTab == 'to_receive'">
                                <button class="w-full" style="background: #E4B934; height: 40px" @click="confirmRecieved(order.reference)">
                                    Order Received
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full px-5 text-center text-lg flex justify-center items-center font-bold cursor-pointer" style="height: 5vh; background: #E4B934; position: fixed; bottom: 0; left: 0"
                v-if="selectedOrders.length > 0 && activeTab == 'oncart'" @click="openCheckoutModal()"
            >
                <p>CHECKOUT</p>
            </div>

            <div id="checkoutModal" class="checkoutModal">
                <!-- Modal content -->
                <div class="checkout-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width' : isMobile ? '85%' : '30%'}">
                    <div class="w-full">
                        <span class="text-lg font-bold">
                           Checkout
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
                                :clickable="otherAddress"
                                :draggable="otherAddress"
                                @click="clickMap"
                                @dragend="showLocation" 
                            ></gmap-marker>
                        </gmap-map>
                       
                    </div>

                    <div class="w-full mt-5" v-for="(orders, restaurant) in groupOrders" :key="restaurant">
                        <p class="text-md font-bold"> {{ restaurant }} </p>

                        <div class="flex flex-col px-5 py-2" v-for="order in orders" :key="order.id">
                            <div class="w-full flex flex-row" style="border-bottom: 1px solid #E4B934">
                                <div class="h-full flex justify-center items-center my-2" style="width: 40%">
                                    <img :src="'/images/uploads/' + order.product.image" style="width: 100%; height: 80px;border: 1px solid #E4B934" class="p-1" />
                                </div>

                                <div class="flex flex-col justify-center items-center" style="width: 60%">
                                    <div class="w-full text-center">
                                        <p class="text-md font-bold">
                                            {{ order.product.name }}
                                        </p>
                                    </div>
                                    
                                    <div class="w-full text-center">
                                        <p class="text-md mt-1">
                                            ₱{{ parseFloat(order.amount).toFixed(2)}}
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
                                ₱ {{ parseFloat(sumAmount(orders)).toFixed(2)  }}
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
                                ₱ {{ getTotal(sumAmount(orders), 60) }}
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
                                <input type="checkbox" v-model="otherAddress">
                            </div> 
                        </div>

                        <div class="w-full pl-5 mt-2" v-if="otherAddress">
                            <input style="height: 30px; border: 1px solid black; border-radius: 5px; width: 100%; padding: 5px; text-transform: capitalize;"
									placeholder="Street, Barangay, Town" type="text"
									class="text-center" v-model="form.address"
									:disabled="true"
								>
                            <span class="text-xs text-red-500">{{validationError('address', saveError)}} </span>
                        </div>


                        <div class="w-full pl-5 mt-4">
                            <button style="border-radius: 5px; width: 100%; background: #E4B934" class="py-2" @click="confirmCheckout()"
                                :class="{'cursor-not-allowed': getDistanceFromLatLonInKm(coordinates.latitude, coordinates.longitude, retaurantCoordinates.latitude, retaurantCoordinates.longitude) > 10 || checkoutDisabled}"
                                :disabled="getDistanceFromLatLonInKm(coordinates.latitude, coordinates.longitude, retaurantCoordinates.latitude, retaurantCoordinates.longitude) > 10 || checkoutDisabled"
                            >
                                Confirm
                            </button>

                            <span class="text-xs text-red-500" v-if="getDistanceFromLatLonInKm(coordinates.latitude, coordinates.longitude, retaurantCoordinates.latitude, retaurantCoordinates.longitude) > 10">
                                Your address is too far on {{ restaurant }} address. Maximum delivery address is 10km.
                            </span>
                        </div>


                    </div>
                </div>
            </div>

            <div id="removeModal" class="removeModal">
				<!-- Modal content -->
				<div class="remove-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width' : isMobile ? '80%' : '20%'}">
					<div class="w-full text-lg font-bold text-center">
						Are you sure to cancel this order ?
					</div>

					<div class="w-full flex flex-row mt-10">
                        <div class="w-full">
                            <button class="w-full py-1 text-white" style="border-radius: 5px; width: 93%; background: #000000"
                                @click="closeRemoveModal();"
                            >
                                No
                            </button>
                        </div>

                        <div class="w-full">
                            <button class="w-full py-1 text-black" style="border-radius: 5px; width: 100%; background: #E4B934"
                                @click="removeProduct(orderSelected)"
                            >
                                Yes
                            </button>
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
import _ from 'lodash';

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
    },
    data(){
        return {
            orders: [],
            selectedOrders: [],
            groupOrders: null, 
            form: {
                payment_method: 'cod',
                address: null,
                orders: [],
                otherAddress: null,
                food_joint: null,
                user_id: null,
                lat: null,
                long: null
            },
            activeFoodJoint: null,
            saveError: null,
            activeTab: 'oncart',
            otherAddress: false,
            places: [],
            gcashNumber: null,
            isMobile: window.screen.width <= 700,
            orderSelected: null,
            coordinates : {
                latitude: 10,
                longitude: 10
            },
            retaurantCoordinates: {
                latitude: 10,
                longitude: 10
            },
            checkoutDisabled: false,
            cancelStatus: ['pending', 'oncart']
        }
    },
    created() {
        this.orders = this.options.orders

        this.getCoordinates(this.auth.address, 'customer')
    },
    watch: {
        'form.address'(arg) {
            
        },
        orderSelected(arg) {
            if(!arg) return;

            this.openRemoveModal()
        },
        selectedOrders(arg){
        },

        'form.payment_method'(arg) {
            this.form.reference_number = null
        },

        activeTab(arg) {
            axios.post(this.$root.route + "/orders/active-tab", {status: arg})
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        this.orders = response.data.orders
					}
				})
        },
        activeFoodJoint(arg) {
            this.getAddress(arg)
        },
        otherAddress(arg) {
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
            this.checkoutDisabled = true

            var self = this

            if(!e.target.value) {
                self.form.address = null

                self.getCoordinates(self.auth.address, 'customer')
            } 

            clearTimeout(self.timeOut);

            this.timeOut = setTimeout(
                function(){
                    self.form.address = e.target.value

                    self.getCoordinates(e.target.value, 'customer')
                    
                }
            , 2000);
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

            console.log(d)
            return d;
        },

        deg2rad(deg) {
            return deg * (Math.PI/180)
        },

        changeQuantity(evt,arg) {
            var data = { id: arg.id, product_id: arg.product_id, quantity: evt.target.value}
            
            axios.post(this.$root.route + "/orders/change-quantity", data)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        // this.orders = response.data.orders
                        location.reload()
					}
                    
				})
        },

        removeProduct(order) {
            var data = { id: order.id, reference: order.reference }

            axios.post(this.$root.route + "/orders/remove-order", data)
				.then(response => {
					 location.reload()
				})
        },

        confirmCheckout(){
            swal({
                title: 'Are you sure to checkout this order ?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((proceed) => {
                if (proceed) {
                    this.checkout()
                } else {
                    
                }
            });
        },

        checkout(){
            this.form.user_id = this.auth.id
            this.form.food_joint = this.activeFoodJoint
            this.form.orders = this.selectedOrders
            this.form.otherAddress = this.otherAddress
            
            axios.post(this.$root.route + "/orders/checkout-order", this.form)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        
                        if(!!response.data.url) {
                            window.location.href = response.data.url;
                        } else {
                            this.activeTab = 'pending'
                            this.selectedOrders = []
                            this.closeCheckoutModal()
                        }
                        

					}
				})
        },

        openCheckoutModal(){
            var modal = document.getElementById("checkoutModal");

            modal.style.display = "block";

            this.groupOrders = _.mapValues(_.groupBy(this.selectedOrders, 'food_hub'), 
                          clist => clist.map(order => _.omit(order, 'food_hub')));
        },

        closeCheckoutModal(){
            var modal = document.getElementById("checkoutModal");

            modal.style.display = "none";
        },

        arrayGroupBy(items, key) {
            return items.reduce((hash, obj) => {
                if(obj[key] === undefined) return hash; 
                return Object.assign(hash, { [obj[key]]:( hash[obj[key]] || [] ).concat(obj)})
                }, {})
        },

        selectOrder(evt, arg) {
            if(!evt.target.checked) {
                this.selectedOrders = this.selectedOrders.filter(x => {
                    return x.id != arg.id;
                })
            } else {
                if(!this.activeFoodJoint) {
                    this.selectedOrders.push(arg)

                    this.activeFoodJoint = arg.food_hub
                } else {
                    if(this.activeFoodJoint != arg.food_hub) {
                        this.activeFoodJoint = arg.food_hub
                        this.selectedOrders = []
                        this.selectedOrders.push(arg)
                    } else {
                        this.selectedOrders.push(arg)
                    }
                }
            }

            this.selectedOrders = _.uniq(this.selectedOrders);
        },

        sumAmount(arr) {
            var res =  _.sumBy(arr, function(arg) { return parseFloat(arg.amount); })

            return parseFloat(res);
        },

        getTotal(sub, fee) {
            var total = parseFloat(sub) + parseFloat(fee);

            return parseFloat(total).toFixed(2)
        },

        getAddress(arg) {   
            var data = [];
            
            axios.post(this.$root.route + "/orders/get-address", {address: arg})
				.then(response => {
					this.getCoordinates(response.data.address, 'foodhub')
				})
        },

        confirmRecieved(arg){
            swal({
                title: 'Are you sure to received this order ?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((proceed) => {
                if (proceed) {
                    this.orderReceived(arg)
                } else {
                    
                }
            });
        },

        orderReceived(arg) {
            axios.post(this.$root.route + "/orders/order-received", {reference: arg})
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        location.reload()
					}
				})
        },

        openRemoveModal(){
            var modal = document.getElementById("removeModal");

            modal.style.display = "block";

        },

        closeRemoveModal(){
            var modal = document.getElementById("removeModal");

            modal.style.display = "none";

            this.orderSelected = null
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
.--text {
	font-size: calc(.1em + 1vw);
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

.removeModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 20%;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
}

/* Modal Content */
.remove-content {
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

.--bg-gray {
    background: #C0C0C0;
}

.--xs {
    font-size: 8px;
}
</style>