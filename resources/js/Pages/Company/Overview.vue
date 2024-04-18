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
import useVuelidate from "@vuelidate/core";
import { required, email, url } from "@vuelidate/validators";
import Multiselect from "@vueform/multiselect";

export default defineComponent({
    setup() {
        return { v$: useVuelidate() };
    },
    props: ['company', 'countries', 'sizes'],
    components: {
        AppLayout,
        Header,
        Link,
        Head,
        JetInput,
        JetLabel,
        JetValidationErrors,
        InputError,
        Multiselect
    },
    validations() {
        return {
            form: {
                company_type: {
                    required,
                },
                company_size: {
                    required,
                },
                legal_registration_no: {
                    required,
                },
                business_name: {
                    required,
                },
                business_subdomain: {
                    required,
                },
                company_website: {
                },
                skype_profile: {
                },
                linkedin_profile: {
                },
                short_description: {
                    required
                },
                corporation_type: {
                    required
                },
                business_description: {
                },

            },
        };
    },
    data() {
        return {
            form: this.$inertia.form({
                company_type: this.company?.data.company_type,
                company_size: this.company?.data.company_size.id,
                legal_registration_no: this.company?.data.legal_registration_no,
                business_name: this.company?.data.company_name,
                business_subdomain: this.company?.data.subdomain,
                company_website: this.company?.data.website,
                skype_profile: this.company?.data.skype_profile,
                linkedin_profile: this.company?.data.linkedin_profile,
                short_description: this.company?.data.short_description,
                corporation_type: this.company?.data.corporation_type.id,
                business_description: this.company?.data.description,
            }),
            isEdit: false,
            title: "Company Overview",
        };
    },
    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.form.transform((data) => ({
                    ...data,
                }))
                    .post(this.route("company.update"),
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
        companySize(event) {
            Array.from(document.querySelectorAll('.radio-btn')).forEach(
                (el) => el.classList.remove('active')
            );
            event.target.classList.add('active');
        }
    },
    created() {
    }
});
</script>
<template>
    <Head :title="title" />
    <app-layout :title="title">

        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                Overview
            </li>
        </template>
        <!--begin::Navbar-->
        <Header :company="company.data" />
        <!--begin::details View-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Company Details</h3>
                    <!-- {{ company.data }} -->
                </div>
                <!--end::Card title-->
                <button class="btn btn-primary align-self-center" @click="this.isEdit = this.isEdit ? false : true"><i
                        class="bi bi-pencil me-2"></i>Edit Company</button>
                <!-- <a href="settings.html" class="btn btn-primary align-self-center">Edit Profile</a> -->
            </div>
            <!--begin::Card header-->
            <!--begin::Card body-->
            <div class="card-body p-9">
                <div class="row" v-if="isEdit">
                    <div class="col-6">
                        <JetValidationErrors />
                        <form @submit.prevent="submit" class="my-auto pb-5 fv-plugins-bootstrap5 fv-plugins-framework">
                            <!--begin::Wrapper-->
                            <div class="w-100">
                                <!--begin::Heading-->
                                <!--begin::Heading-->
                                <div class="pb-10 pb-lg-12">
                                    <!--begin::Title-->
                                    <h2 class="fw-bold text-dark">
                                        Business Details
                                    </h2>
                                    <!--end::Title-->
                                </div>
                                <!--end::Heading-->


                                <!--begin::Input group-->
                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <!--begin::Option-->
                                            <input type="radio" name="account_type" class="btn-check"
                                                v-model="v$.form.company_type.$model" :class="v$.form.company_type.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " value="personal" checked="checked" id="account_type_personal" />
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-10"
                                                for="account_type_personal">
                                                <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                                                <span class="svg-icon svg-icon-3x me-5"><svg width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                                            fill="currentColor"></path>
                                                        <path opacity="0.3"
                                                            d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                                <!--begin::Info-->
                                                <span class="d-block fw-semibold text-start">
                                                    <span class="text-dark fw-bold d-block fs-4 mb-2">
                                                        Personal Account
                                                    </span>
                                                    <span class="text-muted fw-semibold fs-6">If you need more
                                                        info, please check
                                                        it out</span>
                                                </span>
                                                <!--end::Info-->
                                            </label>
                                            <!--end::Option-->
                                            <div v-for="(error, index) of v$.form.company_type.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                        <!--end::Col-->

                                        <!--begin::Col-->
                                        <div class="col-lg-6">
                                            <!--begin::Option-->
                                            <input type="radio" class="btn-check" v-model="v$.form.company_type.$model"
                                                :class="v$.form.company_type.$errors.length > 0
                                                    ? 'is-invalid'
                                                    : ''
                                                    " name="account_type" value="corporate"
                                                id="account_type_corporate" />
                                            <label
                                                class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center"
                                                for="account_type_corporate">
                                                <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                                <span class="svg-icon svg-icon-3x me-5"><svg width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.3"
                                                            d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z"
                                                            fill="currentColor"></path>
                                                        <path
                                                            d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                                <!--begin::Info-->
                                                <span class="d-block fw-semibold text-start">
                                                    <span class="text-dark fw-bold d-block fs-4 mb-2">Corporate
                                                        Account</span>
                                                    <span class="text-muted fw-semibold fs-6">Create corporate
                                                        account to mane
                                                        users</span>
                                                </span>
                                                <!--end::Info-->
                                            </label>
                                            <!--end::Option-->
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Wrapper-->
                            <div class="row g-5">
                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="form-label required">Specify Team Size <i
                                            class="fas fa-exclamation-circle ms-2 fs-7"></i></label>
                                    <!--begin::Row-->
                                    <select class="form-control form-control-lg form-control-solid"
                                        v-model="v$.form.company_size.$model" :class="v$.form.company_size.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            ">
                                        <option v-for="(size, index) in sizes" :key="index" :value="size.id">
                                            {{ size.label }}
                                        </option>
                                    </select>
                                    <!--end::Row-->
                                    <div v-for="(error, index) of v$.form.company_size.$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                </div>
                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="form-label required">Leagal Registration Number</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-lg form-control-solid"
                                        v-model="v$.form.legal_registration_no.$model" :class="v$.form.legal_registration_no.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " />
                                    <div v-for="(error, index) of v$.form.legal_registration_no.$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--begin::Input group-->
                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="form-label required">Business Name</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <input class="form-control form-control-lg form-control-solid"
                                        v-model="v$.form.business_name.$model" :class="v$.form.business_name.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " />

                                    <div v-for="(error, index) of v$.form.business_name.$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="form-label required">Business Subdomain</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <input class="form-control form-control-lg form-control-solid"
                                        v-model="v$.form.business_subdomain.$model" :class="v$.form.business_subdomain.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " />

                                    <div v-for="(error, index) of v$.form.business_subdomain.$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center form-label">Company Website
                                    </label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <input class="form-control form-control-lg form-control-solid"
                                        v-model="v$.form.company_website.$model" :class="v$.form.company_website.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " />

                                    <div v-for="(error, index) of v$.form.company_website.$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center form-label">Company Skype Profile
                                    </label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <input class="form-control form-control-lg form-control-solid"
                                        v-model="v$.form.skype_profile.$model" :class="v$.form.skype_profile.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " />

                                    <div v-for="(error, index) of v$.form.skype_profile.$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center form-label">Company LinkedIn Profile
                                    </label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <input class="form-control form-control-lg form-control-solid"
                                        v-model="v$.form.linkedin_profile.$model" :class="v$.form.linkedin_profile.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " />

                                    <div v-for="(error, index) of v$.form.linkedin_profile.$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="d-flex align-items-center form-label">
                                        <span class="required">Shortened Description</span>
                                    </label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <input class="form-control form-control-lg form-control-solid"
                                        v-model="v$.form.short_description.$model" :class="v$.form.short_description.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " />

                                    <div v-for="(error, index) of v$.form.short_description.$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                    <!--end::Input-->
                                    <!--begin::Hint-->
                                    <div class="form-text">
                                        Customers will see this shortened
                                        version of your statement descriptor
                                    </div>
                                    <!--end::Hint-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="fv-row fv-plugins-icon-container">
                                    <!--begin::Label-->
                                    <label class="form-label required">Corporation Type</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <select class="form-control form-control-lg form-control-solid"
                                        v-model="v$.form.corporation_type.$model" :class="v$.form.corporation_type.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            ">
                                        <option value="1">
                                            S Corporation
                                        </option>
                                        <option value="1">
                                            C Corporation
                                        </option>
                                        <option value="2">
                                            Sole Proprietorship
                                        </option>
                                        <option value="3">
                                            Non-profit
                                        </option>
                                        <option value="4">
                                            Limited Liability
                                        </option>
                                        <option value="5">
                                            General Partnership
                                        </option>
                                    </select>
                                    <div v-for="(error, index) of v$.form.corporation_type.$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group-->
                                <div class="fv-row">
                                    <!--end::Label-->
                                    <label class="form-label">Business Description</label>
                                    <!--end::Label-->

                                    <!--begin::Input-->
                                    <textarea name="business_description"
                                        class="form-control form-control-lg form-control-solid"
                                        v-model="v$.form.business_description.$model" :class="v$.form.business_description.$errors.length > 0
                                            ? 'is-invalid'
                                            : ''
                                            " rows="3"></textarea>

                                    <div v-for="(error, index) of v$.form.business_description.$errors" :key="index">
                                        <input-error :message="error.$message" />
                                    </div>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Wrapper-->
                            <div class="d-flex justify-content-end mt-4">
                                <!--begin::Button-->
                                <button type="button" class="btn btn-outline-secondary me-5"
                                    @click="this.isEdit = false">Discard</button>
                                <!--begin::Button-->
                                <button type="submit" class="btn btn-primary" :class="{ 'text-white-50': form.processing }">
                                    <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div v-else>
                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-6 fw-semibold text-muted">Company Type</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-6">
                            <span class="fw-semibold text-gray-800 fs-6"> {{ company.data.company_type }}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-6 fw-semibold text-muted">Company Size</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <span class="fw-bold fs-6 text-gray-800">{{ company.data.company_size.label }}
                            </span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-6 fw-semibold text-muted">Corporation Type</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-6 d-flex align-items-center">
                            <span class="fw-bold fs-6 text-gray-800 me-2">{{ company.data.corporation_type.title
                            }}
                            </span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-6 fw-semibold text-muted">Legal Registration_no</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-6 d-flex align-items-center">
                            <span class="fw-bold fs-6 text-gray-800 me-2">{{
                                company.data.legal_registration_no
                            }}
                            </span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->


                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-6 fw-semibold text-muted">Website</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-lg-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{ company.data.website }}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-6 fw-semibold text-muted">Subdomain</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-lg-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{ this.company?.data?.subdomain
                            }}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-6 fw-semibold text-muted">Linkedin Profile</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-lg-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{ this.company?.data?.linkedin_profile
                            }}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-6 fw-semibold text-muted">Skype Profile</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-lg-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{ company.data.skype_profile
                            }}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-6 fw-semibold text-muted">Shortened Description</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-lg-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{ company.data.short_description
                            }}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-6 fw-semibold text-muted">Description</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-lg-6">
                            <span class="fw-semibold fs-6 text-gray-800">{{ company.data.description
                            }}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::details View-->
        </div>
        <!--end::Content container-->
    </app-layout>
</template>
