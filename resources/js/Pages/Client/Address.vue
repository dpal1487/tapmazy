<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import AddressForm from "./Components/AddressForm.vue";
import Header from "./Components/Header.vue";
import { toast } from "vue3-toastify";
export default defineComponent({
    props: ["client", "address", "countries"],
    components: {
        AppLayout,
        Link,
        Head,
        Multiselect,
        AddressForm,
        Header,
    },
    data() {
        return {
            isEdit: false,
            isAdd: false,
            form: {
                processing: false,
            },
            title: "Client Address",
        };
    },
    methods: {
        submit(form) {
            this.form = form;
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(
                    this.route(
                        "client.addUpdateAddress",
                        this.client.data.id
                    ),
                    {
                        onSuccess: (data) => {
                            toast.success(
                                this.$page.props.jetstream.flash.message
                            );
                            this.isEdit = false;
                            this.isAdd = false;
                        },
                        onError: (data) => {
                            console.log(data);
                        },
                    }
                );
        },
        toggleModal(isEdit, address) {
            this.isEdit = isEdit;
            this.form = address;
        },
        async confirmDelete(id, index) {
            this.isLoading = true;
            await utils.deleteIndexDialog(route('client.delAddress', id), this.contacts.data, index);
            this.isLoading = false;
        },
    },
    created() { },
});
</script>
<template>
    <Head :title="title" />
    <app-layout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/clients" class="text-muted text-hover-primary">Clients</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ client.data.name }}
            </li>
        </template>
        <Header :client="client.data" :address="address?.data" />
        <div class="card">
            <div class="card-header">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Manage Address</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-10">
                        <address-form @submitted="submit" :countries="countries.data" :address="address?.data"
                            :client="client.data">
                            <template #action>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary"
                                        :class="{ 'text-white-50': form.processing, }">
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
            </div>
        </div>
    </app-layout>
</template>
