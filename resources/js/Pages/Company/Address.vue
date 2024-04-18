<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import AddressForm from "./Components/Address/AddressForm.vue";
import Header from "./Components/Header.vue";
import { toast } from "vue3-toastify";
import AddressCard from "./Components/Address/AddressCard.vue";
import Swal from "sweetalert2";
export default defineComponent({
    props: ["company", 'addresses', 'countries', 'address'],
    components: {
        AppLayout,
        Link,
        Head,
        Multiselect,
        AddressForm,
        Header,
        AddressCard
    },
    data() {
        return {
            isEdit: false,
            isAdd: false,
            form: {
                processing: false,
            },
            title: 'Manage Addresses'
        }
    },
    methods: {
        submit(form) {
            this.form = form;
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(this.isEdit ? this.route("company.updateAddress", this.company.data.id) : this.route("company.addAddress", this.company.data.id), {
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
        toggleModal(isEdit, address) {
            this.isEdit = isEdit;
            this.form = address;
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
                        .delete(this.route("company.delAddress", this.addresses.data[index].id))
                        .then((response) => {
                            toast.success(response.data.message);
                            if (response.data.success) {
                                this.addresses.data.splice(index, 1);
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
        <Header :company="company.data" />
        <div class="card">
            <div class="card-header">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ title }}</h3>
                </div>
                <button class="btn btn-primary align-self-center" v-if="!isEdit"
                    @click="this.isAdd = true, this.form = {}"><i class="bi bi-plus-circle"></i>Add Address
                </button>
            </div>
            <div class="card-body">
                <div class="row" v-if="isEdit || isAdd">
                    <div class="col-6">
                        <address-form @submitted="submit" :countries="countries.data" :address="form">
                            <template #action>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-outline-secondary me-5"
                                        @click="this.isEdit = false, this.isAdd = false">Discard</button>
                                    <button type="submit" class="btn btn-primary"
                                        :class="{ 'text-white-50': form.processing }">
                                        <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        Save Changes
                                    </button>
                                </div>
                            </template>
                        </address-form>
                    </div>
                </div>
                <div v-else>
                    <div class="row" v-if="addresses.data.length > 0">
                        <div class="col-6 mb-5" v-for="(address, index) in addresses.data" :key="index">
                            <address-card :address="address">
                                <template #action>
                                    <div class="d-flex align-items-center py-2">
                                        <button class="btn btn-sm btn-light btn-active-light-primary me-3" @click="confirmDelete(index
                                        )">
                                            <!--begin::Indicator label-->
                                            <span class="indicator-label">Delete</span>
                                            <!--end::Indicator label-->
                                        </button>
                                        <button class="btn btn-sm btn-light btn-active-light-primary"
                                            @click="toggleModal(true, address)">Edit</button>
                                    </div>
                                </template>
                            </address-card>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-content-center pt-10 pb-10" v-else>
                        <div class="text-center py-10">
                            <img src="/assets/images/empty/emptyaddress.png" style="height:100px;" />
                            <div class="fw-bold fs-2 text-gray-900 mt-5">No Address found in your company!</div>
                            <p>Add A New Address</p>
                            <button class="btn btn-primary align-self-center" v-if="!isEdit"
                                @click="this.isAdd = true, this.form = {}"><i class="bi bi-plus-circle"></i>Add Address
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
