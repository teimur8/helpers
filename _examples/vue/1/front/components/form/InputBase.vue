<template>
    <div>
        <div class="form-group" :class="{'has-error': value.errors.has(field) }">
            <label class="col-sm-2 control-label">{{ title }}</label>
            <div class="col-sm-4">
                <input :type="type" class="form-control" v-model="value[field]">
                <p class="help-block help-block-error" v-if="value.errors.has(field)">{{ value.errors.has(field) }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data()
        {
            return {}
        },
        props: {
            value: {
                default() {
                    return ''
                },
            },
            type:{
                type: String,
                default: 'text'
            },
            field:{
                type: String,
            },
            title:{
                type: String,
                default: null
            },
        },

        //methods:  {},
        computed: {
            hasError(){
                return this.error;
            }
        }
    }
</script>
