<template>
    <Navigation :auth="auth">
        <div class="w-full min-h-screen h-full px-2 py-2 flex flex-row">
            
            <div style="width: 15%; border-right: 1px solid #E4B934" class="flex flex-col">
                <div class="w-full" v-for="(orders, restaurant) in orders" :key="restaurant">
                    <p class="--text font-bold"> {{ restaurant }} </p>

                    <div class="flex flex-col px-5 py-2" v-for="order in orders" :key="order.id">
                        <div class="w-full flex flex-row" style="border: 1px solid #E4B934">
                            <div class="w-full">
                                <p class="--text pl-2 font-bold">
                                    {{ order.product.name }}
                                </p>
                                <p class="text-lg pl-2 mt-2">
                                    ₱{{ order.product.amount.toFixed(2)}}
                                </p>

                                <div class="w-full pl-2 mt-2" style="width: 40%">
                                    <input type="number" min="1" style="border: 1px solid #E4B934; width: 100%" class="text-center pt-2 pb-2" :value="order.quantity">
                                </div>
                            </div>

                            <div class="w-full">
                                <img :src="'/images/uploads/' + order.product.image" style="width: 100%; height: 150px" class="p-1"/>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div style="width: 85%">

            </div>

        
        </div>
    </Navigation>
</template>

<script>
import Navigation from '../Layouts/Navigation.vue'
import axios from "axios";

export default {
    props: ['auth', 'options'],
    components: {
        Navigation,
    },
    data(){
        return {
            orders: null
        }
    },
    mounted() {
        this.orders = this.options.orders
    }
}
</script>

<style scoped>
.--text {
	font-size: calc(.1em + 1vw);
}
</style>