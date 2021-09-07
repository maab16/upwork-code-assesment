<template>
    <div>
        <h1>Products</h1>
        <v-btn class="mb-3"><router-link :to="{name: 'product.create'}">Create Product</router-link></v-btn>
        <v-simple-table>
            <template v-slot:default>
            <thead>
                <tr>
                <th class="text-left">
                    Name
                </th>
                <th class="text-left">
                    Manufacture Year
                </th>
                <th class="text-left">
                    Photo
                </th>
                <th class="text-left">
                    Action
                </th>
                </tr>
            </thead>
            <tbody>
                <tr
                v-for="product in products"
                :key="product.name"
                >
                <td>{{ product.name }}</td>
                <td>{{ product.manufacture_year }}</td>
                <td><img v-if="product.photo && user" :src=" getHost() + '/uploads/products/' + user.id + '/' + product.photo" alt="Product Image" style="max-width: 100px" /></td>
                <td>
                    <v-btn><router-link :to="{name: 'product.edit', params: {id: product.id}}">Edit</router-link></v-btn>
                    <v-btn @click="deleteProduct(product.id)">Delete</v-btn>
                </td>
                </tr>
            </tbody>
            </template>
        </v-simple-table>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        data () {
            return {}
        },
        computed: {
            ...mapGetters('product', [
                'products'
            ]),
            ...mapGetters('auth', [
                'user'
            ])
        },
        mounted() {
            this.fetchData()
        },
        methods: {
            ...mapActions('auth', [
                'setUser'
            ]),
            ...mapActions('product', [
                'setProducts',
                'delete'
            ]),
            async fetchData() {
                await this.setUser()
                await this.setProducts()
                this.deserts = this.products
            },
            getHost: function() {
                return location.protocol + '//' + location.host
            },
            deleteProduct: function (id) {
                Swal.fire({
                title: 'Do you want to delete the product?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: `Save`,
                denyButtonText: `No`,
            }).then(async (result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    let response = await this.delete(id)

                    console.log(response)

                    if (response == true) {
                        Swal.fire('The product deleted successfully.', '', 'success')
                        this.fetchData()
                    } else {
                        Swal.fire('Something went wrong. Please contact administrator.', '', 'error')
                    }
                    
                } else if (result.isDenied) {
                    Swal.fire('You cancelled. Please try again.', '', 'info')
                }
            })
            }
        }
    }
</script>