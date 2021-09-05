<template>
    <v-main data-app>
        <v-container>
            <notifications />
            <router-view></router-view>
        </v-container>
    </v-main>
</template>

<script>
    export default {
        // element: '#id',
        mounted() {
            console.log('Document ready')
        }
    }
</script>