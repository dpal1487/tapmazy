<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Multiselect from "@vueform/multiselect";
import Pagination from "../../Jetstream/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/css/index.css';
import utils from "../../utils";
import NoRecordMessage from "../../Components/NoRecordMessage.vue";
import SearchBar from "../../Components/SearchBar.vue";
export default defineComponent({
    props: ["faqs_categories"],

    data() {
        return {
            form: {},
            title: "Faq's Category",
            isLoading: false,
            tbody: [
                "Image",
                "Name",
                "Status",
                "Action",
            ],
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        Pagination,
        Multiselect,
        Loading,
        NoRecordMessage,
        SearchBar
    },
    methods: {
        async confirmDelete(id, index) {
            this.isLoading = true;
            await utils.deleteIndexDialog(route('faq-category.destroy', id), this.faqs_categories.data, index);
            this.isLoading = false;
        },
        search(form) {
            this.form = form;
            Inertia.get(
                "/faqs-categories",
                this.form,
            );
        },
        async changeStatus(status, id) {
            this.isLoading = true;
            await utils.changeStatus(route('faq-category.status'), { id: id, status: status });
            this.isLoading = false;
        },
    },
});
</script>
<template>
    <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="true" />
    <app-layout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <span class="text-muted">{{ title }}</span>
            </li>
        </template>
        <template #toolbar>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <Link href="/faq-category/create" class="btn btn-sm fw-bold btn-primary">
                <i class="bi bi-plus-circle"></i>Add {{ title }}</Link>
            </div>
        </template>

        <Head :title="title" />
        <div class="card">
            <div>
                <SearchBar @search="search" :status="$page.props.ziggy.status" />

            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-center table-row-dashed fs-6 gy-5 text-left">
                        <thead>
                            <tr class="text-gray-800 fw-bold fs-6 text-uppercase">
                                <th v-for="(th, index) in tbody" :key="index">
                                    {{ th }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600" v-if="faqs_categories.data.length > 0">
                            <tr v-for="(faq_category, index) in faqs_categories.data" :key="index">
                                <td>
                                    <div class="d-flex">
                                        <Link :href="`/faq-category/${faq_category.id}`"
                                            class="d-block symbol symbol-50px symbol-lg-50px symbol-fixed position-relative">
                                        <img v-if="faq_category?.image" :src="faq_category?.image?.url" alt="image"
                                            class="rounded" />
                                        <img v-else src="/assets/media/svg/avatars/blank.svg" alt="image" class="rounded">
                                        </Link>
                                    </div>
                                </td>
                                <td>

                                    <Link :href="`/faq-category/${faq_category.id}`"
                                        class="text-gray-700 text-hover-primary fs-5 fw-bold mb-1">{{
                                            faq_category?.title }}</Link>
                                </td>
                                <td>
                                    <div class="form-switch form-check-solid d-block form-check-custom form-check-success">
                                        <input class="form-check-input h-20px w-30px" type="checkbox"
                                            @input="changeStatus($event.target.checked, faq_category.id)"
                                            :checked="faq_category.status == 1 ? true : false" />
                                    </div>
                                </td>
                                <td class="min-w-150px">
                                    <a href="#" class="btn btn-sm btn-light menu-dropdown"
                                        :id="`dropdown-${faq_category.id}`" data-bs-toggle="dropdown">Actions
                                        <span class="svg-icon svg-icon-5 m-0">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                        </span>
                                    </a>
                                    <div class="text-left dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        :aria-labelled:by="`dropdown-${faq_category.id}`">
                                        <div class="menu-item px-3">
                                            <Link :href="`/faq-category/${faq_category.id}/edit`" class="menu-link"><i
                                                class="bi bi-pencil me-2"></i>Edit
                                            </Link>
                                        </div>
                                        <div class="menu-item px-3">
                                            <span @click="confirmDelete(faq_category.id, index)" class="menu-link"><i
                                                    class="bi bi-trash3 me-2"></i>Delete</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="fw-semibold text-gray-600" v-else>
                            <tr class="text-gray-600 fw-bold fs-7 align-middle text-uppercase h-100px">
                                <td colspan="3" class="text-center h-full">
                                    <NoRecordMessage />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer" v-if="faqs_categories.data.length > 0">
                <Pagination :links="faqs_categories" />
            </div>
        </div>
    </app-layout>
</template>
