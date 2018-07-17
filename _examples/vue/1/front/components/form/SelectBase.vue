<template>
    <div>
        <div class="form-group" :class="{'has-error': value.errors.has(field) }">
            <label class="col-sm-2 control-label">{{ title }}</label>
            <div class="col-sm-4">
                <select   class="form-control" v-model="value[field]">
                    <option v-for="item of data" :value="item.key">{{item.value}}</option>
                </select>
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
            data:{
              type: Array
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
