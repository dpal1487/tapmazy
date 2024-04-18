<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { toast } from "vue3-toastify";
import utils from "../../utils";
import DragDropImage from "@/Components/DragDropImage.vue"

export default defineComponent({
    props: ['faq_category', 'image'],
    setup() {
        return { v$: useVuelidate() };
    },
    validations() {
        return {
            form: {
                title: {
                    required,
                },
                status: {
                    required,
                },
                image: {
                },
            },
        };
    },
    data() {
        return {
            isEdit: false,
            isUploading: false,
            processing: false,
            title: "Faq's Category",
            thumbnail: {
                isLoading: false,
                url: null,
            },
            form: this.$inertia.form({
                id: this.faq_category?.data?.id || '',
                title: this.faq_category?.data?.title || '',
                status: this.faq_category?.data?.is_published || 0,
                image: this.image
                    ? this.image?.data
                    : {
                        id: "",
                        url: "",
                    },
            }),
            status: [
                { id: '1', name: 'Published' },
                { id: '0', name: 'Unpublished' },
            ],
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        Multiselect,
        JetInput,
        JetLabel,
        InputError,
        DragDropImage,
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.form.transform((data) => ({
                    ...data,
                }))
                    .post(route().current() == 'faq-category.create' ? this.route("faq-category.store") : this.route('faq-category.update', this.form.id), {
                        onSuccess: (data) => {
                            toast.success(this.$page.props.jetstream.flash.message);
                            this.isEdit = false;
                        },
                        onError: (data) => {
                            if (data.message) {
                                toast.error(data.message);
                            }
                        },
                    })

            }
        },
        async uploadImage(e) {

            this.thumbnail.isLoading = true;
            const data = await utils.imageUpload(route("image.store", 'faq-category'), e, this.image?.data?.entity_id);
            if (data.response) {
                this.form.image = data.response.data;
            } else {
                toast.error(data.response);
            }
            this.thumbnail.url = URL.createObjectURL(data.file);
            this.thumbnail.isLoading = false;
        },
        removeSelectedAvatar() {
            this.thumbnail.url = null;
        }
    },
    created() {
        if (route().current() == 'faq-category.edit') {
            this.isEdit = true;
        }
    }
});
</script>
<template>
    <Head :title="isEdit ? 'Edit Faq Category' : `Add New Faq Category`" />
    <AppLayout :title="isEdit ? 'Edit Faq Category' : `Add New Faq's Category`">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/faqs-categories" class="text-muted text-hover-primary">{{ title }}</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <span class="text-muted text-hover-primary">Form</span>
            </li>
        </template>
        <div class="d-flex flex-column flex-lg-row flex-column-fluid justify-content-center">
            <div class="col-12">
                <form @submit.prevent="submit()" class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <div class="">
                        <div class="row g-5">
                            <div class="col-md-4 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Image</h2>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="fv-row">
                                            <DragDropImage :image="this.image?.data" :onchange="uploadImage"
                                                :remove="removeSelectedAvatar" :selectedImage="thumbnail?.url"
                                                :errors="v$.form.image.$errors" :isUploading="thumbnail?.isLoading" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>General</h2>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-5">
                                            <jet-label for="title" value="Faq's Category Title" />
                                            <jet-input id="title" type="text" v-model="v$.form.title.$model" :class="v$.form.title.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Faq's Category Title" />
                                            <div v-for="(error, index) of v$.form.title.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                        <div class="row mb-5">
                                            <jet-label for="page" value="Status" />
                                            <Multiselect :can-clear="false" :options="status" label="name" valueProp="id"
                                                class="form-control form-control-solid" placeholder="Select Status"
                                                v-model="v$.form.status.$model" :class="v$.form.status.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " />
                                            <div v-for="(error, index) of v$.form.status.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end text-align-center gap-5">
                                <Link href="/faqs-categories" class="btn btn-outline-secondary">
                                Discard
                                </Link>
                                <div>
                                    <button type="submit" class="btn btn-primary"
                                        :class="{ 'text-white-50': form.processing }">
                                        <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span v-if="route().current() == 'faq-category.edit'">Save Changes</span>
                                        <span v-if="route().current() == 'faq-category.create'">Save</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
