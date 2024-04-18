<script>
import { defineComponent } from 'vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import AppLayout from '@/Layouts/AppLayout.vue'
import MenuForm from './Components/MenuForm.vue';
import Pagination from "../../Jetstream/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import utils from "../../utils";
import NoRecordMessage from '../../Components/NoRecordMessage.vue';
export default defineComponent({
    props: ['menu_lists'],
    emits: ['Onhide'],
    data() {
        return {
            item: [],
            title: "Menus",
            form: {},
            tbody: [
                "Menu",
                "Title",
                "Url",
                "Target",
                "Icon Class",
                "Color",
                "Parent",
                "Order By",
                "Route",
                "Parameters",
                "Action",
            ],
            isLoading: false,
            fullPage: true,

        }
    },
    components: {
        Head,
        AppLayout,
        MenuForm,
        Pagination,
        MenuForm,
        Link,
        NoRecordMessage
    },
    methods: {
        async confirmDelete(id, index) {
            this.isLoading = true;
            await utils.deleteIndexDialog(route('menu-item.destroy', this.menu_lists.data[index].id), this.menu_lists.data, index);
            this.isLoading = false;
        },

        search() {
            Inertia.get(
                "/menu-items",
                this.form,
                {
                    preserveState: true,
                }
            );
        },

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
                Menus
            </li>
        </template>
        <template #toolbar>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <Link href="/menu-item/create" class="btn btn-sm fw-bold btn-primary">
                <i class="bi bi-plus-circle"></i>Add Menu Item</Link>
            </div>
        </template>
        <div class="card">
            <div>
                <form class="card-header justify-content-start py-5 gap-2 gap-md-5" @submit.prevent="search">
                    <!--begin::Card title-->
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"><svg width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                    transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                <path
                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                    fill="currentColor"></path>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <input type="text" v-model="form.q" class="form-control form-control-solid w-250px ps-14"
                            placeholder="Search " />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Search
                    </button>
                    <!--end::Search-->
                    <!--end::Card toolbar-->
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 text-center">
                        <thead>
                            <tr class="text-gray-400 text-center fw-bold fs-7 min-w-100px text-uppercase">
                                <th class="min-w-100px" v-for="(th, index) in tbody" :key="index">
                                    {{ th }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600" v-if="menu_lists.data.length > 0">
                            <tr class="text-center" v-for="(item, index) in menu_lists?.data" :key="index">
                                <td>{{ item.menu.name }}</td>
                                <td>{{ item.title }}</td>
                                <td>{{ item.url }}</td>
                                <td>{{ item.target || 'Null' }}</td>
                                <td>{{ item.icon_class }}</td>
                                <td>{{ item.color }}</td>
                                <td>{{ item.parent?.title || 'Null' }}</td>
                                <td>{{ item.order_by }}</td>
                                <td>{{ item.route }}</td>
                                <td>{{ item.parameters }}</td>
                                <td class="min-w-150px">
                                    <a href="#" class="btn btn-sm btn-light menu-dropdown" :id="`dropdown-${item.id}`"
                                        data-bs-toggle="dropdown">Actions
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
                                        :aria-labelled:by="`dropdown-${item.id}`">
                                        <div class="menu-item px-3">
                                            <Link :href="`/menu-item/${item.id}/edit`" class="menu-link"><i
                                                class="bi bi-pencil me-2"></i>Edit
                                            </Link>
                                        </div>
                                        <div class="menu-item px-3">
                                            <span @click="confirmDelete(item.id, index)" class="menu-link"><i
                                                    class="bi bi-trash3 me-2"></i>Delete</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="fw-semibold text-gray-600" v-else>
                            <tr class="text-gray-600 fw-bold fs-7 align-middle text-uppercase h-100px">
                                <td colspan="11" class="text-center h-full">
                                    <NoRecordMessage />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer" v-if="menu_lists.data.length > 0">
                <Pagination :links="menu_lists" />
            </div>
        </div>
    </app-layout>
</template>
