<template>
    <div>
        <div class="inline-block w-full">
            <label v-if="label" :for="id" class="appearance-none text-grey-darkest float-none" :class="{ required: required }">
                {{ label }}
            </label>

            <div class="w-full h-full relative">

                <div class="text-sm text-grey-darkest text-center" v-if="photoUrl && !uploaded ">
                    <a :href="photoUrl">
                        <img :src="photoUrl" style="width: 100%; height: auto;">
                    </a>
                </div>

                <div class="text-sm text-grey-darkest text-center" v-if="showFilename && uploaded">
                    <a :href="response.url">
                        <img v-if="showImagePreview" :src="response.url" style="width: 100%; height: auto;">
                        <span v-else>{{ file.name }}</span>
                    </a>
                </div>

                <label :for="id"
                       :class="{ 'bg-green': uploaded, 'bg-green-lighter': !uploaded }"
                       class="w-full h-10 mt-8 hover:cursor-pointer float-none hover:opacity-75 z-20 relative">
                    <div class="text-center text-white text-base" style="opacity: .9.5;">
                        <div style="margin: auto; margin-top: 5px">
                            <div v-if="uploading">
                                <i class="fa fa-upload"></i>
                                <span>{{ uploadingText }}</span>
                                <span v-if="showPercents">{{ uploadProgress }} %</span>
                            </div>

                            <div v-if="!uploading && !uploaded">
                                <i class="fa fa-upload"></i> &nbsp;
                                <span>{{ buttonLabel }}</span>
                            </div>

                            <div v-if="uploaded">
                                <i class="fa fa-check"></i>&nbsp;
                                <span>{{ uploadedText }}</span>
                            </div>
                        </div>
                    </div>
                </label>

                <div class="text-sm text-red text-center" v-if="failed">
                    {{ failedText }}
                </div>

                <div v-if="error" class="text-sm text-red text-center">
                    {{ error }}
                </div>

                <!-- Progress background -->
                <div class="z-10 absolute pin-t pin-l h-10 bg-green" :style="{ 'width': uploadProgress + '%' }" style="top: 25px"></div>
            </div>
        </div>

        <input type="file" class="hidden" :id="id" @change="atChange" :name="fileName">
    </div>
</template>

<script>
    export default {
        props: {
            label: { type: String, required: false },

            buttonLabel: {
                type: String,
                required: false,
                default: 'Выбрать файл'
            },

            required: {
                type: Boolean,
                required: false
            },

            endpoint: {
                type: String,
                required: true
            },

            requestMethod: {
                type: String,
                required: false,
                default: 'POST'
            },

            requestParams: {
                type: Object,
                required: false,
                default: () => {}
            },

            fileName: {
                type: String,
                required: false,
                default: 'file'
            },

            showPercents: {
                type: Boolean,
                required: false,
                default: false
            },

            uploadingText: {
                type: String,
                required: false,
                default: 'Загрузка ...'
            },

            uploadedText: {
                type: String,
                required: false,
                default: 'Файл загружен'
            },

            failedText: {
                type: String,
                required: false,
                default: 'Произошла ошибка при загрузке'
            },

            showFilename: {
                type: Boolean,
                required: false,
                default: true
            },

            extensions: {
                type: Array,
                required: false,
                default: () => []
            },

            maxSize: {
                type: Number,
                required: false,
                default: 10 // Mb
            },

            showImagePreview: {
                type: Boolean,
                required: false,
                default: true
            },
            photo: {
                type: Object,
            },
        },

        data() {
            return {
                id: null,
                file: null,
                uploadProgress: 0,
                uploading: false,
                uploaded: false,
                failed: false,
                error: '',
                response: null
            };
        },

        created() {
            this.id = Math.random().toString(36).substring(7);
        },

        methods: {
            atChange(event) {
                this.$emit('change');

                this.file = event.target.files[0];

                if (! this.validateFile()) {
                    return;
                }

                this.uploading = true;
                this.uploaded = false;
                this.failed = false;

                let formData = new FormData();

                formData.append(this.fileName, this.file);

                ajax.post(this.endpoint, formData)
                    .then(response => {
                        this.uploading = false;
                        this.failed = false;

                        this.uploaded = true;
                        this.response = response.data.data.item;

                        this.$emit('uploaded', this.response);
                        this.$emit('input', this.response.id);
                    })
                    .catch(error => {
                        this.uploading = false;
                        this.uploadProgress = 0;
                        this.uploaded = false;
                        this.failed = true;

                        this.$emit('failed');
                        if(error.response.data.data.validate.file){
                            this.error = error.response.data.data.validate.file[0]
                        }
                    });

            },

            validateFile() {
                this.error = '';
                this.uploaded = false;

                if (this.extensions.length > 0 &&
                    new RegExp('\\.(' + this.extensions.join('|').replace(/\./g, '\\.') + ')$', 'i').test(this.file.name) === false
                ) {
                    this.error = 'Недопустимый формат файла. ' + 'Разрешенные форматы: ' + this.extensions.join(', ') + '.';

                    return false;
                }

                if (this.file.size / 1024 / 1024 > this.maxSize) {
                    this.error = 'Максимальный размер файла: ' + this.maxSize + ' мб.';

                    return false;
                }

                return true;
            }
        },
        computed:{
            photoUrl(){
                if(this.photo){
                    return this.photo.url;
                }

                if(this.response.url){
                    return this.response.url
                }

                return null;
            },
            photoName(){
                if(this.photo){
                    return this.photo.name;
                }

                if(this.response.url){
                    return this.file.name
                }

                return null;
            }
        }
    }
</script>
