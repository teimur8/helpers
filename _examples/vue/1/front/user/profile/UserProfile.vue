<template>
    <div class="row">
        <div class="col-md-12">
            <div class="row mt-8">
                <div class="col-md-12">
                    <v-wait for="userLoad">
                        <form-personal v-model="user"></form-personal>
                    </v-wait>
                </div>
            </div>
        </div>
    </div>
</template>


<script>


    import Form from '../../components/form/Form';

    import FormInline from '../../components/form/FormInline';
    import InputBase from '../../components/form/InputBase';
    import FileUpload from '../../components/form/FileUpload';
    import FormPersonal from './parts/Perosnal';

    export default {

        components: {InputBase, FormInline, FileUpload, FormPersonal},
        data()
        {
            return {
            }
        },

        methods:  {


        },
        computed: {

        },
        created()
        {
            this.getUser();
        }

    }
</script>
