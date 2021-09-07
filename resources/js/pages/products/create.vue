
<template>
    <div>
        <h2 class="title">Create Product</h2>
        <v-btn class="mb-3"><router-link :to="{name: 'product'}">Products</router-link></v-btn>
        <validation-observer
            ref="observer"
            v-slot="{ invalid }"
        >
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <validation-provider
                v-slot="{ errors }"
                name="Name"
                rules="required"
            >
                <v-text-field
                v-model="form.name"
                :error-messages="errors"
                label="Name"
                required
                ></v-text-field>
            </validation-provider>
            
            <validation-provider
                v-slot="{ errors }"
                name="manufacture_year"
                :rules="'required|between:1900,' + new Date().getFullYear()"
            >
                <v-text-field
                v-model="form.manufacture_year"
                :error-messages="errors"
                label="Manufactured Year"
                required
                ></v-text-field>
            </validation-provider>

            <v-file-input
                v-model="form.photo"
                accept="*"
                placeholder="Choose product image"
                prepend-icon="mdi-camera"
                label="Photo"
            ></v-file-input>

            <v-btn
                class="mr-4"
                type="submit"
                :disabled="invalid"
            >
                submit
            </v-btn>
            <v-btn @click="clear">
                clear
            </v-btn>
        </form>
        </validation-observer>
    </div>
</template>

<script>
  import { required, between } from 'vee-validate/dist/rules'
  import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
  import { mapActions } from 'vuex'

  setInteractionMode('eager')


  extend('required', {
    ...required,
    message: '{_field_} can not be empty',
  })

  extend('between', {
    ...between,
    message: '{_field_} must be between 1900 and 2021.',
  })


  export default {
    components: {
      ValidationProvider,
      ValidationObserver,
    },
    data: () => ({
        form: {
            name: '',
            manufacture_year: '',
            photo: null,
        }
    }),
    methods: {
        ...mapActions('product', [
            'create'
        ]),
      async submit () {
        this.$refs.observer.validate()

        console.log(this.form.photo)

        const data = new FormData();
        data.append('photo', this.form.photo);
        data.append('name', this.form.name);
        data.append('manufacture_year', this.form.manufacture_year);

        console.log(data)

        // this.$store.dispatch('product/create', this.form)
        let response = await this.create(data)

        console.log(response)

        if (response === true) {
            this.$router.push({name: 'product'})
        }

      },
      clear () {
        this.name = ''
        this.manufacture_year = ''
        this.photo = ''
        this.$refs.observer.reset()
      },
    },
  }
</script>