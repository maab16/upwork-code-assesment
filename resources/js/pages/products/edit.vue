
<template>
    <div>
        <h2 class="title">Edit Product</h2>
        
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
                rules="required|between:1900,2021"
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
  import { required, digits, email, max, regex, between } from 'vee-validate/dist/rules'
  import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
  import { mapGetters, mapActions } from 'vuex'

  setInteractionMode('eager')

  extend('digits', {
    ...digits,
    message: '{_field_} needs to be {length} digits. ({_value_})',
  })

  extend('required', {
    ...required,
    message: '{_field_} can not be empty',
  })

  extend('max', {
    ...max,
    message: '{_field_} may not be greater than {length}',
  })

  extend('between', {
    ...between,
    message: '{_field_} must be between 1900 and 2021.',
  })

  extend('regex', {
    ...regex,
    message: '{_field_} {_value_} does not match {regex}',
  })

  extend('email', {
    ...email,
    message: 'Email must be valid',
  })

  export default {
      props: {
          id: {
              type: Number|String,
              required: true
          }
      },
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
    computed: {
        ...mapGetters('product', [
            'product'
        ])
    },
    async mounted () {
        console.log(this.id)
        await this.setProduct(this.id)
        this.form = this.product
    },
    methods: {
        ...mapActions('product', [
            'setProduct',
            'update'
        ]),
      async submit () {
        this.$refs.observer.validate()

        const formData = new FormData();
        formData.append('photo', this.form.photo);
        formData.append('name', this.form.name);
        formData.append('manufacture_year', this.form.manufacture_year);
        formData.append('_method', 'PUT');

        let response = await this.update({ id: this.form.id, form: formData})

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