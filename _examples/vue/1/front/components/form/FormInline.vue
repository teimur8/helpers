<template>
    <form class="form-horizontal" @submit.prevent>
    </form>
</template>

<script>
    export default {
        data()
        {
            return {}
        },
        props: {
            url:   {
                type:    String,
                default: null
            },
            field: {
                type: String
            }

        },

        //methods:  {},
        computed: {
            hasError()
            {
                return this.error;
            }
        }
    }
</script>
