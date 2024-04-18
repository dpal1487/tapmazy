
<script>
import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import { toast } from "vue3-toastify";
import InputError from "@/jetstream/InputError.vue";
import Header from "./Components/Header.vue";
export default defineComponent({
    setup() {
        return { v$: useVuelidate() };
    },
    components: {
        Head,
        Link,
        JetValidationErrors,
        InputError,
        Header
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
                company_type: "",
                company_size: "",
                legal_registration_no: "",
                business_name: "",
                business_subdomain: "",
                company_website: "",
                skype_profile: "",
                linkedin_profile: "",
                short_description: "",
                corporation_type: "",
                business_description: "",
            }),
        };
    },

    methods: {
        submit() {
            this.v$.$touch();
            if (!this.v$.form.$invalid) {
                this.form.transform((data) => ({
                    ...data,
                })).post(this.route("company.store"),
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
    },
});
</script>

<template>
    <Head title="Create Company" />
    <div class="d-flex flex-column flex-root">
        <div class="app-header" style="left:0px;">
            <!--begin::Header container-->
            <div class="app-container  container-fluid d-flex align-items-stretch justify-content-between">
                <!--begin::Sidebar mobile toggle-->
                <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
                    <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                        <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1"><span class="path1"></span><span
                                class="path2"></span></i>
                    </div>
                </div>
                <!--end::Sidebar mobile toggle-->
                <!--begin::Mobile logo-->
                <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                    <a href="/metronic8/demo1/../demo1/index.html" class="d-lg-none">
                        <img alt="Logo" src="/metronic8/demo1/assets/media/logos/default-small.svg" class="h-30px">
                    </a>
                </div>
                <!--end::Mobile logo-->

                <!--begin::Header wrapper-->
                <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">

                    <!--begin::Menu wrapper-->
                    <div class="app-header-menu app-header-mobile-drawer align-items-stretch">
                        <!--begin::Menu-->
                        <div
                            class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semiboldpx-2 px-lg-0">
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Menu wrapper-->
                </div>
                <!--end::Header wrapper-->
            </div>
            <!--end::Header container-->
        </div>
        <!--begin::Authentication - Multi-steps-->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid py-10">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid">
                    <!--begin::Wrapper-->
                    <div class="w-lg-650px w-xl-700px p-10 p-lg-15 mx-auto">
                        <JetValidationErrors />
                        <!--begin::Form-->
                        <div class="card">
                            <div class="card-body">
                                <form @submit.prevent="submit"
                                    class="my-auto pb-5 fv-plugins-bootstrap5 fv-plugins-framework">
                                    <!--begin::Wrapper-->
                                    <div class="w-100">
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
                                                            " value="personal" checked="checked"
                                                        id="account_type_personal" />
                                                    <label
                                                        class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center mb-10"
                                                        for="account_type_personal">
                                                        <!--begin::Svg Icon | path: icons/duotune/communication/com005.svg-->
                                                        <span class="svg-icon svg-icon-3x me-5"><svg width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
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
                                                    <div v-for="(error, index) of v$.form.company_type.$errors"
                                                        :key="index">
                                                        <input-error :message="error.$message" />
                                                    </div>
                                                </div>
                                                <!--end::Col-->

                                                <!--begin::Col-->
                                                <div class="col-lg-6">
                                                    <!--begin::Option-->
                                                    <input type="radio" class="btn-check"
                                                        v-model="v$.form.company_type.$model" :class="v$.form.company_type.$errors.length > 0
                                                            ? 'is-invalid'
                                                            : ''
                                                            " name="account_type" value="corporate"
                                                        id="account_type_corporate" />
                                                    <label
                                                        class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center"
                                                        for="account_type_corporate">
                                                        <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                                        <span class="svg-icon svg-icon-3x me-5"><svg width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
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
                                    <div class="w-100">
                                        <!--begin::Input group-->
                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                            <!--begin::Label-->
                                            <label class="d-flex align-items-center form-label mb-3">
                                                Specify Team Size
                                                <i class="fas fa-exclamation-circle ms-2 fs-7"></i>
                                            </label>
                                            <!--end::Label-->

                                            <!--begin::Row-->
                                            <div class="row mb-2">
                                                <!--begin::Col-->
                                                <div class="col">
                                                    <!--begin::Option-->
                                                    <label
                                                        class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
                                                        <input type="radio" name="company_size" class="btn-check"
                                                            v-model="v$.form.company_size.$model" :class="v$.form.company_size.$errors.length > 0
                                                                ? 'is-invalid'
                                                                : ''
                                                                " value="1-1" />
                                                        <span class="fw-bold fs-3">1-1</span>
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Col-->
                                                <!--begin::Col-->
                                                <div class="col">
                                                    <!--begin::Option-->
                                                    <label
                                                        class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
                                                        <input type="radio" name="company_size" class="btn-check"
                                                            v-model="v$.form.company_size.$model" :class="v$.form.company_size.$errors.length > 0
                                                                ? 'is-invalid'
                                                                : ''
                                                                " value="2-10" />
                                                        <span class="fw-bold fs-3">2-10</span>
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Col-->
                                                <!--begin::Col-->
                                                <div class="col">
                                                    <!--begin::Option-->
                                                    <label
                                                        class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
                                                        <input v-model="v$.form.company_size.$model" :class="v$.form.company_size.$errors.length > 0
                                                            ? 'is-invalid'
                                                            : ''
                                                            " type="radio" name="company_size" class="btn-check"
                                                            value="10-50" />
                                                        <span class="fw-bold fs-3">10-50</span>
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Col-->

                                                <!--begin::Col-->
                                                <div class="col">
                                                    <!--begin::Option-->
                                                    <label
                                                        class="btn btn-outline btn-outline-dashed btn-active-light-primary w-100 p-4">
                                                        <input v-model="v$.form.company_size.$model" :class="v$.form.company_size.$errors.length > 0
                                                            ? 'is-invalid'
                                                            : ''
                                                            " type="radio" name="company_size" class="btn-check"
                                                            value="50+" />
                                                        <span class="fw-bold fs-3">50+</span>
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Row-->
                                            <div v-for="(error, index) of v$.form.company_size.$errors" :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Wrapper-->

                                    <!--begin::Wrapper-->
                                    <div class="row g-5">
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
                                            <div v-for="(error, index) of v$.form.legal_registration_no.$errors"
                                                :key="index">
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

                                            <div v-for="(error, index) of v$.form.business_description.$errors"
                                                :key="index">
                                                <input-error :message="error.$message" />
                                            </div>
                                            <!--end::Input-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Actions-->
                                    <div class="d-flex flex-stack mt-10">
                                        <button type="submit" class="btn btn-primary w-100"
                                            :class="{ 'text-white-50': form.processing }">
                                            <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            Continue <span class="svg-icon svg-icon-4 ms-2"><svg width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1"
                                                        transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                                    <path
                                                        d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                    <!--end::Actions-->
                                </form>
                            </div>
                        </div>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Multi-steps-->
    </div>
</template>