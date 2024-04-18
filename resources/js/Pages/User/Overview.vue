<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Header from "./Components/Header.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { toast } from "vue3-toastify";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import InputError from "@/jetstream/InputError.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import useVuelidate from "@vuelidate/core";
import { required, email, url } from "@vuelidate/validators";
import Multiselect from "@vueform/multiselect";
import ImageInput from '@/components/ImageInput.vue';

export default defineComponent({
    setup() {
        return { v$: useVuelidate() };
    },
    props: ['user', 'address'],

    components: {
        AppLayout,
        Header,
        Link,
        Head,
        JetInput,
        JetLabel,
        JetValidationErrors,
        InputError,
        Multiselect,
        ImageInput,
        VueDatePicker

    },
    validations() {
        return {
            form: {
                first_name: {
                    required,
                },
                last_name: {
                    required,
                },
                date_of_birth: {
                    required,
                },
                gender: {
                    required,
                }
            },
        };
    },
    data() {
        return {
            title: "User",

            isEdit: false,
            isUploading: false,
            processing: false,
            url: null,
            id: route().params.id,
            form: this.$inertia.form({
                id: this.user?.id || '',
                image: this.user?.data?.full_path || '',
                image_id: '',
                first_name: this.user?.data?.first_name || '',
                last_name: this.user?.data?.last_name || '',
                date_of_birth: this.user?.data?.date_of_birth || '',
                dark_mode: this.user?.data?.dark_mode || '',
                gender: this.user?.data?.gender || '',
            }),

        };
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.form.transform((data) => ({
                    ...data,
                }))
                    .post(this.route("account.user.store"),
                        {
                            onSuccess: (data) => {
                                toast.success(this.$page.props.jetstream.flash.message);
                                this.isEdit = false;
                            },
                            onError: (data) => {
                                console.log(data);
                            },
                        });
            }
        },

        onFileChange(e) {
            const file = e.target.files[0];
            this.$data.form.image = file;
            this.selectedFilename = file?.name;
            this.url = URL.createObjectURL(file);
            const formdata = new FormData();
            formdata.append("image", file)

            this.isUploading = true;
            axios.post("/avatar-upload", formdata, {
                headers: {
                    "Content-Type": "multipart/form-data",
                }
            }).then((response) => {
                if (response.data.success) {
                    // toast.success(response.data.message);
                    this.form.image_id = response.data.data.id;
                } else {
                    toast.error(response.data.message);
                }
            }).finally(() => {
                this.isUploading = false;
            })



        },
        removeSelectedAvatar() {
            this.url = null;
        }
    },

});
</script>
<template>
    <Head :title="title" />
    <AppLayout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/users" class="text-muted text-hover-primary">Users</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ title }} Overview
            </li>
        </template>
        <Header :user="user.data" :address="address?.data" />
        <div class="card mb-5 mb-xl-10">
            <div class="card-header cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0"> Account Overview</h3>
                </div>
                <button class="btn btn-primary align-self-center btn-sm"
                    @click="this.isEdit = this.isEdit ? false : true"><i class="bi bi-pencil me-2"></i>Edit Account
                </button>
            </div>
            <div class="card-body p-9">
                <div class="row" v-if="isEdit">
                    <div class="col-12">
                        <JetValidationErrors />
                        <form @submit.prevent="submit()" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                            novalidate="novalidate">
                            <div class="card-body border-top p-9">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
                                    <div class="col-lg-6">
                                        <ImageInput :image="this.user?.data?.full_path" :onchange="onFileChange"
                                            :remove="removeSelectedAvatar" :selectedImage="url"
                                            :isUploading="isUploading" />

                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Full Name</label>
                                    <div class="col-lg-6">
                                        <div class="row g-3">
                                            <div class="col-lg-6  fv-plugins-icon-container">
                                                <jet-input id="first_name" type="text" v-model="v$.form.first_name.$model"
                                                    :class="v$.form.first_name.$errors.length > 0
                                                        ? 'is-invalid'
                                                        : ''
                                                        " placeholder="First name" />
                                                <div v-for="(error, index) of v$.form.first_name.$errors" :key="index">
                                                    <input-error :message="error.$message" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6  fv-plugins-icon-container">
                                                <jet-input id="last_name" type="text" v-model="v$.form.last_name.$model"
                                                    :class="v$.form.last_name.$errors.length > 0
                                                        ? 'is-invalid'
                                                        : ''
                                                        " placeholder="Last name" />
                                                <div v-for="(error, index) of v$.form.last_name.$errors" :key="index">
                                                    <input-error :message="error.$message" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Date Of Birth</label>
                                    <div class="col-lg-6  fv-plugins-icon-container">
                                        <VueDatePicker v-model="v$.form.date_of_birth.$model" :enable-time-picker="false"
                                            auto-apply
                                            input-class-name="form-control form-control-lg form-control-solid fw-normal"
                                            :class="v$.form.date_of_birth.$errors.length > 0
                                                ? 'is-invalid'
                                                : ''
                                                " placeholder="Date Of birth"></VueDatePicker>
                                        <div v-for="(error, index) of v$.form.date_of_birth.$errors" :key="index">
                                            <input-error :message="error.$message" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Gender</label>
                                    <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                        <div class="d-flex align-items-center mt-3">
                                            <label
                                                class="form-check form-check-custom form-check-inline form-check-solid me-5">
                                                <input class="form-check-input" type="radio" value="male" name="gender"
                                                    v-model="form.gender" />
                                                <div class="form-check-label">
                                                    Male
                                                </div>
                                            </label>
                                            <label class="form-check form-check-custom form-check-inline form-check-solid">
                                                <input class="form-check-input" type="radio" value="female" name="gender"
                                                    v-model="form.gender" />
                                                <div class="form-check-label">
                                                    Female
                                                </div>
                                            </label>
                                        </div>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                    <div class="row mb-0">
                                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Dark Mode</label>
                                        <div class="col-lg-6 d-flex align-items-center">
                                            <div class="form-check form-check-solid form-switch form-check-custom ">
                                                <input class="form-check-input" type="checkbox" v-model="form.dark_mode"
                                                    valueProp="dark_mode" />
                                                <label class="form-check-label" for="allowmarketing"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end py-6 px-9">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="button" class="btn btn-outline-secondary me-5"
                                                @click="this.isEdit = false">Discard</button>
                                            <button type="submit" class="btn btn-primary"
                                                :class="{ 'text-white-50': form.processing }">
                                                <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div v-else>
                    <div class="card-body p-9">
                        <div class="row mb-7">
                            <label class="col-6 fw-bold fs-5 text-gray-800">Full Name</label>
                            <div class="col-lg-6">
                                <span class="fw-semibold fs-6 text-gray-800 ">{{ this.user?.data?.first_name }} {{
                                    this.user?.data?.last_name }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-6 fw-bold fs-5 text-gray-800">Email</label>
                            <div class="col-lg-6 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">{{ this.user?.data?.email }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-6 fw-bold fs-5 text-gray-800">Date Of Birth</label>
                            <div class="col-lg-6 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">{{ this.user?.data?.date_of_birth }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-6 fw-bold fs-5 text-gray-800">Gender</label>
                            <div class="col-lg-6 fv-row">
                                <span class="fw-semibold text-gray-800 fs-6">{{ this.user?.data?.gender }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
