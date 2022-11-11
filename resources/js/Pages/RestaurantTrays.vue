<template>
    <Navigation :auth="auth">
        <div class="w-full min-h-screen h-full px-2 py-2 flex flex-col">
            <div class="w-full mt-10">
                <Table :columns="columns" :rows="orders" :keys="keys" :selected.sync="selected" :style="{opacity: selected ? '0.6' : '1'}"/>

                <div id="orderModal" class="orderModal h-full">
                    <!-- Modal content -->
                    <div class="order-content flex flex-col" style="width: 20%; border: 2px solid #E4B934" v-if="selected">
                        <div class="w-full">
                            <span class="text-lg font-bold">
                                Orders
                            </span>

                            <span class="float-right cursor-pointer"
                                @click="closeOrderModal()"
                            >
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full flex-col mt-4">
                            <div class="w-full mt-2" v-for="o in selected.orders" :key="o.id">
                                <p class="w-full">
                                    <span>
                                        {{ o.product.name }}
                                    </span>

                                    <span class="float-right">
                                        {{ o.quantity }} order
                                    </span>
                                </p>
                            </div>

                            <div class="w-full mt-5">
                                <select v-model="form.status" class="w-full" style="border: 1px solid black; height: 40px; border-radius: 5px">
                                    <option :value="'to_deliver'">To Deliver</option>
                                    <option :value="'to_receive'">To Receive</option>
                                </select>
                            </div>

                            <div class="w-full mt-4">
                                <button class="w-full py-2" style="background: #E4B934" @click="save()">
                                    SAVE
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
import Table from "../Components/Table";

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Table
    },
    data(){
        return {
            orders: [],
            selected: null,
            columns: [
                'Customer Name', 'Payment Method', 'G-Cash Ref. No.', 'Address', 'Status', 'Amount', 'Shipping Fee'
            ],
            keys : [
                {
                    label: 'customer_name',
                },
                {
                    label: 'payment_method',
                },
                {
                    label: 'reference_number',
                },
                {
                    label: 'address',
                },
                {
                    label: 'display_status',
                },
                {
                    label: 'amount',
                },
                {
                    label: 'shipping_fee',
                },
            ],
            form: {
                reference: null,
                status: 'to_deliver'
            }
        }
    },

    created(){
        this.orders = this.options.orders.map( x => {
            x['customer_name'] = x.customer_name;
            x['payment_method'] = x.payment_method.toUpperCase();
            x['reference_number'] = x.reference_number;
            x['display_status'] = x.display_status;
            x['address'] = x.address;
            x['amount'] = '₱ ' + x.amount.toFixed(2);
            x['shipping_fee'] = '₱ ' + x.shipping_fee.toFixed(2);

            return x;
        })
    },

    watch: {
        selected(arg) {
            if(!arg) {
                this.closeOrderModal()
                return
            }

            this.form.reference = arg.reference
            
            this.openOrderModal()
        }
    },

    methods: {
        openOrderModal(){
            var modal = document.getElementById("orderModal");

            modal.style.display = "block";
        },

        closeOrderModal(){
            this.selected = null
            this.form.reference = null

            var modal = document.getElementById("orderModal");

            modal.style.display = "none";
            
        },
        save(){
            axios.post(this.$root.route + "/orders/change-status", this.form)
				.then(response => {
					location.reload()
				})
        }
    }
}
</script>

<style scoped>
.orderModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 40%;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: #ffffff;
}

/* Modal Content */
.order-content {
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