<template>
    <div>
        <Navigation :auth="auth"></Navigation>
            <!-- Desktop Icon -->
            <section class="text-gray-600 body-font hidden md:block">
                <div class="container px-5 py-16 mx-auto">
                    <div class="flex flex-wrap  -mx-4 -mb-10 -mt-4 md:space-y-0 space-y-6">
                        <div class="p-4 md:w-1/5 flex flex-col text-center items-center cursor-pointer" @click="activeTab = 'pending'">
                            <div class="w-20 h-20 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-5 flex-shrink-0">
                                <svg  id="Capa_1" class="w-10 h-10" x="0px" y="0px" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5"
                                        width="47.001px" height="47.001px" viewBox="0 0 47.001 47.001" style="enable-background:new 0 0 47.001 47.001;"
                                        xml:space="preserve">
                                    <g>
                                        <g id="Layer_1_64_">
                                            <g>
                                                <path d="M22.432,22.429c0,0.591,0.479,1.067,1.068,1.067s1.068-0.479,1.068-1.067c0.001-1.25,0.445-2.465,1.251-3.421
                                                    c1.793-2.126,3.137-4.431,3.988-6.851c0.115-0.327,0.065-0.689-0.135-0.972c-0.201-0.283-0.525-0.451-0.872-0.451H18.199
                                                    c-0.347,0-0.672,0.168-0.873,0.451c-0.2,0.283-0.25,0.645-0.135,0.972c0.853,2.42,2.195,4.725,3.988,6.851
                                                    C21.986,19.964,22.431,21.18,22.432,22.429z"/>
                                                <path d="M24.568,26.71c0-0.59-0.479-1.067-1.068-1.067s-1.068,0.479-1.068,1.067c-0.001,1.542-0.922,3.067-2.593,4.304
                                                    c-3.574,2.639-6.249,5.506-7.951,8.52c-0.187,0.332-0.184,0.736,0.009,1.062c0.19,0.329,0.542,0.53,0.922,0.53h21.364
                                                    c0.379,0,0.73-0.201,0.922-0.53c0.191-0.326,0.194-0.73,0.008-1.062c-1.701-3.014-4.377-5.881-7.95-8.52
                                                    C25.49,29.777,24.569,28.252,24.568,26.71z"/>
                                                <path d="M42.192,42.729h-0.639c-0.734-8.313-5.602-14.695-9.386-19.228c3.785-4.532,8.651-10.915,9.388-19.228h0.639
                                                    c1.18,0,2.136-0.957,2.136-2.137C44.33,0.956,43.374,0,42.194,0H4.807c-1.18,0-2.136,0.957-2.136,2.136
                                                    c0,1.18,0.956,2.137,2.136,2.137h0.639c0.735,8.314,5.601,14.697,9.386,19.228c-3.784,4.532-8.651,10.914-9.387,19.228H4.807
                                                    c-1.18,0-2.136,0.955-2.136,2.135c0,1.181,0.956,2.138,2.136,2.138h2.671h32.044h2.672c1.18,0,2.136-0.957,2.136-2.138
                                                    C44.33,43.684,43.373,42.729,42.192,42.729z M9.728,42.729c0.803-7.511,5.686-13.295,9.335-17.617l0.195-0.231
                                                    c0.672-0.795,0.672-1.959,0-2.755l-0.194-0.23c-3.648-4.323-8.533-10.107-9.336-17.619h27.544
                                                    c-0.803,7.512-5.688,13.296-9.336,17.619l-0.193,0.23c-0.672,0.795-0.672,1.959,0,2.755l0.195,0.231
                                                    c3.648,4.322,8.531,10.106,9.334,17.617H9.728z"/>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="flex-grow">
                                <h2 class="text-gray-900 text-sm title-font font-bold">Pending</h2>
                            </div>
                            <hr class="mr-4 h-1 w-20 bg-yellow-500 rounded" v-if="activeTab == 'pending'"></hr>
                        </div>
                        <div class="p-4 md:w-1/5 flex flex-col text-center items-center cursor-pointer" @click="activeTab = 'to_process'">
                            <div class="w-20 h-20 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-5 flex-shrink-0">
                                <svg viewBox="0 0 36 36" class="w-10 h-10" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" preserveAspectRatio="xMidYMid meet" >
                                    <path d="M33.49,26.28a1,1,0,0,0-1.2-.7l-2.49.67a14.23,14.23,0,0,0,2.4-6.75A14.48,14.48,0,0,0,27.37,7.35,1,1,0,0,0,26,7.44a1,1,0,0,0,.09,1.41,12.45,12.45,0,0,1,4.16,10.46,12.19,12.19,0,0,1-2,5.74L28,22.54a1,1,0,1,0-1.95.16l.5,6.44,6.25-1.66A1,1,0,0,0,33.49,26.28Z" class="clr-i-outline clr-i-outline-path-1"></path><path d="M4.31,17.08a1.06,1.06,0,0,0,.44.16,1,1,0,0,0,1.12-.85A12.21,12.21,0,0,1,18.69,5.84L16.45,7.37a1,1,0,0,0,.47,1.79A1,1,0,0,0,17.56,9l5.33-3.66L18.33.76a1,1,0,1,0-1.39,1.38l1.7,1.7A14.2,14.2,0,0,0,3.89,16.12,1,1,0,0,0,4.31,17.08Z" class="clr-i-outline clr-i-outline-path-2"></path><path d="M21.73,29.93a12,12,0,0,1-4.84.51,12.3,12.3,0,0,1-9.57-6.3l2.49.93a1,1,0,0,0,.69-1.84l-4.59-1.7h0L4.44,21,3.33,27.35a1,1,0,0,0,.79,1.13l.17,0a1,1,0,0,0,1-.81l.42-2.4a14.3,14.3,0,0,0,11,7.14,13.91,13.91,0,0,0,5.63-.6,1,1,0,0,0-.6-1.9Z" class="clr-i-outline clr-i-outline-path-3"></path><path d="M22,13H14a1,1,0,0,0-1,1v8a1,1,0,0,0,1,1h8a1,1,0,0,0,1-1V14A1,1,0,0,0,22,13Zm-1,8H15V15h6Z" class="clr-i-outline clr-i-outline-path-4"></path>
                                </svg>
                            </div>
                            <div class="flex-grow">
                            <h2 class="text-gray-900 text-sm title-font font-bold">To Process</h2>
                            </div>
                            <hr class="mr-4 h-1 w-20 bg-yellow-500 rounded" v-if="activeTab == 'to_process'"></hr>
                        </div>
                        <div class="p-4 md:w-1/5 flex flex-col text-center items-center cursor-pointer" @click="activeTab = 'to_deliver'">
                            <div class="w-20 h-20 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-5 flex-shrink-0">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-grow">
                            <h2 class="text-gray-900 text-sm title-font font-bold">To Deliver</h2>
                            </div>
                            <hr class="mr-4 h-1 w-20 bg-yellow-500 rounded" v-if="activeTab == 'to_deliver'"></hr>
                        </div>  
                        <div class="p-4 md:w-1/5 flex flex-col text-center items-center cursor-pointer" @click="activeTab = 'to_receive'">
                            <div class="w-20 h-20 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-5 flex-shrink-0">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                </svg>
                            </div>
                            <div class="flex-grow">
                            <h2 class="text-gray-900 text-sm title-font font-bold">To Receive</h2>
                            </div>
                            <hr class="mr-4 h-1 w-20 bg-yellow-500 rounded" v-if="activeTab == 'to_receive'"></hr>
                        </div>  
                        <div class="p-4 md:w-1/5 flex flex-col text-center items-center cursor-pointer" @click="activeTab = 'received'">
                            <div class="w-20 h-20 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-5 flex-shrink-0">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-grow">
                            <h2 class="text-gray-900 text-sm title-font font-bold">History</h2>
                            </div>
                            <hr class="mr-4 h-1 w-20 bg-yellow-500 rounded" v-if="activeTab == 'received'"></hr>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mobile Icon -->
            <section class="text-gray-600 body-font sm:hidden">
                <div class="container px-5 mx-auto">
                    <div class="flex flex-wrap sm:-m-4 -mx-4 -mt-4 md:space-y-0 space-y-6">
                        <div class=" flex flex-col text-center items-center">
                            <div class="w-full  px-2 flex flex-col  mt-32">
                                <div>
                                    <div class="w-full flex flex-row mt-5">
                                        <div class="w-full flex flex-col cursor-pointer p-2" @click="activeTab = 'pending'">
                                            <div class="w-full flex w-14 h-14 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-5 flex-shrink-0">
                                                <svg id="Capa_1" class="w-5 h-5" x="0px" y="0px" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5"
                                                        width="47.001px" height="47.001px" viewBox="0 0 47.001 47.001" style="enable-background:new 0 0 47.001 47.001;"
                                                        xml:space="preserve">
                                                    <g>
                                                        <g id="Layer_1_64_">
                                                            <g>
                                                                <path d="M22.432,22.429c0,0.591,0.479,1.067,1.068,1.067s1.068-0.479,1.068-1.067c0.001-1.25,0.445-2.465,1.251-3.421
                                                                    c1.793-2.126,3.137-4.431,3.988-6.851c0.115-0.327,0.065-0.689-0.135-0.972c-0.201-0.283-0.525-0.451-0.872-0.451H18.199
                                                                    c-0.347,0-0.672,0.168-0.873,0.451c-0.2,0.283-0.25,0.645-0.135,0.972c0.853,2.42,2.195,4.725,3.988,6.851
                                                                    C21.986,19.964,22.431,21.18,22.432,22.429z"/>
                                                                <path d="M24.568,26.71c0-0.59-0.479-1.067-1.068-1.067s-1.068,0.479-1.068,1.067c-0.001,1.542-0.922,3.067-2.593,4.304
                                                                    c-3.574,2.639-6.249,5.506-7.951,8.52c-0.187,0.332-0.184,0.736,0.009,1.062c0.19,0.329,0.542,0.53,0.922,0.53h21.364
                                                                    c0.379,0,0.73-0.201,0.922-0.53c0.191-0.326,0.194-0.73,0.008-1.062c-1.701-3.014-4.377-5.881-7.95-8.52
                                                                    C25.49,29.777,24.569,28.252,24.568,26.71z"/>
                                                                <path d="M42.192,42.729h-0.639c-0.734-8.313-5.602-14.695-9.386-19.228c3.785-4.532,8.651-10.915,9.388-19.228h0.639
                                                                    c1.18,0,2.136-0.957,2.136-2.137C44.33,0.956,43.374,0,42.194,0H4.807c-1.18,0-2.136,0.957-2.136,2.136
                                                                    c0,1.18,0.956,2.137,2.136,2.137h0.639c0.735,8.314,5.601,14.697,9.386,19.228c-3.784,4.532-8.651,10.914-9.387,19.228H4.807
                                                                    c-1.18,0-2.136,0.955-2.136,2.135c0,1.181,0.956,2.138,2.136,2.138h2.671h32.044h2.672c1.18,0,2.136-0.957,2.136-2.138
                                                                    C44.33,43.684,43.373,42.729,42.192,42.729z M9.728,42.729c0.803-7.511,5.686-13.295,9.335-17.617l0.195-0.231
                                                                    c0.672-0.795,0.672-1.959,0-2.755l-0.194-0.23c-3.648-4.323-8.533-10.107-9.336-17.619h27.544
                                                                    c-0.803,7.512-5.688,13.296-9.336,17.619l-0.193,0.23c-0.672,0.795-0.672,1.959,0,2.755l0.195,0.231
                                                                    c3.648,4.322,8.531,10.106,9.334,17.617H9.728z"/>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                            <p class="text-xs font-bold" style="font-size: 0.6rem; line-height: 0.75rem;">Pending</p>
                                            <hr class=" h-1 w-12 bg-yellow-500 rounded" v-if="activeTab == 'pending'"></hr>
                                        </div>
                                        <div class="w-full flex flex-col cursor-pointer p-2" @click="activeTab = 'to_process'">
                                            <div class="w-full flex w-14 h-14 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-5 flex-shrink-0">
                                                <svg viewBox="0 0 36 36" class="w-5 h-5" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" preserveAspectRatio="xMidYMid meet" >
                                                    <path d="M33.49,26.28a1,1,0,0,0-1.2-.7l-2.49.67a14.23,14.23,0,0,0,2.4-6.75A14.48,14.48,0,0,0,27.37,7.35,1,1,0,0,0,26,7.44a1,1,0,0,0,.09,1.41,12.45,12.45,0,0,1,4.16,10.46,12.19,12.19,0,0,1-2,5.74L28,22.54a1,1,0,1,0-1.95.16l.5,6.44,6.25-1.66A1,1,0,0,0,33.49,26.28Z" class="clr-i-outline clr-i-outline-path-1"></path><path d="M4.31,17.08a1.06,1.06,0,0,0,.44.16,1,1,0,0,0,1.12-.85A12.21,12.21,0,0,1,18.69,5.84L16.45,7.37a1,1,0,0,0,.47,1.79A1,1,0,0,0,17.56,9l5.33-3.66L18.33.76a1,1,0,1,0-1.39,1.38l1.7,1.7A14.2,14.2,0,0,0,3.89,16.12,1,1,0,0,0,4.31,17.08Z" class="clr-i-outline clr-i-outline-path-2"></path><path d="M21.73,29.93a12,12,0,0,1-4.84.51,12.3,12.3,0,0,1-9.57-6.3l2.49.93a1,1,0,0,0,.69-1.84l-4.59-1.7h0L4.44,21,3.33,27.35a1,1,0,0,0,.79,1.13l.17,0a1,1,0,0,0,1-.81l.42-2.4a14.3,14.3,0,0,0,11,7.14,13.91,13.91,0,0,0,5.63-.6,1,1,0,0,0-.6-1.9Z" class="clr-i-outline clr-i-outline-path-3"></path><path d="M22,13H14a1,1,0,0,0-1,1v8a1,1,0,0,0,1,1h8a1,1,0,0,0,1-1V14A1,1,0,0,0,22,13Zm-1,8H15V15h6Z" class="clr-i-outline clr-i-outline-path-4"></path>
                                                </svg>
                                            </div>
                                            <p class="text-xs font-bold" style="font-size: 0.6rem; line-height: 0.75rem;">To Process</p>
                                            <hr class=" h-1 w-12 bg-yellow-500 rounded" v-if="activeTab == 'to_process'"></hr>
                                        </div>
                                        <div class="w-full flex flex-col cursor-pointer p-2" @click="activeTab = 'to_deliver'">
                                            <div class="w-full flex w-14 h-14 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-5 flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-xs font-bold" style="font-size: 0.6rem; line-height: 0.75rem;">To Deliver</p>
                                            <hr class=" h-1 w-12 bg-yellow-500 rounded" v-if="activeTab == 'to_deliver'"></hr>
                                        </div>
                                        <div class="w-full flex flex-col cursor-pointer p-2" @click="activeTab = 'to_receive'">
                                            <div class="w-full flex w-14 h-14 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-5 flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                                </svg>
                                            </div>
                                            <p class="text-xs font-bold" style="font-size: 0.6rem; line-height: 0.75rem;">To Receive</p>
                                            <hr class=" h-1 w-12 bg-yellow-500 rounded" v-if="activeTab == 'to_receive'"></hr>
                                        </div>
                                        <div class="w-full flex flex-col cursor-pointer p-2" @click="activeTab = 'received'">
                                            <div class="w-full flex w-14 h-14 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-500 mb-5 flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-xs font-bold" style="font-size: 0.6rem; line-height: 0.75rem;">History</p>
                                            <hr class=" h-1 w-12 bg-yellow-500 rounded" v-if="activeTab == 'received'"></hr>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
			<section class="text-gray-600 body-font">
				<div class="container px-5 py-12 mx-auto">
					<div class="flex flex-wrap -m-4" v-for="(orders, restaurant) in orders" :key="restaurant">
                        <div class="font-bold w-full mb-6 lg:mb-0" >
                            <span>{{restaurant}}</span>
                        </div>
						<div class="lg:w-1/4 md:w-1/2 p-4 w-full mb-4" v-for="order in orders" :key="order.id">
                            <div class="w-full inline-flex mb-2 justify-between items-center" v-if="activeTab == 'pending'">
                                <input type="checkbox" class="focus:ring-yellow-500 h-4 w-4 text-yellow-600 border-yellow-300 rounded" :value="order" @change="selectOrder($event, order)" v-model="selectedOrders"/>
                                <button type="button" class="w-full ml-2 rounded-md border-0 px-4 text-red-500 text-base font-medium  hover:text-rose-600 sm:mt-0 sm:w-auto sm:text-sm" @click="orderSelected = order.id">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
							<a class="block relative h-48 rounded overflow-hidden cursor-pointer"  >
							<img alt="ecommerce" class="object-cover object-center w-full h-full block" :src="'/images/uploads/' +  order.product.image">
							</a>
							<div class="mt-4">
								<h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">{{ order.product.name.toUpperCase() }}</h3>
								<p class="leading-relaxed text-base">₱{{ parseFloat(order.amount).toFixed(2) }}</p>
							</div>
							<div class="px-4 mt-4" v-if="activeTab == 'pending'">
								<input type="number" min="1"  class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out text-center" :value="order.quantity" @change="changeQuantity($event, order)">
                            </div>
                            <div class="px-4 mt-4" v-if="activeTab == 'to_receive'">
                                <button class="w-full inline-flex justify-center mb-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:text-sm" @click="orderReceived(order.reference)">
                                    Order Received
                                </button>
                            </div>
						</div>
					</div>
				</div>
			</section>


            <div class="w-full min-h-screen h-full px-2 py-2 flex flex-col">

                <div class="w-full px-5 text-center text-lg flex justify-center items-center font-bold cursor-pointer" style="height: 5vh; background: #E4B934; position: fixed; bottom: 0; left: 0"
                    v-if="selectedOrders.length > 0 && activeTab == 'pending'" @click="openCheckoutModal()"
                >
                    <p class="text-white">CHECKOUT</p>
                </div>
            </div>

            <!-- Modal remove item -->
            <div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalRemoveCart" @keydown.escape.prevent.stop="closeRemoveModal()" x-transition>
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div
                        class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
                        role="dialog" aria-modal="true" aria-labelledby=""  @click.stop x-trap.noscroll.inert="">
                        <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                        <button type="button" @click="closeRemoveModal()"
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
                            </div>
                        </div>
                        <div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
                                
                                <div class="relative mb-2">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                        Are you sure to delete this order ?
                                    </h3>
                                </div>

                            </div>
                        </div>

                        <div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button class="w-full inline-flex justify-center ml-2 mb-2 rounded-md border border-rose-300 shadow-sm px-4 py-2 bg-rose-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:mt-0 sm:w-auto sm:text-sm"
                                @click="removeProduct(orderSelected)"
                            >
                                Yes
                            </button>
                            <button type="button"  @click="closeRemoveModal();" class="ml-2 mb-2 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                        </div>
                                        
                    </div>
                </div>
            </div>

			<!-- Modal buy now product -->
			<div class="fixed z-50 inset-0 overflow-y-auto" v-show="showModalBuyNow" @keydown.escape.prevent.stop="closeCheckoutModal()" x-transition>
				<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

				<div class="fixed inset-0 transition-opacity" aria-hidden="true" x-transition.opacity>
					<div class="absolute inset-0 bg-gray-500 opacity-75"></div>
				</div>

				<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

					<div
						class="inline-block align-bottom items-center bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
						role="dialog" aria-modal="true" aria-labelledby="" @click.stop x-trap.noscroll.inert="">
						<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
						<button type="button" @click="closeCheckoutModal()"
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
									Checkout Order
								</h3>
							</div>
						</div>
						<div class="text-center sm:mt-0 sm:ml-4 sm:text-left">
							<div class="mt-6 grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 xl:grid-cols-1 gap-2 items-center">
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

                                    <div class="w-full flex flex-row mt-5">
                                        <div class="w-full flex justify-center items-center">
                                            Mode of Payment:
                                        </div>

										<div class="w-full flex justify-center items-center">
											<input class="ml-2" type="radio" value="cod" v-model="form.payment_method" />
											<label class="mx-2">COD</label>

											<input class="mr-1" type="radio" value="gcash" v-model="form.payment_method" />
											<label>&nbsp;G-Cash</label>
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
										<input type="text" id="address" name="address" placeholder="Street, Barangay, Town" v-model="form.address" class="w-full bg-white rounded border border-gray-300 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">

										<span class="text-red-500 text-xs ml-2">
											{{validationError('address', saveError)}}
										</span>
									</div>

									<div class="w-full  pl-5 mt-5">
										<div class="w-full flex justify-start items-center ">
											Available Delivery Address:
										</div>

										<div class="w-full flex justify-start items-center ">
											<select class="mt-1 block w-full py-2 px-3 border border-yellow-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm enabled:hover:border-yellow-500 disabled:opacity-50">
												<option :value="place.id" v-for="place in places" :key="place.id">{{ place.address}}</option>
											</select>
										</div>
									</div>

                                </div>
							</div>
						</div>
						<div class="mt-16 sm:mt-4 sm:flex sm:flex-row-reverse">
							<button type="button" @click="checkout()" class="w-full inline-flex justify-center ml-2 mb-2 rounded-md border border-yellow-300 shadow-sm px-4 py-2 bg-yellow-500 text-base font-medium text-white hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:w-auto sm:text-sm">
							Confirm
							</button>
							<button type="button" @click="closeCheckoutModal()" class="w-full inline-flex justify-center ml-2 mb-2  rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
						</div>
										
					</div>
				</div>
			</div>
    </div>
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
            orderSelected: null,
            showModalRemoveCart: false,
            showModalBuyNow: false,
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
                    console.log(response.data.data.url);
					if(response.data.status == 422) {
						this.saveError = response.data.errors 
					} else {
                        this.activeTab = 'to_process'
                        this.selectedOrders = []
                        this.closeCheckoutModal()
                        
                        if(response.data.data.hasOwnProperty('url')) {
                            window.location.href = response.data.data.url
                        }
					}
				})
        },

        openCheckoutModal(){
            this.showModalBuyNow = true

            this.groupOrders = _.mapValues(_.groupBy(this.selectedOrders, 'food_hub'),
                          clist => clist.map(order => _.omit(order, 'food_hub')));
        },

        closeCheckoutModal(){
            this.showModalBuyNow = false
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
            this.showModalRemoveCart = true

        },

        closeRemoveModal(){
            this.showModalRemoveCart = false

            this.orderSelected = null
        },


    }
}
</script>
