<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import Pagination from "../../Jetstream/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import { toast } from "vue3-toastify";
import Loading from "vue-loading-overlay";
import utils from "../../utils";
import NoRecordMessage from "../../Components/NoRecordMessage.vue";

export default defineComponent({
    props: ["services"],
    data() {
        return {
            form: {},
            selectAll: false,
            tbody: [
                "Image",
                "Name",
                "Page",
                "Description",
                "Action",
            ],
            checkbox: [],
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        Pagination,
        Multiselect,
        Loading,
        NoRecordMessage
    },
    methods: {

        async confirmDelete(id, index) {
            this.isLoading = true;
            await utils.deleteIndexDialog(route('service.destroy', this.services.data[index].id), this.services.data, index);
            this.isLoading = false;
        },
        search() {
            Inertia.get("/services", this.form);
        }
    },
});
</script>
<template>
    <app-layout title="Service">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/services" class="text-muted text-hover-primary">Services</Link>
            </li>
        </template>
        <template #toolbar>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <Link href="/service/create" class="btn btn-sm fw-bold btn-primary">
                <i class="bi bi-plus-circle"></i>Add New Service</Link>
            </div>
        </template>

        <Head title="Service" />
        <div class="card">
            <div>
                <form class="card-header justify-content-start p-5 gap-3" @submit.prevent="search()">
                    <div class="d-flex align-items-center position-relative">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"><svg width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                    transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                <path
                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                    fill="currentColor"></path>
                            </svg>
                        </span>
                        <input type="text" v-model="form.q" class="form-control form-control-solid w-250px ps-14"
                            placeholder="Search " />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-gray-700 fw-bold fs-7 text-uppercase">
                                <th v-for="(th, index) in tbody" :key="index">
                                    {{ th }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-500" v-if="services.data.length > 0">
                            <tr v-for="(service, index) in services.data" :key="index">
                                <td v-if="service.image">
                                    <Link class="symbol symbol-50px me-5" :href="`/service/${service.id}`">
                                    <img alt="Logo" :src="service.image?.url">
                                    </Link>
                                </td>
                                <td v-else>
                                    <Link class="symbol symbol-50px me-5" :href="`/service/${service.id}`">
                                    <img alt="Logo" src="/assets/images/comingsoon.png">
                                    </Link>
                                </td>
                                <td>
                                    <Link :href="'/service/' + service.id"
                                        class="text-gray-700 text-hover-primary fs-5 fw-bold mb-1 text-capitalize">{{
                                            service.name }}
                                    </Link>
                                </td>
                                <td class="text-capitalize">{{ service.page }}</td>

                                <td class="text-capitalize" v-html="service.description"></td>
                                <td class="min-w-150px">
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary"
                                            :id="`dropdown-${service.id}`" data-bs-toggle="dropdown"
                                            aria-expanded="false">Actions
                                            <span class="svg-icon svg-icon-5 m-0">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu text-small menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            :aria-labelled:by="`dropdown-${service.id}`">
                                            <li class="menu-item px-3">
                                                <Link
                                                    class="btn btn-sm dropdown-item align-items-center justify-content-center"
                                                    :href="`/service/${service.id}/edit`">
                                                <i class="bi bi-pencil me-2"></i>
                                                Edit
                                                </Link>
                                            </li>
                                            <li class="menu-item px-3">
                                                <button @click="confirmDelete(service.id, index)"
                                                    class="btn btn-sm dropdown-item align-items-center justify-content-center">
                                                    <i class="bi bi-trash3 me-2"></i> Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="fw-semibold text-gray-600" v-else>
                            <tr class="text-gray-600 fw-bold fs-7 align-middle text-uppercase h-100px">
                                <td colspan="5" class="text-center h-full">
                                    <NoRecordMessage />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer" v-if="services.data.length > 0">
                <Pagination :links="services" />
            </div>
        </div>
    </app-layout>
</template>
