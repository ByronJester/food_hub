<template>
<div>
    <!-- Modal wallet -->
    <div class="fixed z-50 inset-0 overflow-y-auto" v-show="showWallet" @keydown.escape.prevent.stop="showWallet = false" x-transition>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
                role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
                <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" @click="isRegister = false"
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
                            My Wallet
                        </h3>
                    </div>
                </div>
                <div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
                       <h2 class="leading-7 text-2xl text-gray-600">₱ {{ auth.wallet.toFixed(2) }}</h2>
                    </div>
                </div>
                <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showWallet = false" class="ml-2 mb-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Close</button>
                </div>      
            </div>
        </div>
    </div>
    <header class="text-gray-600 body-font bg-white shadow-lg z-10 fixed w-full">
        <div class="container mx-auto flex flex-wrap p-3 flex-col md:flex-row items-center">
            <a role="button" @click="auth.role == '3' ? changeActive('/customers') : auth.role == '1' ? changeActive('/users') : changeActive('/restaurants')" class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
            <span class="ml-3 text-2xl text-yellow-400 font-bold">FoodHub</span>
            </a>
            <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center text-gray-500">
                <a role="button" :class="{'underline underline-offset-4 text-gray-900' : active == '/restaurants'}" class="mr-5 hover:text-gray-900 font-bold" @click="changeActive('/restaurants')" v-if="auth.role == 2">
                    <i class="fa-solid fa-shop mr-2"></i>My Food
                </a>
                <a role="button" :class="{'underline underline-offset-4 text-gray-900' : active == '/restaurants/my-trays'}" class="mr-5 hover:text-gray-900 font-bold" @click="changeActive('/restaurants/my-trays')" v-if="auth.role == 2">
                    <span class="align-top text-red-500 font-bold text-sm" style="top: -0.8rem; right: -0.8rem" v-if="auth.trays > 0">
                        {{ auth.trays }}
                    </span>
                    <i class="fa-solid fa-cart-shopping mr-2"></i>My Trays
                </a>
                <a role="button" :class="{'underline underline-offset-4 text-gray-900' : active == '/customers'}" class="mr-5 hover:text-gray-900 font-bold" @click="changeActive('/customers')" v-if="auth.role == 3">
                    <i class="fa-solid fa-shop mr-2"></i>Food Hubs
                </a>
                <a role="button" :class="{'underline underline-offset-4 text-gray-900' : active == '/orders'}" class="mr-5 hover:text-gray-900 font-bold" @click="changeActive('/orders')" v-if="auth.role == 3">
                    <span class="align-top text-red-500 font-bold text-sm" style="top: -0.8rem; right: -0.8rem" v-if="auth.trays > 0">
                        {{ auth.trays }}
                    </span>
                    <i class="fa-solid fa-cart-shopping mr-2"></i>Trays
                </a>

                <a role="button" :class="{'underline underline-offset-4 text-gray-900' : active == '/users'}" class="underline-offset-4 mr-5 hover:text-gray-900 font-bold" @click="changeActive('/users')" v-if="auth.role == 1 || (auth.role == 2 && auth.user_type == 'owner')">
                    <i class="fa-solid fa-users-gear mr-2"></i>Users
                </a>
                <a role="button" :class="{'underline underline-offset-4 text-gray-900' : active == '/users/profile'}" class="mr-5 hover:text-gray-900 font-bold" @click="changeActive('/users/profile')">
                    <i class="fa-solid fa-user mr-1"></i>Profile
                </a>
                <a role="button" :class="{'underline underline-offset-4 text-gray-900' : active == '/trails'}" class="mr-5 hover:text-gray-900 font-bold" @click="changeActive('/trails')" v-if="auth.user_type == 'admin'">
                    <i class="fa-solid fa-list mr-1"></i>Trails
                </a>
                <a role="button" class="mr-5 hover:text-gray-900 font-bold"  @click="showWallet = true" v-if="auth.role == 2 && auth.user_type == 'owner'">
                    <i class="fa-solid fa-wallet mr-1"></i>Wallet
                </a>
                <a role="button" class="mr-5 hover:text-gray-900 font-bold" @click="logout()">
                    <i class="fa-solid fa-door-open mr-1"></i>Logout
                </a>

            </nav>
        </div>
    </header>

    <slot></slot>
</div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
	props:['auth'],
	data(){
        return {
            leftPanel: '3%',
            rightPanel: '97%',
            active: window.location.pathname,
            isMobile: window.screen.width <= 700,
            showWallet: false,
        }
	},

    created(){
        console.log(this.auth.trays)
    },

	methods: {
        logout(){
            Inertia.post(this.$root.route + "/users/logout", {},
			{
				onSuccess: (res) => {
				},
				orError: (err) => {
				}
			});
        },

        changeActive(arg){
            Inertia.get(
                this.$root.route + arg,
                {
                    onSuccess: () => { },
                },
            );
        },
	}
}

</script>
