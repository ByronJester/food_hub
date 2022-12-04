<template>
    <div>
        <Navigation :auth="auth"></Navigation>

            <div class="relative w-full min-h-screen h-full px-2 py-12 flex flex-col">

                <div class="w-full mt-12">
                    <graph-line
                        style="width: 100vw; height: 500px"
                        :shape="'normal'"
                        :axis-min="0"
                        :axis-max="Math.max( ...options.sales)"
                        :axis-full-mode="true"
                        :labels="options.days"
                        :names="['DAILY SALES FOR REPORT THIS MONTH']"
                        :values="options.sales">
                        <!-- <note :text="'Line Chart'"></note> -->
                        <tooltip :names="['DAILY SALES REPORT FOR THIS MONTH']" :position="'right'"></tooltip>
                        <legends :names="['DAILY SALES REPORT FOR THIS MONTH']"></legends>
                        <guideline :tooltip-y="true"></guideline>
                    </graph-line>
                </div>

                <section class="text-gray-600 body-font">
                    <div class="container  mx-auto">
                        <div class="flex flex-wrap sm:mb-2 -mx-2">
                            <div class="w-full overflow-hidden rounded-lg shadow">
                                <div class="bg-white px-2 py-5 border-b border-gray-200 sm:px-6">
                                    <div class="ml-2 -mt-4 flex justify-between items-center flex-wrap sm:flex-nowrap">
                                        <div class="ml-2 mt-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                Transactions
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                Showing all transactions
                                            </p>
                                        </div>
                                        <div class="mt-4 flex sm:mt-0 sm:ml-2">
                                            <span class="float-right cursor-pointer" @click="printReport()">
                                                <i class="fa-solid fa-print fa-2xl"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full overflow-x-auto">
                                    <Table :columns="columns" :rows="orders" :keys="keys" :selected.sync="selected" />
                                </div>
                                <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t bg-gray-50 sm:grid-cols-9">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

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

		<!-- Modal order derails -->
		<div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalOrderDetail" v-if="selected" @keydown.escape.prevent.stop="closeOrderModal()" x-transition>
			<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

			<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
				<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
			</div>

			<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

				<div
					class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
					role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
					<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
					<button type="button" @click="closeOrderModal()"
						class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						<span class="sr-only">Close</span>
						<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
						stroke="currentColor" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
					</div>

					<div class="sm:flex sm:items-start">
						<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
							<h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
								Orders
							</h3>
						</div>
					</div>
					<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
						<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
							
                            <div class="relative mb-2">
                                <div class="leading-7 text-sm text-gray-600" v-for="o in selected.orders" :key="o.id">
                                    <p class="w-full">
                                        <span>
                                            {{ o.product.name }}
                                        </span>

                                        <span class="float-right">
                                            {{ o.quantity }} order
                                        </span>
                                    </p>
                                </div>
                            </div>
							<div class="relative mb-2">
								<select v-model="form.status" id="status" class="mt-1 block w-full py-2 px-3 border border-yellow-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm enabled:hover:border-yellow-500 disabled:opacity-50">
									<option :value="'to_deliver'">To Deliver</option>
                                        <option :value="'to_receive'">To Receive</option>
								</select>
							</div>

						</div>
					</div>

					<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
						<button class="w-full inline-flex justify-center mb-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm"
							@click="save()"
						>
							Save
						</button>&nbsp;
						<button type="button" @click="closeOrderModal()" class=" mb-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
					</div>
									
				</div>
			</div>
		</div>
    </div>
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
            orders: [],
            selected: null,
            columns: [
                'Customer Name', 'Payment Method', 'G-Cash Ref. No.', 'Address', 'Status', 'Amount', 'Shipping Fee', 'Total'
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
                {
                    label: 'total',
                },
            ],
            form: {
                reference: null,
                status: 'to_deliver',
                user_id: null
            },
            showModalOrderDetail: false,
        }
    },

    created(){
        this.orders = this.options.orders.map( x => {
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
        }
    },

    methods: {
        openOrderModal(){
            this.showModalOrderDetail = true
        },

        closeOrderModal(){
            this.selected = null
            this.form.reference = null

            this.showModalOrderDetail = false
            
        },
        save(){
            axios.post(this.$root.route + "/orders/change-status", this.form)
				.then(response => {
					location.reload()
				})
        },

        printReport(){
            this.$refs.report.generatePdf()
        }
    }
}
</script>