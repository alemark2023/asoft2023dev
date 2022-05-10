<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">{{ company.name }} - {{ company.number }}</h3>
        </div>
        <div class="card-body"> 

            <template v-if="payment_link.payment_link_type_id == '01'">
                
                <form-yape 
                    :payment_link="payment_link"
                    :payment_configuration="payment_configuration"
                    :total="total"
                    >
                </form-yape>

            </template>
             
        </div> 
    </div>
</template>

<script>
    import FormYape from './partials/yape.vue'

    export default {
        components: {
            FormYape,
        },
        props: [
            'payment_link',
            'company',
            'payment_configuration',
            'total',
        ],
        data() {
            return {
                resource: 'public-payment-links',
                form: {},
                errors: {},
                loading_submit: false,
            }
        },
        async created() {
            await this.initForm()
        },
        methods: {
            initForm(){

                this.form = {
                }

                this.errors = {}

            },
        }
    }
</script>
