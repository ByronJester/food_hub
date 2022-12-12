<template>
    <Navigation :auth="auth">
        <div class="w-full flex justify-center items-center">
            <div style="width: 20%; height: 100%; border: 1px solid #E4B934; border-radius: 10px" class="mt-32 flex flex-col">
                <div class="w-full text-center mt-5" style="font-size: 60px">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>

                <div class="w-full text-center mt-5" style="font-size: 30px">
                    ₱ {{ (billing.amount/100).toFixed(2) }}
                </div>

                <div class="w-full flex flex-row mt-5 px-3" style="font-size: 20px" v-for="order in orders" :key="order.id">
                    <div class="w-full">
                        <span>
                            {{ order.product.name }} (₱ {{order.product.amount.toFixed(2)}})
                        </span>
                    </div>

                    <div class="w-full">
                        <span class="float-right">
                            {{ order.quantity }} Order(s)
                        </span>
                    </div>
                </div>

                <div class="w-full flex flex-row mt-4 px-3" style="font-size: 20px" >
                    <div class="w-full">
                        <span>
                           Shipping Fee
                        </span>
                    </div>

                    <div class="w-full">
                        <span class="float-right">
                            ₱ 60.00
                        </span>
                    </div>
                </div>

                <div class="w-full flex flex-row mt-4 px-3" style="font-size: 20px" >
                    <div class="w-full">
                        <span>
                            {{ billing.billing.name }}
                        </span>
                    </div>

                    <div class="w-full">
                        <span class="float-right">
                            {{ billing.billing.phone }}
                        </span>
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
        data() {
            return {
                billing: null,
                orders: null
            }
        },
        mounted() {
            this.billing = this.options.source.data.attributes

            this.orders = this.options.orders
        }
    }
</script>