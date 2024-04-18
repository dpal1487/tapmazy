<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import AccountForm from "./Components/Account/AccountForm.vue";
import Header from "./Components/Header.vue";
import { toast } from "vue3-toastify";
import AccountCard from "./Components/Account/AccountCard.vue";
import Swal from "sweetalert2";
export default defineComponent({
    props: ["company", 'accounts',],
    components: {
        AppLayout,
        Link,
        Head,
        Multiselect,
        AccountForm,
        Header,
        AccountCard
    },
    data() {
        return {
            isEdit: false,
            isAdd: false,
            form: {
                processing: false,
            },
            title: 'Manage Accounts'
        }
    },
    methods: {
        submit(form) {
            this.form = form;
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(this.isEdit ? this.route("company.updateAccount", this.company.data.id) : this.route("company.addAccount", this.company.data.id), {
                    onSuccess: (data) => {
                        toast.success(this.$page.props.jetstream.flash.message);
                        this.isEdit = false;
                        this.isAdd = false;
                    },
                    onError: (data) => {
                        console.log(data);
                    },
                });
        },
        toggleModal(isEdit, account) {
            this.isEdit = isEdit;
            this.form = account;
        },
        confirmDelete(index) {
            Swal.fire({
                title: "Are you sure you want to delete ?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .delete(this.route("company.delAccount", this.accounts.data[index].id))
                        .then((response) => {
                            toast.success(response.data.message);
                            if (response.data.success) {
                                this.accounts.data.splice(index, 1);
                                return;
                            }
                        })

                        .catch((error) => {
                            if (error.response.status == 400) {
                                toast.error(error.response.data.message);
                            }
                        });
                } else if (result.dismiss === 'cancel') {
                    Swal.fire({
                        text: " was not deleted.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        }
                    });
                }

            });
        },
    },
    created() {
    },
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
                {{ title }}
            </li>
        </template>
        <!--begin::Navbar-->
        <Header :company="company.data" />
        <div class="card">
            <div class="card-header">
                <!--begin::Title-->
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ title }}</h3>
                </div>
                <!--end::Card title-->
                <button class="btn btn-primary align-self-center" v-if="!isEdit"
                    @click="this.isAdd = true, this.form = {}"><i class="bi bi-plus-circle"></i>Add New Account
                </button>
                <!--end::Title-->
            </div>
            <div class="card-body">
                <!--begin::Form-->
                <div class="row" v-if="isEdit || isAdd">
                    <div class="col-6">
                        <account-form @submitted="submit" :account="form">
                            <template #action>
                                <div class="d-flex justify-content-end">
                                    <!--begin::Button-->
                                    <button type="button" class="btn btn-outline-secondary me-5"
                                        @click="this.isEdit = false, this.isAdd = false">Discard</button>
                                    <!--begin::Button-->
                                    <button type="submit" class="btn btn-primary"
                                        :class="{ 'text-white-50': form.processing }">
                                        <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        Save Changes
                                    </button>
                                </div>
                            </template>
                        </account-form>
                    </div>
                </div>
                <div v-else>
                    <div class="row" v-if="accounts.data.length > 0">
                        <div class="col-4 mb-5" v-for="(account, index) in accounts.data" :key="index">
                            <account-card :account="account">
                                <template #action>
                                    <div class="" style="position: absolute; top: 5px;right: 5px;">
                                        <a href="#"
                                            class="btn btn-sm btn-icon btn-light btn-active-light-primary menu-dropdown"
                                            :id="`dropdown-account-${index}`" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <div class="menu menu-sub dropdown-menu  menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            :aria-labelled:by="`dropdown-account-${id}`">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <span class="menu-link px-3" @click="toggleModal(true, account)">
                                                    Edit
                                                </span>
                                            </div>
                                            <!--end::Menu item-->

                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <span class="menu-link px-3" @click="confirmDelete(index
                                                )">
                                                    Delete
                                                </span>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                    </div>
                                </template>
                            </account-card>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-content-center pt-10 pb-10" v-else>
                    <div class="text-center py-10">
                        <img src="/assets/images/empty/emptybank.png" style="height:100px;" />
                        <div class="fw-bold fs-2 text-gray-900 mt-5">No Account found in your company!</div>
                        <p>Add A New Bank Account</p>
                        <button class="btn btn-primary align-self-center" v-if="!isEdit"
                            @click="this.isAdd = true, this.form = {}"><i class="bi bi-plus-circle"></i>Add New Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
