<template>
    <Navigation :auth="auth">
        <div class="w-full min-h-screen h-full px-2 py-2 flex flex-col">
            <div :style="{width: !orders ? '100%' : '100%'}">
                <div class="w-full flex flex-row mt-5" style="height: 10vh">
                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'pending'">
                        <div class="w-full flex justify-center items-center">
                            <img :src="'/images/pending.png'" :class="{'--bg-gray': activeTab == 'pending'}"
                                :style="{'width': isMobile ? '50px': '80px', 'width': isMobile ? '50px': '80px'}"
                            />
                        </div>

                        <div class="w-full">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, 'text-md': isMobile}">
                                Pending
                            </p>
                        </div>
                    </div>

                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'to_process'">
                        <div class="w-full flex justify-center items-center">
                            <img :src="'/images/to_pay.png'" :style="{'width': isMobile ? '50px': '80px', 'width': isMobile ? '50px': '80px'}" :class="{'--bg-gray': activeTab == 'to_process'}"/>
                        </div>

                        <div class="w-full">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, 'text-md': isMobile}">
                                To Process
                            </p>
                        </div>
                    </div>

                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'to_deliver'">
                        <div class="w-full flex justify-center items-center">
                            <img :src="'/images/to_ship.png'" :style="{'width': isMobile ? '50px': '80px', 'width': isMobile ? '50px': '80px'}"  :class="{'--bg-gray': activeTab == 'to_deliver'}"/>
                        </div>

                        <div class="w-full cursor-pointer">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, 'text-md': isMobile}">
                                To Deliver
                            </p>
                        </div>
                    </div>

                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'to_receive'">
                        <div class="w-full flex justify-center items-center">
                            <img :src="'/images/to_receive.png'" :style="{'width': isMobile ? '50px': '80px', 'width': isMobile ? '50px': '80px'}"  :class="{'--bg-gray': activeTab == 'to_receive'}"/>
                        </div>

                        <div class="w-full">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, 'text-md': isMobile}">
                                To Receive
                            </p>
                        </div>
                    </div>

                    <div class="w-full flex flex-col cursor-pointer" @click="activeTab = 'received'">
                        <div class="w-full flex justify-center items-center">
                            <img :src="'/images/history.png'" :style="{'width': isMobile ? '50px': '80px', 'width': isMobile ? '50px': '90px'}"  :class="{'--bg-gray': activeTab == 'received'}"
                                style="height: 80p"
                            />
                        </div>

                        <div class="w-full">
                            <p class="text-center font-bold" :class="{'--text': !isMobile, 'text-md': isMobile}">
                                History
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="w-full px-10 pt-20" v-for="(orders, restaurant) in orders" :key="restaurant">
                <p class="font-bold" :class="{'--text': !isMobile, 'text-lg': isMobile}"> {{ restaurant }} </p>

                <div :class="{'grid' : !isMobile, 'grid-cols-5': !isMobile, 'gap-4': !isMobile, 'flex': isMobile, 'flex-col': isMobile}">
                    <div class="flex flex-col px-5 py-2" v-for="order in orders" :key="order.id">
                        <div class="w-full flex flex-col" style="border: 1px solid #E4B934">
                            <div class="w-full">
                                <input type="checkbox" class="ml-1 pt-1" :value="order" @change="selectOrder($event, order)" v-model="selectedOrders" v-if="activeTab == 'pending'">

                                <span class="float-right pt-1 pr-2 cursor-pointer" @click="orderSelected = order.id" v-if="activeTab == 'pending'">
                                    <i class="fa-solid fa-xmark"></i>
                                </span>
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
                                <div class="w-full py-1 px-1" v-if="activeTab == 'pending'">
                                    <input type="number" min="1" style="border: 1px solid #E4B934; width: 100%" class="text-center pt-2 pb-2" :value="order.quantity" @change="changeQuantity($event, order)">
                                </div>

                                <div class="w-full text-center py-1">
                                    <p class="text-lg mt-1">
                                        ₱{{ parseFloat(order.amount).toFixed(2)}}
                                    </p>
                                </div>
                            </div>

                            <div class="w-full flex flex-row" v-if="activeTab == 'to_receive'">
                                <button class="w-full" style="background: #E4B934; height: 40px" @click="orderReceived(order.reference)">
                                    Order Recieved
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full px-5 text-center text-lg flex justify-center items-center font-bold cursor-pointer" style="height: 5vh; background: #E4B934; position: fixed; bottom: 0; left: 0"
                v-if="selectedOrders.length > 0 && activeTab == 'pending'" @click="openCheckoutModal()"
            >
                <p>CHECKOUT</p>
            </div>

            <div id="checkoutModal" class="checkoutModal">
                <!-- Modal content -->
                <div class="checkout-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width' : isMobile ? '100%' : '20%'}">
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
                                ₱ {{ sumAmount(orders).toFixed(2)  }}
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
                                {{ gcashNumber }}
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
                                <input type="checkbox" v-model="otherAddress">
                            </div>
                        </div>

                        <div class="w-full pl-5 mt-2" v-if="otherAddress">
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
                                    <option :value="place.id" v-for="place in places" :key="place.id">{{ place.address}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="w-full pl-5 mt-4">
                            <button style="border-radius: 5px; width: 93%; background: #E4B934" class="py-2" @click="checkout()">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="removeModal" class="removeModal">
				<!-- Modal content -->
				<div class="remove-content flex flex-col" style="border: 2px solid #E4B934" :style="{'width' : isMobile ? '80%' : '20%'}">
					<div class="w-full text-lg font-bold text-center">
						Are you sure to delete this order ?
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
                            <button class="w-full py-1 text-black" style="border-radius: 5px; width: 93%; background: #E4B934"
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
                reference_number: null,
                address: null,
                orders: [],
                otherAddress: null,
                food_joint: null,
                user_id: null
            },
            activeFoodJoint: null,
            saveError: null,
            activeTab: 'pending',
            otherAddress: false,
            places: [],
            gcashNumber: null,
            isMobile: window.screen.width <= 700,
            orderSelected: null
        }
    },
    mounted() {
        this.orders = this.options.orders
    },
    watch: {
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
            this.getPlaces(arg)
        },
        otherAddress(arg) {
            this.form.address = null
        }
    },
    methods: {
        changeQuantity(evt,arg) {

            axios.post(this.$root.route + "/orders/change-quantity", data)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        this.orders = response.data.orders
					}
				})
        },

        removeProduct(id) {
            var data = { id: id}

            axios.post(this.$root.route + "/orders/remove-order", data)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        location.reload()
					}
				})
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
                        this.activeTab = 'to_process'
                        this.selectedOrders = []
                        this.closeCheckoutModal()
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
            var res =  _.sumBy(arr, function(arg) { return arg.amount; })

            return parseFloat(res);
        },

        getTotal(sub, fee) {
            var total = sub + fee;

            return parseFloat(total).toFixed(2)
        },

        getPlaces(arg) { 
            var data = [];
            
            axios.post(this.$root.route + "/orders/get-address", {address: arg})
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        this.places = response.data.places
                        this.gcashNumber = response.data.phone
					}
				})
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
</style>