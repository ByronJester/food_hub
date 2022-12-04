<template>
    <table class="w-full whitespace-no-wrap">
        <thead>
            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                <th class="px-4 py-3" v-for="column in columns" :key="column">
                    {{ column }}
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y">

            <tr v-for="(l, index) in rows" :key="l.id"
                class="text-gray-700">
                <td class="px-4 py-3 cursor-pointer" v-for="(k, i) in keys" :key="i" @click="selectItem(l)">
                    {{ rows[index][k.label] }}
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: ['rows', 'columns', 'keys', 'selected'],
    data() {
        return {
        }
    },
    methods: {
        selectItem(arg) {
            this.$emit('update:selected', arg)
        }
    },

    mounted(){
        
    }
}
</script>
