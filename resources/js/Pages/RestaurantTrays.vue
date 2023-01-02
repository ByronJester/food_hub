<template>
    <Navigation :auth="auth">
        <div class="w-full min-h-screen h-full px-2 py-2 flex flex-col --main--div">

            <!-- <div class="w-full" v-if="!isMobile">
                <graph-line
                    style="width: 100vw; height: 500px;"
                    :shape="'normal'"
                    :axis-min="0"
                    :axis-max="Math.max( ...options.sales)"
                    :axis-full-mode="true"
                    :labels="options.days"
                    :names="['DAILY SALES REPORT FOR THIS MONTH']"
                    :values="options.sales">
                    <guideline :tooltip-y="true"></guideline>
                </graph-line>

                <div class="w-full text-center text-3xl">
                    DAILY SALES REPORT FOR THIS MONTH
                </div>
            </div> -->

            <div class="w-full mt-10">
                <select v-model="payment_method" style="border: 1px solid black" class="text-center">
                    <option value="cod">COD</option>
                    <option value="gcash">GCASH</option>
                </select>
                <!-- <span class="float-right cursor-pointer" @click="printReport()">
                    <i class="fa-solid fa-print fa-2xl"></i>
                </span> -->
            </div>

            <div class="w-full mt-5">
                <Table :columns="columns" :rows="rows" :keys="keys" :selected.sync="selected" :style="{opacity: selected ? '0.6' : '1'}" class="w-full"/>

                <div id="orderModal" class="orderModal h-full">
                    <!-- Modal content -->
                    <div class="order-content flex flex-col" style="border: 2px solid #E4B934" v-if="selected" :style="{'width': isMobile ? '90%': '20%'}">
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
                                    <option :value="'to_process'">Prepare Order</option>
                                    <option :value="'cancel'" :disabled="selected.payment_method == 'GCASH'">Cancel Order</option>
                                    <option :value="'to_deliver'">Deliver Order</option>
                                    <option :value="'to_receive'">Receive Order</option>
                                    <option :value="'reported'">Mark as Bogus Order</option>
                                </select>

                                <textarea class="w-full mt-2 pl-2" rows="4" cols="50" placeholder="Reason..." 
                                    v-model="form.reason" style="border: 1px solid black;" v-if="form.status == 'cancel'"
                                >
                                </textarea>
                                <span class="text-xs text-red-500">{{validationError('reason', saveError)}} </span>
                                
                            </div>

                            <div class="w-full mt-4">
                                <button class="w-full py-2" style="background: #E4B934" @click="confirmation()">
                                    SAVE
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             <VueHtml2pdf
                :show-layout="false"
                :float-layout="true"
                :enable-download="true"
                :preview-modal="true"
                :paginate-elements-by-height="1000"
                :filename="Math.random().toString(36).slice(2)"
                :pdf-quality="2"
                :manual-pagination="false"
                pdf-format="a4"
                pdf-orientation="landscape"
                pdf-content-width="100%"
                ref="report"
            >
                <section slot="pdf-content">
                    <div class="w-full p-5">
                        <table class="w-full">
                            <tr class="text-center">
                                <th v-for="column in columns" :key="column">
                                    {{ column }}
                                </th>
                            </tr>

                            <tr class="text-center"
                                v-for="(l, index) in orders" :key="l.id"
                            >
                                <td v-for="(k, i) in keys" :key="i" class="cursor-pointer">
                                    <span>{{ orders[index][k.label] }}</span>
                                </td>
                            </tr>

                        </table>
                    </div>
                </section>
            </VueHtml2pdf>
        </div>
    </Navigation>
</template>

<script>
import Navigation from '../Layouts/Navigation.vue'
import axios from "axios";
import Table from "../Components/Table";
import VueHtml2pdf from 'vue-html2pdf'

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
        Table,
        VueHtml2pdf
    },
    data(){
        return {
            codOrders: [],
            gcashOrders: [],
            selected: null,
            columns: [
                'Date', 'Customer Name', 'Address', 'Contact Number', 'Payment Method', 'Status', 'Amount', 'Shipping Fee', 'Total'
            ],
            keys : [
                {
                    label: 'date',
                },
                {
                    label: 'customer_name',
                },
                {
                    label: 'address',
                },
                {
                    label: 'contact',
                },
                {
                    label: 'payment_method',
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
                {
                    label: 'total',
                },
            ],
            form: {
                reference: null,
                status: 'to_process',
                user_id: null,
                reason: null
            },
            saveError: null,
            payment_method: 'cod',
            rows: [],
            isMobile: window.screen.width <= 700,
        }
    },

    created(){
        this.codOrders = this.options.orders.filter( y => {
            return y.payment_method == 'cod'
        }).map( x => {
            x['customer_name'] = x.customer_name;
            x['payment_method'] = x.payment_method.toUpperCase();
            x['reference_number'] = x.reference_number;
            x['display_status'] = x.display_status;
            x['address'] = x.address;
            x['amount'] = '₱ ' + parseFloat(x.amount).toFixed(2);
            x['total'] = '₱ ' + parseFloat(x.total).toFixed(2);
            x['shipping_fee'] = '₱ ' + parseFloat(x.shipping_fee).toFixed(2);

            return x;
        })

        this.gcashOrders = this.options.orders.filter( y => {
            return y.payment_method == 'gcash'
        }).map( x => {
            x['customer_name'] = x.customer_name;
            x['payment_method'] = x.payment_method.toUpperCase();
            x['reference_number'] = x.reference_number;
            x['display_status'] = x.display_status;
            x['address'] = x.address;
            x['amount'] = '₱ ' + parseFloat(x.amount).toFixed(2);
            x['total'] = '₱ ' + parseFloat(x.total).toFixed(2);
            x['shipping_fee'] = '₱ ' + parseFloat(x.shipping_fee).toFixed(2);

            return x;
        })

        this.rows = this.codOrders
        
    },

    watch: {
        selected(arg) {
            if(!arg) {
                this.closeOrderModal()
                return
            }

            this.form.reference = arg.reference
            this.form.user_id = arg.user.id
            
            this.openOrderModal()
        },

        payment_method(arg) {
           if(arg == 'cod') {
                this.rows = this.codOrders
           } else {
                this.rows = this.gcashOrders
           }
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

            this.saveError = null
            this.form.status = 'to_process'
            
        },
        confirmation(){
            var message = '';
            var status = this.form.status

            if(status == 'to_process') {
                message = 'Are you sure to process this order ?'
            }

            if(status == 'cancel') {
                message = 'Are you sure to cancel this order ?'
            }

            if(status == 'reported') {
                message = 'Are you sure to report this order as bogus order ?'
            }

            if(status == 'to_deliver') {
                message = 'Are you sure to deliver this order ?'
            }

            if(status == 'to_receive') {
                message = 'Are you sure to mark this order to recieve ?'
            }

            swal({
                title: message,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((proceed) => {
                if (proceed) {
                    this.save()
                } else {
                    
                }
            });
        },
        save(){
            axios.post(this.$root.route + "/orders/change-status", this.form)
				.then(response => {
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
						location.reload()
					}
				})
        },

        printReport(){
            this.$refs.report.generatePdf()
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
  top: 30%;
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

table {
    border-collapse: collapse;
    border-radius: 5px;
    border-style: hidden;
    box-shadow: 0 0 0 1px black;
}
td {
    border: 1px solid black;
    padding-top: 20px;
    padding-bottom: 20px;
}
th {
    border: 1px solid black;
    background: #E4B934;
    color: black;
    padding-top: 20px;
    padding-bottom: 20px;
}
</style>