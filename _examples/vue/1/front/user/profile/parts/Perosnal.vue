<template>
    <form class="form-horizontal" @submit.prevent>

        <input-base
                v-model="personal"
                :title="'Имя'"
                :field="'first_name'"
        ></input-base>

        <select-base
                v-model="personal"
                :title="'Город'"
                :field="'city_id'"
                :data="cities"
        ></select-base>

        <div class="form-group ">
            <label class="col-sm-2 control-label">Логотип</label>
            <div class="col-sm-4">
                <file-upload label=""
                             endpoint="/upload/profile"
                             fileName="file"
                             :showImagePreview="true"
                             v-model="personal.photo_id"
                             :photo="photo"
                >
                </file-upload>
            </div>
        </div>


        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
                <button class="btn btn-success" @click="savePersonal">Сохранить</button>
            </div>
        </div>

    </form>

</template>


<script>

    import Form from '../../../components/form/Form';

    import FormInline from '../../../components/form/FormInline';
    import InputBase from '../../../components/form/InputBase';
    import SelectBase from '../../../components/form/SelectBase';
    import FileUpload from '../../../components/form/FileUpload';

    export default {

        props:  {
            value:{
                default: {}
            }
        },
        components: {InputBase, FormInline, FileUpload,SelectBase},
        data()
        {
            return {
                personal: new Form({
                    first_name: this.value.first_name,
                    city_id:    _.get(this.value.profile, 'city_id'),
                }),
                cities:[],
            }
        },

        methods: {
            savePersonal()
            {
                this.personal.submit('post', '/personal/set-personal')
                    .then(response => {

                    })
                    .catch(error => {

                    })
            },

            getGeo(){
                ajax.get('/geo')
                    .then(response => {
                        let data = response.data.data.items;
                        this.cities = data.cities;
                        this.countries = data.countries;
                    })
                    .catch(e => {})
            }
        },
        computed:{
        },
        created(){
            this.getGeo();
        }

    }
</script>
